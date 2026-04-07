import { matchmakingController } from "../controllers/matchmakingController.js";
import { getActiveGame } from "../services/rankedGameService.js";
import { gameState } from "../states/matchmakingState.js";
import { gameController } from "../controllers/gameController.js";
import { io } from "../server.js";

export function handleConnection(socket) {
  socket.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);

  const userActiveGame = getActiveGame(socket.user.id);

  if (userActiveGame) gameController.userConnected(socket, userActiveGame);

  // ============ GENERAL ============
  socket.on("disconnect", () => {
    matchmakingController.leaveMatchmaking(socket);
    gameController.userDisconnected(socket);
  });

  socket.on("test", (data, callback) => {
    callback({ message: "pong", received: data });
  });
  // =================================

  // ============ MATCHMAKING ============
  socket.on("matchmaking:join", () => {
    matchmakingController.joinMatchmaking(socket);
  });

  socket.on("matchmaking:leave", () => {
    matchmakingController.leaveMatchmaking(socket);
  });

  socket.on("matchmaking:confirm", (tmpUuid) => {
    matchmakingController.confirmMatchmaking(socket, tmpUuid);
  });
  // ====================================

  // ============ GAME ============
  socket.on("game:submit-answer", (answerId) => {
    gameController.submitAnswer(socket, answerId);
  });
  // ==============================
}
