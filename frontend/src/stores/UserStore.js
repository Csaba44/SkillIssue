import { defineStore } from "pinia";
import api from "../config/api";

export const useUserStore = defineStore("counter", {
  state: () => {
    return {
      user: null,
      isAuthenticated: null,
    };
  },
  actions: {
    async verifySession() {
      try {
        const response = await api.get("/api/users");
        this.user = response.data;
        this.isAuthenticated = true;
      } catch (error) {
        console.log(error);
        if (!error.response) return console.error("Check your internet connection.");
        if (error.response?.status === 401) {
          this.user = false;
          this.isAuthenticated = false;
        }
      }
    },
    async logout() {
      try {
        await api.get("/api/csrf-cookie");
        const response = await api.post("/api/logout");
        await this.verifySession();
      } catch (error) {
        if (!error.response) return console.error("Check your internet connection.");

        console.error(error);
      }
    },
  },
});
