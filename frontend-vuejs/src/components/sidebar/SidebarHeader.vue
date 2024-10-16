<template>
  <div class="flex items-center justify-between flex-shrink-0 px-3">
    <router-link
      :to="{ name: routeDashBoardActive }"
      class="inline-flex items-center gap-2"
    >
      <span class="sr-only">L@H</span>
        <img class="ease-in transition-all duration-300 w-28 h-auto" :src="Logo" alt="Learn At Home"/>
    </router-link>

    <Button
      iconOnly
      variant="secondary"
      v-slot="{ iconSizeClasses }"
      v-show="sidebarState.isOpen || sidebarState.isHovered"
      @click="sidebarState.isOpen = !sidebarState.isOpen"
      :srText="sidebarState.isOpen ? 'Close sidebar' : 'Open sidebar'"
    >
      <MenuFoldLineLeftIcon
        aria-hidden="true"
        v-show="sidebarState.isOpen"
        :class="['hidden lg:block', iconSizeClasses]"
      />

      <MenuFoldLineRightIcon
        aria-hidden="true"
        v-show="!sidebarState.isOpen"
        :class="['hidden lg:block', iconSizeClasses]"
      />

      <XMarkIcon aria-hidden="true" :class="['lg:hidden', iconSizeClasses]" />
    </Button>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { XMarkIcon } from "@heroicons/vue/24/outline";
import Logo from "@/assets/logo.png";
import Button from "@/components/Button.vue";
import {
  MenuFoldLineLeftIcon,
  MenuFoldLineRightIcon,
} from "@/components/icons/outline";
import { sidebarState } from "@/utility/theme";
import { useAuthStore } from '@/stores';
import { redirectRouteName } from '@/utility/roles';

const routeDashBoardActive = ref('');

onMounted(() => {
    getRouteDashBoard();
});
const getRouteDashBoard = async () => {
    const { getCurrentUser } = useAuthStore();
    const currentUser = await getCurrentUser();
    routeDashBoardActive.value = redirectRouteName(currentUser.roles);
}

</script>
