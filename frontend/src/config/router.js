import { createRouter, createWebHistory } from "vue-router";
import { useUserStore } from "../stores/UserStore";

// Eager loaded routes
import HomeView from "../views/HomeView.vue";
import ProfileView from "../views/ProfileView.vue";

// Lazy loaded routes
const DashboardView = () => import("../views/DashboardView.vue");
const GameView = () => import("../views/GameView.vue");
const RegisterView = () => import("../views/RegisterView.vue");
const LoginView = () => import("../views/LoginView.vue");
const AdminView = () => import("../views/AdminView.vue");
const HealthView = () => import("../views/HealthView.vue");
const SummaryView = () => import("../views/SummaryView.vue");
const ReportView = () => import("../views/ReportView.vue");
const PrivacyPolicyView = () => import("../views/PrivacyPolicyView.vue");
const TermsOfServiceView = () => import("../views/TermsOfServiceView.vue");

const routes = [
  { path: "/", name: "home", component: HomeView },
  { path: "/privacy-policy", name: "privacy-policy", component: PrivacyPolicyView },
  { path: "/terms-of-service", name: "terms-of-service", component: TermsOfServiceView },
  { path: "/register", name: "register", component: RegisterView },
  { path: "/login", name: "login", component: LoginView },
  { path: "/dashboard", name: "dashboard", component: DashboardView, meta: { requiresAuth: true } },

  { path: "/game/solo/:gameToken", name: "game", component: GameView, meta: { requiresAuth: true } },
  { path: "/game/ranked/:matchUuid", name: "gameRanked", component: GameView, meta: { requiresAuth: true } },

  { path: "/summary/ranked/:matchUuid", name: "summaryRanked", component: SummaryView, meta: { requiresAuth: true } },
  { path: "/summary/solo/:id", name: "summaryPractice", component: SummaryView, meta: { requiresAuth: true } },

  { path: "/profiles/:id", name: "profile", component: ProfileView },

  { path: "/report/question", name: "questionReport", component: ReportView },
  { path: "/report/user", name: "userReport", component: ReportView },


  { path: "/admin", name: "admin", component: AdminView, meta: { requiresAuth: true, requiresAdmin: true } },

  // Test connection with backend services
  { path: "/health", name: "health", component: HealthView, meta: { requiresAuth: true, requiresAdmin: true } },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
});

router.beforeEach(async (to) => {
  const userStore = useUserStore();

  if (to.meta.requiresAuth) {
    if (userStore.isAuthenticated == null) {
      await userStore.verifySession();
    }

    if (userStore.isAuthenticated == false) return router.push("/login");
  }

  if (to.meta.requiresAdmin && !userStore.user.is_admin) {
    console.warn("Not enough privileges.");
    return router.push("/");
  }
});

export default router;
