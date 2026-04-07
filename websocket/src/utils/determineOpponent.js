export function determineOpponent(userId, match) {
  let playerKey = "";

  if (match.playerA.userId == userId) playerKey = "playerB";
  else if (match.playerB.userId == userId) playerKey = "playerA";
  else return false;

  return { player: match[playerKey], playerKey };
}
