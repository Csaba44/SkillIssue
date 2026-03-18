import { createServer } from "http";
import { Server } from "socket.io";
import { userAuthMiddleware } from "./middleware/UserAuthMiddleware.js";
import { runMatchmaking } from "./controllers/matchmakingController.js";
import { cleanPendingMatches } from "./services/cleanPendingMatches.js";
import { checkQuestionTimes } from "./services/rankedGameService.js";

const MM_TICKS = 2000; // Run matchmaking every 2 seconds

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

// Middlewares
io.use(userAuthMiddleware);

setInterval(() => {
  runMatchmaking();
  cleanPendingMatches();
}, MM_TICKS);

setInterval(() => {
  checkQuestionTimes();
}, 1000);

httpServer.listen(3000, "0.0.0.0", () => {
  console.log("Socket.IO server running on port 3000");
});