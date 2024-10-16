import { useAuthStore } from "@/stores";

export default async function requireAuth({ next }) {
  const { getCurrentUser } = useAuthStore();
  try {
      const currentUser = await getCurrentUser();
    if (!currentUser) {
      return next({
        name: "Login",
      });
    }
  } catch (error) {
    document.location.href = "/auth/login";
  }

  return next();
}
