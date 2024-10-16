import { useToast } from "vue-toastification";
import Toastification from "@/toast/Toastification";

export const toast = useToast();

export const primaryToast = ({ title = "Succès", text }) => {
  return toast.success({
    component: Toastification,
    props: {
      variant: "primary",
      title,
      text,
    },
  });
};

export const successToast = ({ title = "Succès", text }) => {
  return toast.success({
    component: Toastification,
    props: {
      variant: "success",
      title,
      text,
    },
  });
};

export const errorToast = ({ title = "Erreur", text }) => {
  return toast.error({
    component: Toastification,
    props: {
      variant: "error",
      title,
      text,
    },
  });
};

export const warnToast = ({ title = "Alert", text }) => {
  return toast.error({
    component: Toastification,
    props: {
      variant: "warning",
      title,
      text,
    },
  });
};

export const infoToast = ({ title = "Information", text }) => {
  return toast.error({
    component: Toastification,
    props: {
      variant: "info",
      title,
      text,
    },
  });
};
