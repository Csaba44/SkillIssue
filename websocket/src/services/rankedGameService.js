import api from "../config/api.js";
import { gameState } from "../states/matchmakingState.js";

export async function createRankedGame(playerA, playerB) {
  try {
    const res = await api.post("/api/internal/game-matches", {
      user_a_id: playerA.userId,
      user_b_id: playerB.userId,
    });

    const match = {
      roomId: `game:${res.data.match_uuid}`,
      match_uuid: res.data.match_uuid,
      ranked_token: res.data.ranked_token,
      playerA,
      playerB,
      questions: [],
      createdAt: Date.now(),
    }
    console.log(match);

    return match;

  } catch (error) {
    console.error(error);
  }
}

export function getActiveGame(id) {
  for (const game of gameState.ongoingGames.values()) {
    if (game.playerA.userId == id || game.playerB.userId == id) {
      return game;
    }
  }

  return null;
}