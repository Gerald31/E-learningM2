<template>
  <div
    class="flex flex-col items-center justify-center min-h-screen gap-4 bg-gray-100 dark:bg-dark-bg dark:text-gray-600"
  >
    <main class="flex items-center flex-1 w-full">
      <div
        class="w-full overflow-hidden bg-white shadow-md dark:bg-dark-eval-1"
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

<script setup>
import Logo from "@/components/Logo.vue";
import PageFooter from "@/components/PageFooter.vue";
import FadeTransition from '@/components/transitions/FadeTransition.vue';
</script>
