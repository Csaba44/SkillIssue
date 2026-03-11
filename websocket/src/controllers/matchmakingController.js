const matchmakingQueue = new Map();

export function joinMatchmaking(socket) {

  if (matchmakingQueue.has(socket.id)) {
    return;
  }

  matchmakingQueue.set(socket.id, {
    socketId: socket.id,
    userId: socket.user.id,
    userName: socket.user.name,
    userElo: socket.user.elo,
    joinedAt: Date.now(),
    lastHeartbeat: Date.now()
  });

  console.log("matchmaking joined, user:", socket.user.name);
  console.log(matchmakingQueue)

}

export function leaveMatchmaking(socket) {

  if (!matchmakingQueue.has(socket.id)) {
    return;
  }

  matchmakingQueue.delete(socket.id);

  console.log("matchmaking left, user:", socket.user.name);

  console.log(matchmakingQueue)
}

export const matchmakingController = {
  leaveMatchmaking,
  joinMatchmaking
}