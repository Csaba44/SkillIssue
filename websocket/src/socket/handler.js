import { matchmakingController } from "../controllers/matchmakingController.js";
import { io } from "../server.js";


export function handleConnection(socket) {
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
