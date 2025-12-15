import { createRouter, createWebHistory } from "vue-router";


// Eager loaded routes
import HomeView from "../views/HomeView.vue";

// Lazy loaded routes
const TestView = () => import("../views/TestView.vue");
const RegisterView = () => import("../views/RegisterView.vue");
const LoginView = () => import("../views/LoginView.vue");


const routes = [
  { path: "/", name: "home", component: HomeView },
  { path: "/test", name: "test", component: TestView },
  { path: "/register", name: "register", component: RegisterView },
  { path: "/login", name: "login", component: LoginView },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
});


export default router;
