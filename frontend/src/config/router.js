import { createRouter, createWebHistory } from "vue-router";


// Eager loaded routes
import HomeView from "../views/HomeView.vue";

// Lazy loaded routes
const TestView = () => import("../views/TestView.vue");


const routes = [
  { path: "/", name: "home", component: HomeView },
  { path: "/test", name: "test", component: TestView },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
});


export default router;
