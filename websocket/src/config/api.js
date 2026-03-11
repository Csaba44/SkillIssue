import axios from "axios";

const api = axios.create({
  baseURL: process.env.APP_ENV == "production"
    ? "https://api.skillissue.hu"
    : "http://nginx",
  timeout: 10000,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: "application/json",
  },
});

export default api;
