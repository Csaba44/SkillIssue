import { defineStore } from "pinia";
import { socket } from "../config/websocket";
import { toast } from "vue-sonner";
import router from "../config/router";


export const useGameStore = defineStore("game", {
  state: () => ({
    match: null,
    isOpponentOnline: null,
    currentRound: null,
    currentQuestion: null,
    currentSubject: null,
    currentAnswers: null,

    actualAnswers: { correctAnswerId: null, opponentAnswerId: null }
  }),

  actions: {
    handleMatch(match) {
      this.match = match;
      this.isOpponentOnline = true;
      router.push("/game/ranked/" + match.match_uuid);
    },
    handleStopMatch() {
      this.match = null;
      this.isOpponentOnline = null;
      this.currentRound = null;
      this.currentQuestion = null;
      this.currentAnswers = null;
      this.currentSubject = null;
      this.actualAnswers = { correctAnswerId: null, opponentAnswerId: null };
    },
    submitAnswer(answerId) {
      // Check if answer exists on current question
      if (this.currentAnswers.filter(a => a.id === answerId).length <= 0) return;

      socket.emit("game:submit-answer", answerId);
      console.log("Submitted.")
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

      socket.on("game:new-question", (data) => {
        this.currentRound = data.currentRound;
        this.currentSubject = data.subject;
        this.currentQuestion = data.question;
        this.currentAnswers = data.answers;
      });

      socket.on("game:answers", (data) => {
        this.actualAnswers = {
          correctAnswerId: data.correctAnswerId,
          opponentAnswerId: data.opponentAnswerId
        }
      });

      socket.on("game:finished", (data) => {
        router.push(`/summary/ranked/${this.match.match_uuid}`);
        this.handleStopMatch();
        console.log("FINISHED! ", data);
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