<template>
  <PerfrectScrollbar
    tagname="nav"
    aria-label="main"
    class="relative flex flex-col flex-1 max-h-full gap-4 px-3"
  >
    <SidebarLink
      title="Dashboard"
      :to="{ name: routeDashBoardActive }"
      :active="
        isCurrentRoute('dashboardAdmin') ||
        isCurrentRoute('dashboardTutor') ||
        isCurrentRoute('dashboardClient')
      "
    >
      <template #icon>
        <DashboardIcon class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
      </template>
    </SidebarLink>

    <SidebarCollapsible
        title="Tutorats"
        :active="
            isCurrentRoute('tutoratAdmin') ||
            isCurrentRoute('tutoratAdmin') ||
            isCurrentRoute('tutoratAdmin') ||
            isCurrentRoute('createTutoratTutor') ||
            isCurrentRoute('tutoratsDispoTutor') ||
            isCurrentRoute('tutoratsAffectedTutor') ||
            isCurrentRoute('agendaTutor') ||
            isCurrentRoute('tutoratsAvailableStudent') ||
            isCurrentRoute('tutoratsAffectedStudent')
        "
    >
      <template #icon>
          <BookOpenIcon class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
      </template>
        <div v-if="isAdminUser">
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratAdmin' }"
                :active="isCurrentRoute('tutoratAdmin')"
                title="En cours"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratAdmin' }"
                :active="isCurrentRoute('tutoratAdmin')"
                title="Prochaine"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratAdmin' }"
                :active="isCurrentRoute('tutoratAdmin')"
                title="Terminer"
            />
        </div>

        <div v-else-if="isTutorUser">
            <SidebarCollapsibleItem
                :to="{ name: 'createTutoratTutor' }"
                :active="isCurrentRoute('createTutoratTutor')"
                title="Création Tutorats"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratsDispoTutor' }"
                :active="isCurrentRoute('tutoratsDispoTutor')"
                title="Tutorats disponibles"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratsAffectedTutor' }"
                :active="isCurrentRoute('tutoratsAffectedTutor')"
                title="Tutorats affectés"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'agendaTutor' }"
                :active="isCurrentRoute('agendaTutor')"
                title="Agenda"
            />
        </div>
        <div v-else>
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratsAvailableStudent' }"
                :active="isCurrentRoute('tutoratsAvailableStudent')"
                title="Tutorat(s) disponibles"
            />
            <SidebarCollapsibleItem
                :to="{ name: 'tutoratsAffectedStudent' }"
                :active="isCurrentRoute('tutoratsAffectedStudent')"
                title="Tutorat(s) en cours"
            />
            <!--SidebarCollapsibleItem
                :to="{ name: 'documentsAvailableStudent' }"
                :active="isCurrentRoute('documentsAvailableStudent')"
                title="Documents disponibles"
            /-->
        </div>
    </SidebarCollapsible>

      <SidebarCollapsible
          v-if="isAdminUser"
          title="Utilisateurs"
          :active="isCurrentRoute('studentsAdmin') || isCurrentRoute('tutorsAdmin')"
      >
          <template #icon>
              <UsersIcon class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
          </template>
          <SidebarCollapsibleItem
              :to="{ name: 'studentsAdmin' }"
              :active="isCurrentRoute('studentsAdmin')"
              title="Etudiants"
          />
          <SidebarCollapsibleItem
              :to="{ name: 'tutorsAdmin' }"
              :active="isCurrentRoute('tutorsAdmin')"
              title="Tuteurs"
          />
      </SidebarCollapsible>

    <SidebarCollapsible
        v-if="isAdminUser"
        title="Parametrage"
        :active="isCurrentRoute('blogAdmin') || isCurrentRoute('classLevelAdmin')"
    >
      <template #icon>
          <CogIcon class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
      </template>
      <SidebarCollapsibleItem
          :to="{ name: 'blogAdmin' }"
          :active="isCurrentRoute('blogAdmin')"
          title="Blog"
      />
      <SidebarCollapsibleItem
          :to="{ name: 'classLevelAdmin' }"
          :active="isCurrentRoute('classLevelAdmin')"
          title="Classe"
      />
    </SidebarCollapsible>
  </PerfrectScrollbar>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from "vue-router";
import PerfrectScrollbar from "@/components/PerfectScrollbar.vue";
import SidebarLink from "@/components/sidebar/SidebarLink.vue";
import { DashboardIcon } from "@/components/icons/outline";
import { ShieldCheckIcon, BookOpenIcon, CogIcon, UsersIcon } from "@heroicons/vue/24/outline";
import SidebarCollapsible from "@/components/sidebar/SidebarCollapsible.vue";
import SidebarCollapsibleItem from "@/components/sidebar/SidebarCollapsibleItem.vue";
import { useAuthStore } from '@/stores';
import { redirectRouteName, isAdmin, isTutor, isStudent } from '@/utility/roles';

onMounted(() => {
    getRouteDashBoard();
});
const routeDashBoardActive = ref('');
const isAdminUser = ref(false);
const isTutorUser = ref(false);
const isStudentUser = ref(false);

const isCurrentRoute = (routeName) => {
  return useRouter().currentRoute.value.name === routeName;
};

const isCurrentPath = (path) => {
  return useRouter().currentRoute.value.path.startsWith(path);
};

const getRouteDashBoard = async () => {
    const { getCurrentUser } = useAuthStore();
    const currentUser = await getCurrentUser();
    routeDashBoardActive.value = redirectRouteName(currentUser.roles);
    isAdminUser.value = isAdmin(currentUser.roles);
    isTutorUser.value = isTutor(currentUser.roles);
    isStudentUser.value = isStudent(currentUser.roles);
}

</script>
