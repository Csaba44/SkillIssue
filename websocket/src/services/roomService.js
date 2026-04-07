import { io } from "../server.js";

export function joinUserToRoom(socketId, room) {
  const socket = io.sockets.sockets.get(socketId);

  if (!socket) return false;

  socket.join(room);
  return true;
}

export function leaveUserFromRoom(socketId, room) {
  const socket = io.sockets.sockets.get(socketId);

  if (!socket) return false;

  if (!socket.rooms.has(room)) return false;

  socket.leave(room);
  return true;
}
