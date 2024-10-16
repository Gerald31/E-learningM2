<script setup>
import { computed, inject, onMounted } from "vue";
import PageWrapper from "@/components/PageWrapper.vue";
import StatisticsSection from '@/components/pages/dashboard/StatisticsSection.vue'
import SalesSection from '@/components/pages/dashboard/SalesSection.vue'
import LatestSection from "@/components/pages/dashboard/LatestSection.vue";
import TimeLineSection from "@/components/pages/dashboard/TimeLineSection.vue";
import NewTimeLine from "@/components/pages/dashboard/NewTimeLine.vue";
import { useDashboardStore } from "@/stores";

const api = inject('$api');

const dashboardStore = useDashboardStore();

onMounted(async() => {
    await fetchStats();
})

const usersTotal = computed(() => dashboardStore.usersTotal);
const usersDroup = computed(() => dashboardStore.usersGroupByMonth);

const fetchStats = () => {
    api.dashboardApi.fetchStats().then(({ total, usersGroup, lastUsers, lastestTransaction }) => {
        dashboardStore.setUsersTotal(total);
        dashboardStore.setUsersStatistics(usersGroup);
        dashboardStore.setLastUsers(lastUsers);
        dashboardStore.setLastTransactions(lastestTransaction);
    }).catch((error) => {
        console.log('error =>', error)
    })
}
</script>

<template>
  <PageWrapper>
    <template #header>
      <div
        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
      >
        <h2 class="text-xl font-semibold leading-tight">Dashboard</h2>
      </div>
    </template>

    <!-- Statistics section -->
    <StatisticsSection :users-group="usersDroup" :users-total="usersTotal" />

    <!-- Sales section -->
    <SalesSection />

    <!-- Latest users & transaction section -->
    <LatestSection />
  </PageWrapper>
</template>
