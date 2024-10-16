<template>
  <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <h2 class="sr-only">Latest users</h2>

    <!-- Recent cards -->
      <!-- Recent contacts -->
    <BaseCard title="Utilisateurs plus récents" :actions="[{ title: 'View', to: '#' }]">
        <div
          class="mt-4 flex items-center justify-between"
          v-for="user in latestUsers"
          :key="user.id"
        >
          <div class="flex items-center gap-2">
            <img
              class="w-10 h-10 rounded-md object-cover"
              :src="fullPathPicture(user.avatar)??avatar"
            />
            <div>
              <h5 class="text-xs text-gray-600 dark:text-gray-300">{{ getFullName(user) }}</h5>
              <p class="text-xs text-gray-400 dark:text-gray-500">
                  {{ user.email }}
              </p>
            </div>
          </div>
          <Button
            size="sm"
            iconOnly
            variant="secondary"
            v-slot="{ iconSizeClasses }"
          >
            <EllipsisVerticalIcon aria-hidden="true" :class="iconSizeClasses" />
            <span class="sr-only">Actions</span>
          </Button>
        </div>
      </BaseCard>

      <!-- Recent transactions -->
    <BaseCard
        title="Transactions plus récents"
        :actions="[{ title: 'View', to: '#' }]"
      >
        <template v-for="latestTransaction in latestTransactions">
            <div class="mt-4 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <PlusCircleIcon class="w-6 h-6 text-green-500" />
                <div>
                  <h5 class="text-xs text-gray-600 dark:text-gray-300">{{  getFullName(latestTransaction?.etudiant) }}</h5>
                  <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $filters.datefr(latestTransaction?.created_at, true) }}
                  </p>
                </div>
              </div>

             <span class="text-base font-medium text-green-500">+ {{ latestTransaction?.price }} €</span>
            </div>
        </template>
      </BaseCard>
  </section>
</template>

<script setup>
import { computed } from "vue";
import BaseCard from "@/components/BaseCard.vue";
import Button from "@/components/Button.vue";
import {
  EllipsisVerticalIcon,
  PlusCircleIcon,
  MinusCircleIcon,
} from "@heroicons/vue/24/outline";
import { EmptyCircleIcon } from "@/components/icons/outline";
import { useDashboardStore } from "@/stores";
import { fullPathPicture } from '@/utility/media';
import avatar from '@/assets/avatar.webp';
import { getFullName } from "@/utility/chat";

const dashboardStore = useDashboardStore();
const latestUsers = computed(() => dashboardStore.latestUsers);
const latestTransactions = computed(() => dashboardStore.latestTransactions);

</script>
