import { io } from "../server.js";
import { getActiveGame } from "../services/rankedGameService.js";
import { gameState } from "../states/matchmakingState.js";
import { determineOpponent } from "../utils/determineOpponent.js";

function userConnected(socket, userActiveGame) {
  if (userActiveGame) {
    socket.emit("game:active-game", userActiveGame);
    const opponent = determineOpponent(socket.user.id, userActiveGame);

    // Update socket id for player reconnecting
    const whichPlayer = opponent.playerKey == "playerA" ? "playerB" : "playerA";

    gameState.ongoingGames.get(userActiveGame.match_uuid)[whichPlayer].socketId = socket.id;


    io.to(opponent.player.socketId).emit("game:opponent-reconnected");
    console.log("RECONNECTED");
  }
};

function userDisconnected(socket) {
  const activeGame = getActiveGame(socket.user.id);

  if (activeGame) {
    const opponent = determineOpponent(socket.user.id, activeGame);

    io.to(opponent.player.socketId).emit("game:opponent-disconnected");
    console.log("DISCONNECTED");
  }
}

export const gameController = {
  userConnected,
  userDisconnected
}