import { createServer } from "http";
import { Server } from "socket.io";

export const httpServer = createServer();

export const io = new Server(httpServer, {
  cors: {
    origin: [
      "http://127.0.0.1:5173",
      "http://localhost:5173",
      "https://skillissue.hu"
    ],
    methods: ["GET", "POST"],
    credentials: true
  }
});

httpServer.listen(3000, "0.0.0.0", () => {
  console.log("Socket.IO server running on port 3000");
});