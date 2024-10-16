<template>
  <nav
    aria-label="secondary"
    :class="[
      'sticky top-0 z-10 px-6 py-4 bg-white flex items-center justify-between transition-transform duration-500 dark:bg-dark-eval-1',
      {
        '-translate-y-full': scrolling.down,
        'translate-y-0': scrolling.up,
      },
    ]"
  >
    <div class="flex items-center gap-2">

    </div>

    <div class="flex items-center gap-2">
      <Button
        iconOnly
        variant="secondary"
        v-slot="{ iconSizeClasses }"
        class="hidden md:inline-flex"
        srText="Toggle dark mode"
      >
        <CalendarIcon
          aria-hidden="true"
          :class="iconSizeClasses"
        />
        <span class="px-2">{{ timestamp }}</span>
      </Button>

      <!-- Dropdwon -->
      <Dropdown align="right" width="48">
        <template #trigger>
          <button
            class="flex text-sm transition border-2 border-transparent focus:outline-none focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark-eval-1"
          >
            <img
              class="object-cover w-8 h-8 rounded-full"
              :src="user.avatar??avatar"
              alt="User Name"
            />
          </button>
        </template>
        <template #content>
          <Button
              hidden
            iconOnly
            variant="secondary"
            v-slot="{ iconSizeClasses }"
            srText="Mon compte"
            class="w-full"
            href="my-profile"
          >
            <UserIcon aria-hidden="true" :class="iconSizeClasses" />
            <span class="px-2">Mon compte</span>
          </Button>
          <Button
              hidden
            iconOnly
            variant="secondary"
            v-slot="{ iconSizeClasses }"
            srText="Paramètres"
            class="w-full"
            href="settings"
          >
            <CogIcon aria-hidden="true" :class="iconSizeClasses" />
            <span class="px-2">Paramètres</span>
          </Button>
          <Button
            iconOnly
            variant="secondary"
            v-slot="{ iconSizeClasses }"
            srText="Déconnexion"
            class="w-full"
            @click="logout"
          >
            <ArrowRightOnRectangleIcon aria-hidden="true" :class="iconSizeClasses" />
            <span class="px-2">Déconnexion</span>
          </Button>
        </template>
      </Dropdown>
    </div>
  </nav>

  <!-- Mobile bottom bar -->
  <div
    :class="[
      'fixed inset-x-0 bottom-0 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white md:hidden dark:bg-dark-eval-1',
      {
        'translate-y-full': scrolling.down,
        'translate-y-0': scrolling.up,
      },
    ]"
  >
    <Button
      iconOnly
      variant="secondary"
      v-slot="{ iconSizeClasses }"
      srText="Search"
    >
      <MagnifyingGlassIcon aria-hidden="true" :class="iconSizeClasses" />
    </Button>

    <router-link :to="{ name: routeDashBoardActive }">
      <img class="ease-in transition-all duration-300 w-10 h-10 h-auto" :src="Logo" alt="Learn At Home"/>
      <span class="sr-only">Learn@Home</span>
    </router-link>

    <Button
      iconOnly
      variant="secondary"
      @click="sidebarState.isOpen = !sidebarState.isOpen"
      v-slot="{ iconSizeClasses }"
      class="md:hidden"
      srText="Search"
    >
      <Bars3Icon
        v-show="!sidebarState.isOpen"
        aria-hidden="true"
        :class="iconSizeClasses"
      />
      <XMarkIcon
        v-show="sidebarState.isOpen"
        aria-hidden="true"
        :class="iconSizeClasses"
      />
    </Button>
  </div>
</template>

<script setup>
import { computed, inject, onMounted, onUnmounted, ref } from "vue";
import { useFullscreen } from "@vueuse/core";
import {

  MagnifyingGlassIcon,
  Bars3Icon,
  XMarkIcon,
  ArrowsPointingOutIcon,
  ArrowRightOnRectangleIcon,
  CogIcon,
  UserIcon,
  CalendarIcon
} from "@heroicons/vue/24/outline";
import {
  handleScroll,
  isDark,
  scrolling,
  toggleDarkMode,
  sidebarState,
} from "@/utility/theme";
import Button from "@/components/Button.vue";
import Logo from "@/assets/logo.png";
import Dropdown from "@/components/Dropdown.vue";
import { ArrowsInnerIcon } from "@/components/icons/outline";
import userAvatar from "@/assets/images/avatar.jpg";
import avatar from "@/assets/avatar.webp";
import { useAuthStore } from '@/stores';
import router from '@/router';
import { errorToast } from '@/toast';
import { redirectRouteName } from "@/utility/roles";

const { isFullscreen, toggle: toggleFullScreen } = useFullscreen();
const api = inject("$api");


const authStore = useAuthStore();

const routeDashBoardActive = ref('');
const timestamp = ref(null);

onMounted(() => {
  document.addEventListener("scroll", handleScroll);
  getRouteDashBoard();
  setInterval(getNow, 1000);
});

const user = computed(() => authStore.user);

const getRouteDashBoard = async () => {
    routeDashBoardActive.value = redirectRouteName(user.roles);
}

onUnmounted(() => {
  document.removeEventListener("scroll", handleScroll);
});

const logout = async () => {
    await authStore.deleteSession();
    await router.push({ name: 'Login' });
};

const getNow = () => {
    const today = new Date();
    const date = formatDate(today);
    const time = formatTime(today);
    timestamp.value = date +' '+ time;
}

const padTo2Digits = (num) => {
    return num.toString().padStart(2, '0');
}

const formatDate = (date) => {
    return [
        padTo2Digits(date.getDate()),
        padTo2Digits(date.getMonth() + 1),
        date.getFullYear(),
    ].join('/');
}

const formatTime = (date) => {
    return [
        padTo2Digits(date.getHours()),
        padTo2Digits(date.getMinutes()),
        padTo2Digits(date.getSeconds()),
    ].join(':');
}

</script>
