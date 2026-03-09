
import { handleConnection } from "./socket/handler.js";
import { io } from "./server.js";


io.on("connection", handleConnection);


