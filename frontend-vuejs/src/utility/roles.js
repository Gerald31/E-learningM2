export const ROLE_ADMIN = "ROLE_ADMIN";
export const ROLE_TUTOR = "ROLE_TUTOR";
export const ROLE_STUDENT = "ROLE_STUDENT";

export const isAdmin = (role) => {
  return role === ROLE_ADMIN;
};

export const isTutor = (role) => {
  return role === ROLE_TUTOR;
};

export const isStudent = (role) => {
  return role === ROLE_STUDENT;
};

export const rolesArray = [
  {
    id: ROLE_TUTOR,
    label: 'Professeur'
  },
  {
    id: ROLE_STUDENT,
    label: 'Etudiant'
  }
]

export const redirectRouteName = (role) => {
  switch (role) {
    case ROLE_ADMIN:
      return "dashboardAdmin";
    case ROLE_TUTOR:
      return "dashboardTutor";
    case ROLE_STUDENT:
      return "dashboardClient";
    default:
      return "home";
  }
};
