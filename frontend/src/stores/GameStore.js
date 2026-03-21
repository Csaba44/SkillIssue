import { defineStore } from "pinia";
import { socket } from "../config/websocket";
import { toast } from "vue-sonner";
import router from "../config/router";
import { useUserStore } from "./UserStore";
import { determineOpponent, determinePlayer } from "../utils/determineOpponent";


export const useGameStore = defineStore("game", {
  state: () => ({
    match: null,
    isOpponentOnline: null,
    currentRound: null,
    currentQuestion: null,
    currentQuestionToken: null,
    currentSubject: null,
    currentAnswers: null,

    timeExpired: false,

    selectedAnswer: null,
    submittedAnswer: null,

    startTimerFrom: import.meta.env.VITE_RANKED_MAX_GUESS_TIME ?? 30,

    actualAnswers: { correctAnswerId: null, opponentAnswerId: null },

    rejoining: false,
  }),

  actions: {
    handleMatch(match) {
      const currentRoute = router.currentRoute.value.name;
      if (currentRoute === "questionReport" || currentRoute === "userReport") return;

      this.match = match;
      this.isOpponentOnline = true;
      router.push("/game/ranked/" + match.match_uuid);
    },
    handleStopMatch() {
      this.match = null;
      this.isOpponentOnline = null;
      this.currentRound = null;
      this.currentQuestion = null;
      this.currentQuestionToken = null;
      this.currentAnswers = null;
      this.currentSubject = null;
      this.selectedAnswer = null;
      this.submittedAnswer = null;
      this.timeExpired = null;
      this.actualAnswers = { correctAnswerId: null, opponentAnswerId: null };

      this.startTimerFrom = import.meta.env.VITE_RANKED_MAX_GUESS_TIME ?? 30;

      this.rejoining = false;

      const userStore = useUserStore();
      userStore.verifySession();
    },
    submitAnswer(answerId) {
      // Check if answer exists on current question
      if (this.currentAnswers.filter(a => a.id === answerId).length <= 0 && answerId !== null) return toast.error("A válasz nem létezik.");

      socket.emit("game:submit-answer", answerId);
      console.log("Submitted.")

      this.rejoining = false;
    },
    initListeners() {
      socket.on("game:started", (match) => {
        this.handleMatch(match);
      });

      socket.on("game:active-game", (match) => {
        toast.success("Sikeres visszacsatlakozás!");

        const userStore = useUserStore();

        const lastQuestion = match.questions.at(-1);
        this.startTimerFrom = lastQuestion.timeLeft;
        this.timeExpired = lastQuestion.timeLeft == 0;

        console.log("Timer starting from", this.startTimerFrom);

        const playerKey = determinePlayer(userStore.user, match)?.playerKey;

        const userAnswer = lastQuestion.playerAnswers[playerKey].answerId
        this.submittedAnswer = userAnswer;
        this.selectedAnswer = userAnswer;

        this.rejoining = true;

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
        this.actualAnswers = {
          correctAnswerId: null,
          opponentAnswerId: null
        }

        this.currentRound = data.currentRound;
        this.currentSubject = data.subject;
        this.currentQuestion = data.question;
        this.currentAnswers = data.answers;
        this.currentQuestionToken = data.questionToken;

        if (!this.rejoining) {

          this.timeExpired = null;
          this.startTimerFrom = import.meta.env.VITE_RANKED_MAX_GUESS_TIME ?? 30;
          this.selectedAnswer = null;
          this.submittedAnswer = null;
        }
      });

      socket.on("game:answers", (data) => {
        this.actualAnswers = {
          correctAnswerId: data.correctAnswerId,
          opponentAnswerId: data.opponentAnswerId
        }
      });

      socket.on("game:time-expired", (roundNumber) => {
        this.timeExpired = true;
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