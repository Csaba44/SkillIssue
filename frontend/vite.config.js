import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue(), tailwindcss()],
  server: {
    proxy: {
      "/api": {
        target: "http://nginx",
        changeOrigin: true,
      },
      "/socket.io": {
        target: "http://websocket:3000",
        changeOrigin: true,
        secure: false,
        ws: true,
      },
    },
    allowedHosts: ["skillissue.local"],
  },
});
