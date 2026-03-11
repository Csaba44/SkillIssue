import { matchmake } from "../services/matchmake.js";
import { gameState } from "../states/matchmakingState.js";


export function joinMatchmaking(socket) {

  if (gameState.matchmakingQueue.has(socket.id)) {
    return;
  }

  gameState.matchmakingQueue.set(socket.id, {
    socketId: socket.id,
    userId: socket.user.id,
    userName: socket.user.name,
    userElo: socket.user.elo,
    joinedAt: Date.now(),
    lastHeartbeat: Date.now()
  });

  console.log("matchmaking joined, user:", socket.user.name);
}

export function leaveMatchmaking(socket) {

  if (!gameState.matchmakingQueue.has(socket.id)) {
    return;
  }

  gameState.matchmakingQueue.delete(socket.id);

  console.log("matchmaking left, user:", socket.user.name);
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

      gameState.matchmakingQueue.delete(socketId);
      gameState.inGame.set(socketId, player);
    });
  });
}

export const matchmakingController = {
  leaveMatchmaking,
  joinMatchmaking
}