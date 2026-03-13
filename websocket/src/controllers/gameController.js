import api from "../config/api.js";
import { io } from "../server.js";
import { formatFinalResults, getActiveGame, leaveUsersFromGame } from "../services/rankedGameService.js";
import { joinUserToRoom } from "../services/roomService.js";
import { gameState } from "../states/matchmakingState.js";
import { determineOpponent } from "../utils/determineOpponent.js";
import { formatQuestionData } from "../utils/formatQuestionData.js";

const MAX_ROUNDS = process.env.MAX_ROUNDS ?? 5;
const AFTER_QUESTION_TIMEOUT = process.env.AFTER_QUESTION_TIMEOUT ?? 3500; // ms

function userConnected(socket, userActiveGame) {
  if (userActiveGame) {
    socket.emit("game:active-game", userActiveGame);
    const opponent = determineOpponent(socket.user.id, userActiveGame);

    // Update socket id for player reconnecting
    const whichPlayer = opponent.playerKey == "playerA" ? "playerB" : "playerA";

    gameState.ongoingGames.get(userActiveGame.match_uuid)[whichPlayer].socketId = socket.id;
    joinUserToRoom(socket.id, userActiveGame.roomId);

    // Send current question to reconnecting player
    const questions = gameState.ongoingGames.get(userActiveGame.match_uuid).questions;

    if (questions.length > 0) {
      socket.emit("game:new-question", formatQuestionData(questions.at(-1)));
    }

    // Advise opponent of player reconnection
    io.to(opponent.player.socketId).emit("game:opponent-reconnected");
    console.log("RECONNECTED");
  }
};

function userDisconnected(socket) {
  const activeGame = getActiveGame(socket.user.id);

  if (activeGame) {
    const opponent = determineOpponent(socket.user.id, activeGame);

    // Advise opponent of player disconnection
    io.to(opponent.player.socketId).emit("game:opponent-disconnected");
    console.log("DISCONNECTED");
  }
}

async function nextQuestion(match) {
  try {
    const res = await api.post("/api/internal/questions/get-one", { ranked_token: match.ranked_token });

    const data = formatQuestionData(res.data);

    gameState.ongoingGames.get(match.match_uuid).questions.push({
      ...res.data, playerAnswers: {
        playerA: { answerId: null },
        playerB: { answerId: null }
      }
    });

    io.to(match.roomId).emit("game:new-question", data);
  } catch (error) {
    console.error(error);
  }
}

async function submitAnswer(socket, answerId) {
  const match = getActiveGame(socket.user.id);
  if (!match) return socket.emit("game:error", { message: "Nincs ilyen játszma." });

  try {
    const question = match.questions.at(-1);
    const res = await api.post(`/api/internal/answers/verify/${answerId}`, {
      answering_user_id: socket.user.id,
      ranked_token: match.ranked_token,
      question_token: question.question_token,
    });


    const opponent = determineOpponent(socket.user.id, match);
    const opponentKey = opponent.playerKey;
    const playerKey = opponentKey == "playerA" ? "playerB" : "playerA";

    const isUserFinished = res.data.finished_for_user;

    if (isUserFinished) {
      question.playerAnswers[playerKey].answerId = answerId;
    }

    const bothFinishedForRound = question.playerAnswers[opponentKey].answerId !== null && question.playerAnswers[playerKey].answerId !== null;

    const gameFinished = res.data.game_finished ?? false;

    // WHEN ROUND IS OVER
    if (bothFinishedForRound) {
      const playerAAnswer = question.playerAnswers.playerA.answerId;
      const playerBAnswer = question.playerAnswers.playerB.answerId;

      sendCorrectAndOpponentAnswers(match, res.data.correct_answer_id, { playerA: playerAAnswer, playerB: playerBAnswer });

      if (!gameFinished) {
        setTimeout(() => {
          nextQuestion(match);
        }, AFTER_QUESTION_TIMEOUT);
      }
    }

    if (gameFinished) {
      setTimeout(() => {
        const results = formatFinalResults(match, res.data);
        io.to(match.roomId).emit("game:finished", results);
        leaveUsersFromGame(match);
      }, AFTER_QUESTION_TIMEOUT);
    }
  } catch (error) {
    const message =
      error.response?.data?.message ||
      error.message ||
      "Unknown error";

    socket.emit("game:error", message);
    console.error(error);
  }
}

function sendCorrectAndOpponentAnswers(match, correctAnswerId, playerAnswers) {
  console.log(match.roomId, { correctAnswerId, playerAnswers })

  io.to(match.playerA.socketId).emit("game:answers", { opponentAnswerId: playerAnswers.playerB, correctAnswerId: correctAnswerId });
  io.to(match.playerB.socketId).emit("game:answers", { opponentAnswerId: playerAnswers.playerA, correctAnswerId: correctAnswerId });
}

export const gameController = {
  userConnected,
  userDisconnected,
  nextQuestion,
  submitAnswer,
}