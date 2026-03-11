import { io } from "../server.js";
import { matchmake } from "../services/matchmake.js";
import { gameState } from "../states/matchmakingState.js";


export function joinMatchmaking(socket) {
  // Duplicated queueing protection
  if (gameState.matchmakingQueue.has(socket.id)) return;

  const alreadyQueued = Array.from(gameState.matchmakingQueue.values())
    .some(player => player.userId === socket.user.id);

  if (alreadyQueued) return socket.emit("matchmaking:error", { message: "Egy másik kliens már csatlakozott erről a fiókról." });

  gameState.matchmakingQueue.set(socket.id, {
    socketId: socket.id,
    userId: socket.user.id,
    userName: socket.user.name,
    userElo: socket.user.elo,
    joinedAt: Date.now(),
    lastHeartbeat: Date.now()
  });

  console.log("matchmaking joined, user:", socket.user.name);

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);
}

export function leaveMatchmaking(socket) {

  if (!gameState.matchmakingQueue.has(socket.id)) {
    return;
  }

  gameState.matchmakingQueue.delete(socket.id);

  console.log("matchmaking left, user:", socket.user.name);

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);

}


function getQueuePlayers() {
  const now = Date.now();

  return Array.from(gameState.matchmakingQueue.values()).map(player => ({
    ...player,
    waitTime: Math.floor((now - player.joinedAt) / 1000)
  }));
}


export function runMatchmaking() {
  const players = getQueuePlayers();
  console.log("[QUEUE LENGTH] " + players.length + " players");
  console.log("[IN GAME LENGTH] " + gameState.inGame.size + " players");
  const pairs = matchmake(players);

  pairs.forEach(pair => {
    pair.forEach(player => {
      const socketId = player.socketId;
      // TODO: send confirmation popup, if accepted, put them to separate room

      //gameState.matchmakingQueue.delete(socketId);
      //gameState.inGame.set(socketId, player);
    });
  });

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);
}

export const matchmakingController = {
  leaveMatchmaking,
  joinMatchmaking
}