import { io } from "../server.js";
import { gameState } from "../states/matchmakingState.js";

const MAX_PENDING_TIME = 15; // seconds

export function cleanPendingMatches() {
  const pending = Array.from(gameState.pendingGames.values());

  pending.forEach((match) => {
    const now = Date.now();

    // debug
    console.log(match);

    const pendingSince = Math.floor((now - match.createdAt) / 1000);

    if (pendingSince > MAX_PENDING_TIME) {
      gameState.pendingGames.delete(match.tmpUuid);
      notifyPlayer(match.playerA);
      notifyPlayer(match.playerB);
    }
  });
}

function notifyPlayer(player) {
  io.to(player.socketId).emit("matchmaking:not-accepted");
}
