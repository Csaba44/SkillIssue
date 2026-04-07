import { handleConnection } from "./socket/handler.js";
import { io } from "./server.js";

import dotenv from "dotenv";

dotenv.config();

io.on("connection", handleConnection);
