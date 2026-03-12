import { defineStore } from "pinia";
import { socket } from "../config/websocket";
import { toast } from "vue-sonner";
import router from "../config/router";


export const useGameStore = defineStore("game", {
  state: () => ({
    match: null,
    currRoundNumber: null,
  }),

  actions: {
    handleMatch(match) {
      this.match = match;
      router.push("/game/ranked/" + match.match_uuid);
      console.log(match);
    },

    initListeners() {
      socket.on("game:started", (match) => {
        this.handleMatch(match);
      });

      socket.on("game:active-game", (match) => {
        this.handleMatch(match);
      });

      socket.on("game:error", (err) => {
        toast.error(err.message);
      });
    }

  }

});