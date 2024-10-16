import { createRouter, createWebHistory } from "vue-router";
import requireAuth from "@/router/middleware/requireAuth";
import { ROLE_ADMIN, ROLE_STUDENT, ROLE_TUTOR } from '@/utility/roles';
import {useAuthStore} from '@/stores';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: () => import("@/layouts/HomeLayout.vue"),
      children: [
        {
          path: "/",
          name: "home",
          meta: {
            title: "Acceuil",
          },
          component: () => import("@/views/home/Home.vue"),
        },
        {
          path: "/about",
          name: "about",
          meta: {
            title: "A propos",
          },
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import("@/views/home/AboutView.vue"),
        },
      ]
    },
    {
      path: "/student",
      meta: {
        title: "Dashboard",
        middleware: [requireAuth],
        roles: [ROLE_STUDENT],
      },
      component: () => import("@/layouts/ClientLayout.vue"),
      children: [
        {
          path: "/student/dashboard",
          name: "dashboardClient",
          meta: {
            title: "Dashboard",
            middleware: [requireAuth],
            roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/DashboardView.vue"),
        },
        {
          path: "/student/tutorat/available",
          name: "tutoratsAvailableStudent",
          meta: {
              title: "Tutorats disponibles",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/TutoratsAvailable.vue"),
        },
        {
          path: "/student/documents/available",
          name: "documentsAvailableStudent",
          meta: {
              title: "Documents disponibles",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/DocumentsAvailable.vue"),
        },
        {
          path: "/student/tutorat/affected",
          name: "tutoratsAffectedStudent",
          meta: {
              title: "Tutorats affectés",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/TutoratsAffected.vue"),
        },
        {
          path: "/student/tutorat/:tutorat/detail",
          name: "tutoratsDetail",
          meta: {
              title: "Detail de Tutorat",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/DetailsTutorat.vue"),
          props: true,
        },
        {
          path: "/student/tutorat/:tutorat/payment",
          name: "tutoratsPayment",
          meta: {
              title: "Paiement de Tutorat",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/PaymentView.vue"),
          props: true,
        },
        {
          path: "/student/tutorat/:tutorat/payment/success",
          name: "successPayment",
          meta: {
              title: "Paiement de Tutorat",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/PaymentSuccess.vue"),
          props: true,
        },
        {
          path: "/student/messenger/:tutorat",
          name: "messengerStudent",
          meta: {
              title: "Messenger",
              middleware: [requireAuth],
              roles: [ROLE_STUDENT],
          },
          component: () => import("@/views/client/MessengerView.vue"),
          props: true,
        },
      ],
    },
    {
      path: "/tutor",
      meta: {
        title: "Dashboard",
        middleware: [requireAuth],
        roles: [ROLE_TUTOR],
      },
      component: () => import("@/layouts/TutorLayout.vue"),
      children: [
        {
          path: "/tutor/dashboard",
          name: "dashboardTutor",
          meta: {
            title: "Dashboard",
            middleware: [requireAuth],
            roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/TutorDashboardView.vue"),
        },
        {
          path: "/tutor/tutorat/create",
          name: "createTutoratTutor",
          meta: {
              title: "Création de Tutorats",
              middleware: [requireAuth],
              roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/CreateTutoratsView.vue"),
        },
        {
          path: "/tutor/tutorat/available",
          name: "tutoratsDispoTutor",
          meta: {
              title: "Tutorats disponibles",
              middleware: [requireAuth],
              roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/TutoratsAvailable.vue"),
        },
        {
          path: "/tutor/tutorat/affected",
          name: "tutoratsAffectedTutor",
          meta: {
              title: "Tutorats affectés",
              middleware: [requireAuth],
              roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/TutoratsAffected.vue"),
        },
        {
          path: "/tutor/messenger/:tutorat",
          name: "messengerTutor",
          meta: {
              title: "Messenger",
              middleware: [requireAuth],
              roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/MessengerView.vue"),
          props: true,
        },
        {
          path: "/tutor/agenda",
          name: "agendaTutor",
          meta: {
            title: "Agenda",
            middleware: [requireAuth],
            roles: [ROLE_TUTOR],
          },
          component: () => import("@/views/tutor/CalendarView.vue"),
        },
      ],
    },
    {
      path: "/admin",
      meta: {
        title: "Dashboard",
        middleware: [requireAuth],
        roles: [ROLE_ADMIN],
      },
      component: () => import("@/layouts/AdminLayout.vue"),
      children: [
        {
          path: "/admin/dashboard",
          name: "dashboardAdmin",
          meta: {
            title: "Dashboard",
            middleware: [requireAuth],
            roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/Dashboard.vue"),
        },
        {
          path: "/admin/tutorats",
          name: "tutoratAdmin",
          meta: {
              title: "Tutorat",
              middleware: [requireAuth],
              roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/Tutorats.vue"),
        },
        {
          path: "/admin/tutors",
          name: "tutorsAdmin",
          meta: {
              title: "Tuteurs",
              middleware: [requireAuth],
              roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/TutorView.vue"),
        },
        {
          path: "/admin/students",
          name: "studentsAdmin",
          meta: {
              title: "Etudiant",
              middleware: [requireAuth],
              roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/StudentsView.vue"),
        },
        {
          path: "/admin/blog",
          name: "blogAdmin",
          meta: {
              title: "Blog",
              middleware: [requireAuth],
              roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/BlogView.vue"),
        },
        {
          path: "/admin/class-level",
          name: "classLevelAdmin",
          meta: {
              title: "Classe",
              middleware: [requireAuth],
              roles: [ROLE_ADMIN],
          },
          component: () => import("@/views/admin/ClassLevelView.vue"),
        },
      ],
    },
    {
      path: "/auth",
      name: "Auth",
      component: () => import("@/layouts/AuthLayout.vue"),
      children: [
        {
          path: "/auth/login",
          name: "Login",
          meta: {
            title: "Authentification",
          },
          component: () => import("@/views/auth/Login.vue"),
        },
        {
          path: "/auth/register",
          name: "Register",
          meta: {
            title: "Inscription",
          },
          component: () => import("@/views/auth/Register.vue"),
        },
        {
          path: "/auth/forgot-password",
          name: "ForgotPassword",
          meta: {
            title: "Mot de passe oublié",
          },
          component: () => import("@/views/auth/ForgotPassword.vue"),
        },
        {
          path: "/auth/reset-password",
          name: "ResetPassword",
          meta: {
            title: "Initialiser mot de passe",
          },
          component: () => import("@/views/auth/ResetPassword.vue"),
        },
        {
          path: "/auth/confirm-password",
          name: "ConfirmPassword",
          meta: {
            title: "Confirmer mot de passe",
          },
          component: () => import("@/views/auth/ConfirmPassword.vue"),
        },
        {
          path: "/auth/verify-email/:token",
          name: "VerifyEmail",
          meta: {
            title: "Verifier email",
          },
          component: () => import("@/views/auth/VerifyEmail.vue"),
          props: true,
        },
      ],
    },
    {
      path: "/403",
      name: "UnAuthorized",
      meta: {
        title: "Accès refusé",
      },
      component: () => import("@/views/pages/Error403.vue"),
    },
    {
      path: '/404',
      name: 'Notfound',
      meta: {
        title: "Page non trouvée",
      },
      component: () => import("@/views/pages/Error404.vue"),
    },
    {
      path: "/:pathMatch(.*)*",
      redirect: "/404",
    }
  ],
});

router.beforeEach(async (to, from, next) => {
    const hasPublicAccess = !to.meta.middleware || to.meta.middleware.length === 0;
    if (hasPublicAccess) {
        return next();
    }

    const authStore = useAuthStore();
    const currentUser = authStore.user;
    if (currentUser) {
        if (to.matched.some(record => record.meta.roles.includes(currentUser.roles))) {
            return next();
        }
        return next({
            name: "UnAuthorized",
        });
    }
    return next({
        name: "Login",
    });
});

/* Default title tag */
const defaultDocumentTitle = "Learn@Home";

/* Set document title from route meta */
router.afterEach((to) => {
  document.title = to.meta?.title
    ? `${to.meta.title} — ${defaultDocumentTitle}`
    : defaultDocumentTitle;
});

export default router;
