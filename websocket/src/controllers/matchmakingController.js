import { io } from "../server.js";
import { createRankedGame } from "../services/rankedGameService.js";
import { matchmake } from "../services/matchmake.js";
import { gameState } from "../states/matchmakingState.js";
import crypto from "crypto";
import { joinUserToRoom } from "../services/roomService.js";
import { gameController } from "./gameController.js";

export function joinMatchmaking(socket) {
  // Duplicated queueing protection
  if (gameState.matchmakingQueue.has(socket.id)) return;

  const alreadyQueued = Array.from(gameState.matchmakingQueue.values()).some(
    (player) => player.userId === socket.user.id,
  );

  if (alreadyQueued)
    return socket.emit("matchmaking:error", { message: "Egy másik kliens már csatlakozott erről a fiókról." });

  if (isPlayerAlreadyInGame(socket.user.id)) return socket.emit("matchmaking:error", { message: "Már játékban vagy." });

  gameState.matchmakingQueue.set(socket.id, {
    socketId: socket.id,
    userId: socket.user.id,
    userName: socket.user.name,
    userElo: socket.user.elo,
    joinedAt: Date.now(),
    lastHeartbeat: Date.now(),
  });

  console.log("[MATCHMAKING UPDATE] matchmaking joined, user:", socket.user.name);

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);
}

export function leaveMatchmaking(socket) {
  if (!gameState.matchmakingQueue.has(socket.id)) {
    return;
  }

  gameState.matchmakingQueue.delete(socket.id);

  console.log("[MATCHMAKING UPDATE] matchmaking left, user:", socket.user.name);

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);
}

function getQueuePlayers() {
  const now = Date.now();

  return Array.from(gameState.matchmakingQueue.values()).map((player) => ({
    ...player,
    waitTime: Math.floor((now - player.joinedAt) / 1000),
  }));
}

function isPlayerAlreadyInGame(id) {
  for (const game of gameState.ongoingGames.values()) {
    if (game.playerA.userId == id || game.playerB.userId == id) {
      return true;
    }
  }

  return false;
}

export function runMatchmaking() {
  const players = getQueuePlayers();
  console.log("[QUEUE COUNT] " + players.length + " players");
  console.log("[PENDING GAMES CONT] " + gameState.pendingGames.size + " games");
  console.log("[ONGOING GAMES COUNT] " + gameState.ongoingGames.size + " games");
  const pairs = matchmake(players);

  pairs.forEach((pair) => {
    const tmpUuid = "tmp_" + crypto.randomUUID();

    const pendingMatch = {
      tmpUuid,
      playerA: { ...pair[0], confirmed: false },
      playerB: { ...pair[1], confirmed: false },
      createdAt: Date.now(),
    };
    gameState.pendingGames.set(tmpUuid, pendingMatch);

    pair.forEach((player) => {
      const socketId = player.socketId;

      io.to(socketId).emit("matchmaking:confirmation-needed", tmpUuid);
      gameState.matchmakingQueue.delete(socketId);
    });
  });

  io.emit("matchmaking:queue-length-updated", gameState.matchmakingQueue.size);
}

async function confirmMatchmaking(socket, tmpUuid) {
  const pendingMatch = gameState.pendingGames.get(tmpUuid);

  let confirmingPlayer = null;

  if (socket.user.id === pendingMatch.playerA.userId) {
    confirmingPlayer = "playerA";
  } else if (socket.user.id === pendingMatch.playerB.userId) {
    confirmingPlayer = "playerB";
  }

  pendingMatch[confirmingPlayer].confirmed = true;

  const bothConfirmed = pendingMatch.playerA.confirmed && pendingMatch.playerB.confirmed;

  console.log("[PLAYER PAIR FOUND] " + (bothConfirmed ? "ALL PLAYERS" : confirmingPlayer) + " accepted the game");

  // Create ranked game & remove from pending & set to ongoing
  if (bothConfirmed) {
    const playerA = pendingMatch.playerA;
    const playerB = pendingMatch.playerB;

    const match = await createRankedGame(playerA, playerB);

    if (match.errorMessage) {
      gameState.pendingGames.delete(pendingMatch.tmpUuid);
      console.log(match);
      io.to(playerA.socketId).emit("matchmaking:error", { message: match.errorMessage });
      io.to(playerB.socketId).emit("matchmaking:error", { message: match.errorMessage });
      return match.errorMessage;
    }

    gameState.ongoingGames.set(match.match_uuid, match);

    joinUserToRoom(playerA.socketId, match.roomId);
    joinUserToRoom(playerB.socketId, match.roomId);

    io.to(match.roomId).emit("game:started", match);
    gameController.nextQuestion(match);
  }
}

export const matchmakingController = {
  leaveMatchmaking,
  joinMatchmaking,
  confirmMatchmaking,
};
