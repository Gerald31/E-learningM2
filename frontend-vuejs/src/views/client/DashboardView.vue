<script setup>
import { computed, inject, onMounted } from "vue";
import PageWrapper from "@/components/PageWrapper.vue";
import StatisticsSection from '@/components/pages/dashboard/StatisticsSection.vue'
import SalesSection from '@/components/pages/dashboard/SalesSection.vue'
import LatestSection from "@/components/pages/dashboard/LatestSection.vue";
import TimeLineSection from "@/components/pages/dashboard/TimeLineSection.vue";
import NewTimeLine from "@/components/pages/dashboard/NewTimeLine.vue";
import { useAuthStore, useClassLevelStore, useDashboardStore } from "@/stores";
import { ROLE_TUTOR } from "@/utility/roles";

const api = inject("$api");

const classLevelStore = useClassLevelStore();
const dashboardStore = useDashboardStore();
const authStore = useAuthStore();

const currentUser = computed(() => authStore.user);

const classLevels = computed(() => classLevelStore.getMyClassLevels);
const usersTotal = computed(() => dashboardStore.usersTotal);
const usersDroup = computed(() => dashboardStore.usersGroupByMonth);

onMounted(async () => {
    await getSkill(currentUser.value);
    await fetchStats();
});

const getSkill =  (currentUser) => {
    api.classLevelApi.getMyClassLevel(currentUser?.id, ROLE_TUTOR).then(({ classLevels, subjects }) => {
        classLevelStore.setMyClassLevels(classLevels);
        classLevelStore.setMySubjects(subjects);
    }).catch((errors) => {
        console.log('errors =>', errors)
    });
};

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

        <!-- TimeLine last tutorat -->
        <TimeLineSection />

        <!-- NewTimeLine last tutorat -->
        <NewTimeLine />
    </PageWrapper>
</template>
