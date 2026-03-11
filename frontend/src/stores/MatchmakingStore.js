import { defineStore } from "pinia";
import { socket } from "../config/websocket";
import { toast } from "vue-sonner";

export const useMatchmakingStore = defineStore("matchmaking", {

  state: () => ({
    isSearching: false,
    playersInQueue: 0,
    startedAt: null,
    timer: 0,
    intervalId: null
  }),

  actions: {

    start() {
      socket.emit("matchmaking:join");

      this.isSearching = true;
      this.startedAt = Date.now();

      this.intervalId = setInterval(() => {
        this.timer = Math.floor((Date.now() - this.startedAt) / 1000);
      }, 1000);
    },

    stop() {
      socket.emit("matchmaking:leave");

      this.isSearching = false;
      this.timer = 0;
      this.startedAt = null;

      if (this.intervalId) {
        clearInterval(this.intervalId);
        this.intervalId = null;
      }
    },

    initListeners() {

      socket.on("matchmaking:queue-length-updated", (length) => {
        this.playersInQueue = length;
      });

      socket.on("matchmaking:error", (err) => {
        this.stop();
        toast.error(err.message);
      });

    }

  }

});