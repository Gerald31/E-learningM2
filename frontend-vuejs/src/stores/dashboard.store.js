import { defineStore } from "pinia";

const dashboardStorageVersion = 1;
const dashboardStorageItemKey = `dashboard_store_${dashboardStorageVersion}`;

const defaultStoredDashboard = {
    latestUsers: [],
    usersStatistics: [],
    latestTransactions: [],
    usersTotal: 0,
};

const serialiseDashboardStore = (state) => {
    sessionStorage.setItem(dashboardStorageItemKey, JSON.stringify({
        latestUsers: state.latestUsers,
        usersStatistics: state.usersStatistics,
        usersTotal: state.usersTotal,
        latestTransactions: state.latestTransactions,
    }));
}

const deserialiseDashboardStore = () => {
    const storedDashboard = sessionStorage.getItem(dashboardStorageItemKey);
    return storedDashboard ? JSON.parse(storedDashboard) : defaultStoredDashboard;
}

const dashboardStore = deserialiseDashboardStore();

export const useDashboardStore = defineStore("dashboard", {
    state: () => ({
        usersTotal: dashboardStore.usersTotal,
        latestUsers: dashboardStore.latestUsers,
        usersStatistics: dashboardStore.usersStatistics,
        latestTransactions: dashboardStore.latestTransactions,
    }),
    getters: {
        usersGroupByMonth: (state) => {
            return state.usersStatistics.map(statistic => statistic.data);
        },
        avgUser: (state) => {
            const total = state.usersGroupByMonth.reduce((acc, val) => acc + val, 0);
            const length = state.usersGroupByMonth.length;
            const avg = total/length;
            return  Math.round((avg/ total)*100).toFixed(2);
        }
    },
    actions: {
        setUsersTotal (payload) {
            this.usersTotal = payload;
            serialiseDashboardStore(this);
        },
        setUsersStatistics (payload) {
            this.usersStatistics = payload;
            serialiseDashboardStore(this);
        },
        setLastUsers (payload) {
            this.latestUsers = payload;
            serialiseDashboardStore(this);
        },
        setLastTransactions (payload) {
            this.latestTransactions = payload;
            serialiseDashboardStore(this);
        },
    },
});
