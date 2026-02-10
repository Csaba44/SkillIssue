import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.PROD
    ? "https://api.skillissue.hu"
    : "/",
  timeout: 10000,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: "application/json",
  },
});

export default api;
