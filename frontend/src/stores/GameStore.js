import { defineStore } from "pinia";
import { socket } from "../config/websocket";
import { toast } from "vue-sonner";
import router from "../config/router";


export const useGameStore = defineStore("game", {
  state: () => ({
    match: null,
    currRoundNumber: null,
    isOpponentOnline: null,
  }),

  actions: {
    handleMatch(match) {
      this.match = match;
      this.isOpponentOnline = true;
      router.push("/game/ranked/" + match.match_uuid);
      console.log(match);
    },
    handleStopMatch() {
      this.match = null;
      this.currRoundNumber = null;
      this.isOpponentOnline = null;
    },

    initListeners() {
      socket.on("game:started", (match) => {
        this.handleMatch(match);
      });

      socket.on("game:active-game", (match) => {
        this.handleMatch(match);
      });

      socket.on("game:opponent-disconnected", () => {
        this.isOpponentOnline = false;
        console.log("DISCONNECT")
        toast.warning("Az ellenfél lecsatlakozott.");
      });

      socket.on("game:opponent-reconnected", () => {
        this.isOpponentOnline = true;
        console.log("RECONNECT")
        toast.success("Az ellenfél visszacsatlakozott.");
      });

      socket.on("game:error", (err) => {
        toast.error(err.message);
      });

      socket.on("disconnect", (reason) => {
        console.warn("Socket disconnected: " + reason);
        if (this.match) {
          toast.error("Hiba történt - a játszma törölve.");
          router.push("/dashboard");
          this.handleStopMatch();
        }
        else {
          toast.error("Hiba történt a kapcsolat kiépítésekor.");
        }
      });
    }

  }

});