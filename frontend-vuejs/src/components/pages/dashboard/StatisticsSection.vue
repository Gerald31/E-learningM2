<template>
  <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
    <h2 class="sr-only">Quick statistics</h2>

    <!-- Users card -->
    <QuiclStatisticsCard
      title="Utilisateurs"
      :chartData="props.usersGroup"
      :result="props.usersTotal"
      :percentage="avgUsers"
      :status="statusAvg(avgUsers)"
      :actions="[{ title: 'View', to: '#' }]"
    >
      <template #icon="{ sizeClasses }">
        <UserGroupIcon aria-hidden="true" :class="sizeClasses" />
      </template>
    </QuiclStatisticsCard>

      <!-- Orders card -->
    <QuiclStatisticsCard
      title="Tutorats"
      :chartData="ordersData"
      result="34.4k"
      status="warning"
      percentage="0.60%"
      :actions="[{ title: 'View', to: '#' }]"
    >
      <template #icon="{ sizeClasses }">
          <ShoppingCartIcon aria-hidden="true" :class="sizeClasses" />
      </template>
    </QuiclStatisticsCard>

    <!-- Visits card -->
    <QuiclStatisticsCard
      title="Visiteur"
      :chartData="visitsData"
      result="-2.6k"
      status="danger"
      percentage="-2.10%"
      :actions="[{ title: 'View', to: '#' }]"
    >
      <template #icon="{ sizeClasses }">
        <EyeIcon aria-hidden="true" :class="sizeClasses" />
      </template>
    </QuiclStatisticsCard>

    <!-- Growth card -->
    <QuiclStatisticsCard
      title="Croissance"
      :chartData="growthData"
      result="15.6%"
      percentage="7.20%"
      :actions="[{ title: 'View', to: '#' }]"
    >
      <template #icon="{ sizeClasses }">
        <ChartPieIcon aria-hidden="true" :class="sizeClasses" />
      </template>
    </QuiclStatisticsCard>
  </section>
</template>

<script setup>
import { computed } from "vue";
import { useDashboardStore } from "@/stores";
import QuiclStatisticsCard from "@/components/QuiclStatisticsCard.vue";
import {
  UserGroupIcon,
  EyeIcon,
  ShoppingCartIcon,
  ChartPieIcon,
} from "@heroicons/vue/24/outline";

const dashboardStore = useDashboardStore();

const props = defineProps({
    usersTotal: {
        type: Number,
        default: 0,
    },
    usersGroup: {
        type: [Array, Object],
        default: [],
    },
})

const avgUsers = computed(() => dashboardStore.avgUser + ' %');

// success : avgUsers > 0, danger: avgUsers < 0, warning: avgUsers === 0
const statusAvg = (avgUsers) => {
    if (avgUsers > 0) return 'success';
    if (avgUsers < 0) return 'danger';
    if (avgUsers === 0) return 'warning';
}
const customersData = [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13];
const visitsData = [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13].reverse();
const ordersData = [34, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13];
const growthData = [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13];
</script>
