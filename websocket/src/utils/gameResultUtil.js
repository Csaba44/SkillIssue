export function isDraw(scores) {
  const values = Object.values(scores)

  const same = values.every(v => v === values[0])

  return same;
}