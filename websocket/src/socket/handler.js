import { io } from "../server.js";


export function handleConnection(socket) {
  socket.on("test", (data, callback) => {
    callback({ message: "pong", received: data });
  });
}
