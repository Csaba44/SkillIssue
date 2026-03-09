import { io } from "../server.js";

export function testService(msg) {
  io.emit("test", msg);
  console.log("test message received");
}
