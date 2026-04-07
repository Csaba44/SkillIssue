import api from "../config/api.js";
import { submitAnswer } from "../controllers/gameController.js";
import { io } from "../server.js";
import { gameState } from "../states/matchmakingState.js";
import { isDraw } from "../utils/gameResultUtil.js";
import { joinUserToRoom, leaveUserFromRoom } from "./roomService.js";

export async function createRankedGame(playerA, playerB) {
  try {
    const res = await api.post("/api/internal/game-matches", {
      user_a_id: playerA.userId,
      user_b_id: playerB.userId,
    });

    const match = {
      roomId: `game:${res.data.match_uuid}`,
      match_uuid: res.data.match_uuid,
      ranked_token: res.data.ranked_token,
      playerA: { ...playerA, connected: true },
      playerB: { ...playerB, connected: true },
      questions: [],
      createdAt: Date.now(),
    };
    console.log(match);

    return match;
  } catch (error) {
    console.error(error);
    return { errorMessage: error.response?.data.message ?? false };
  }
}

export function getActiveGame(id) {
  for (const game of gameState.ongoingGames.values()) {
    if (game.playerA.userId == id || game.playerB.userId == id) {
      return game;
    }
  }

  return null;
}

export function formatFinalResults(match, data) {
  const playerAId = match.playerA.userId;
  const playerBId = match.playerB.userId;

  const isGameDrawn = isDraw(data.scores);

  const winnerKey = isGameDrawn ? null : data.winner_id == playerAId ? "playerA" : "playerB";

  return {
    isDraw: isGameDrawn,
    playerA: {
      userId: playerAId,
      userName: match.playerA.userName,
      score: data.scores[playerAId],
      won: isGameDrawn ? null : winnerKey == "playerA",
      eloChange: data.elo_changes[playerAId],
    },
    playerB: {
      userId: playerBId,
      userName: match.playerB.userName,
      score: data.scores[playerBId],
      won: isGameDrawn ? null : winnerKey == "playerB",
      eloChange: data.elo_changes[playerBId],
    },
  };
}

export function leaveUsersFromGame(match) {
  leaveUserFromRoom(match.playerA.socketId, match.roomId);
  leaveUserFromRoom(match.playerB.socketId, match.roomId);

  gameState.ongoingGames.delete(match.match_uuid);
}

export function checkQuestionTimes() {
  for (const game of gameState.ongoingGames.values()) {
    const lastQuestion = game.questions.at(-1);
    if (!lastQuestion) continue;
    if (lastQuestion.timeLeft <= 0) continue;

    lastQuestion.timeLeft -= 1;
    console.log("TIMELEFT", lastQuestion.timeLeft);

    if (lastQuestion.timeLeft <= 0) {
      console.log("TIME EXPIRED!");

      io.to(game.roomId).emit("game:time-expired", lastQuestion.current_round);

      ["playerA", "playerB"].forEach((playerKey) => {
        if (!game[playerKey].connected) {
          submitAnswer(null, null, true, game[playerKey].userId);
        }
      });
    }
  }
}
