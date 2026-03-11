// === MATCHMAKING LOGIC CONSTANTS ===
const MAX_ELO_RANGE = 500;
const BASE_ELO_RANGE = 200;
const LONG_WAIT_TIME = 120;
const FACTOR = (MAX_ELO_RANGE - BASE_ELO_RANGE) / LONG_WAIT_TIME;
// ===================================

export function matchmake(queue) {
  queue = queue.sort((a, b) => a.elo - b.elo)
  const pairs = [];

  for (let i = 0; i < queue.length; i++) {
    const currPlayer = queue[i];
    const nextPlayer = queue[i + 1] ?? false;
    if (!nextPlayer) break;

    const maxWait = Math.max(currPlayer.waitTime, nextPlayer.waitTime)
    const eloRange = BASE_ELO_RANGE + (maxWait * FACTOR);

    const eloDiff = Math.abs(currPlayer.userElo - nextPlayer.userElo);

    if (eloDiff <= eloRange) {
      pairs.push([currPlayer, nextPlayer]);

      i++; // skip next player
    }

  }
  return pairs;
};

