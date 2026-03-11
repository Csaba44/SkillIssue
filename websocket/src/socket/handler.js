import { matchmakingController } from "../controllers/matchmakingController.js";
import { gameState } from "../states/matchmakingState.js";


export function handleConnection(socket) {
  socket.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);

  socket.on("test", (data, callback) => {
    callback({ message: "pong", received: data });
  });

  socket.on("matchmaking:join", () => {
    matchmakingController.joinMatchmaking(socket);
  });

  socket.on("matchmaking:leave", () => {
    matchmakingController.leaveMatchmaking(socket);
  });

  socket.on("disconnect", () => {
    matchmakingController.leaveMatchmaking(socket);
  });
}
