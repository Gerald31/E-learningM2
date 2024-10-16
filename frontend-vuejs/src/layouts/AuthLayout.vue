<script setup>
import { useRouter } from 'vue-router';
import Logo from "@/assets/logo.png";
import PageFooter from "@/components/PageFooter.vue";

import FadeTransition from '@/components/transitions/FadeTransition.vue';
const isCurrentRoute = (routeName) => {
    return useRouter().currentRoute.value.name === routeName;
};
</script>

<template>
  <div
    class="flex flex-col items-center justify-center min-h-screen gap-4 pt-6 bg-gray-100 dark:bg-dark-bg"
  >
    <div class="flex-shrink-0">
      <router-link :to="{ name: 'home' }">
          <img class="ease-in transition-all duration-300" :src="Logo" alt="Learn At Home"/>
        <span class="sr-only">Home</span>
      </router-link>
    </div>

    <main
        class="flex items-center flex-1 w-full "
        :class="{ 'sm:max-w-xl': !isCurrentRoute('Register'), 'sm:max-w-4xl': isCurrentRoute('Register')}"
    >
      <div
        class="w-full px-6 py-4 overflow-hidden bg-white shadow-md sm:rounded-lg dark:bg-dark-eval-1"
      >
          <router-view v-slot="{ Component, route }">
              <FadeTransition>
                  <div :key="route.name">
                      <component :is="Component" />
                  </div>
              </FadeTransition>
          </router-view>
      </div>
    </main>

    <PageFooter />
  </div>
</template>

