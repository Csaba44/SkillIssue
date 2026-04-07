import axios from "axios";

const SERVICE_TOKEN = process.env.SERVICE_TOKEN;

const api = axios.create({
  baseURL: process.env.APP_ENV == "production" ? "https://api.skillissue.hu" : "http://nginx",
  timeout: 10000,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: "application/json",
    Authorization: `Bearer ${SERVICE_TOKEN}`,
  },
});

export default api;
