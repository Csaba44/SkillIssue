import { io } from "../server.js";


export function handleConnection(socket) {
  socket.on("test", (data) => {
    io.emit("test", { message: "pong", received: data });
  });
}
