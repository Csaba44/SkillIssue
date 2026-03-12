import api from "../config/api.js";

export async function createRankedGame(playerA, playerB) {
  try {
    const res = await api.post("/api/internal/game-matches", {
      user_a_id: playerA.userId,
      user_b_id: playerB.userId,
    });

    const match = {
      match_uuid: res.data.match_uuid,
      ranked_token: res.data.ranked_token,
      playerA,
      playerB,
      createdAt: Date.now(),
    }
    console.log(match);

    return match;

  } catch (error) {
    console.error(error);
  }
}