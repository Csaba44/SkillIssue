export function determineOpponent(user, match) {
  let playerKey = "";

  if (match.playerA.userId == user.id) playerKey = "playerB";
  else if (match.playerB.userId == user.id) playerKey = "playerA";
  else return false;

  return { player: match[playerKey], playerKey };
}