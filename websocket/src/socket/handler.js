import { testService } from "../services/TestService.js";

export function handleConnection(socket) {
  socket.on("test", testService);
}
