import api from "../config/api.js";

export const userAuthMiddleware = async (socket, next) => {
  try {
    const cookie = socket.handshake.headers.cookie;

    console.log("[WEBSOCKET] Authenticating user");

    const res = await api.get(
      "/api/socket-auth",
      {
        headers: {
          Cookie: cookie
        },
        withCredentials: true
      }
    );

    socket.user = res.data.user;

    console.log("[WEBSOCKET] User authneticated with userid: " + socket.user.id);

    next();
  } catch (err) {
    console.log(err.response.data);
    next(new Error("Unauthorized"));
  }
};