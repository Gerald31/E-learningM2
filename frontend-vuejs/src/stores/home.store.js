import { defineStore } from "pinia";

const homeStorageVersion = 1;
const homeStorageItemKey = `home_store_${homeStorageVersion}`;

const defaultStoredHome = {
    statistics: {
        tutors: 0,
        students: 0,
        tutoratProgress: 0,
        tutoratDone: 0,
    }
};

const serialiseHomeStore = (state) => {
    sessionStorage.setItem(homeStorageItemKey, JSON.stringify({
        statistics: state.statistics,
    }));
}

const deserialiseHomeStore = () => {
    const storedHome = sessionStorage.getItem(homeStorageItemKey);
    return storedHome ? JSON.parse(storedHome) : defaultStoredHome;
}

const homeStore = deserialiseHomeStore();

export const useHomeStore = defineStore("home", {
    state: () => ({
        statistics: homeStore.statistics,
    }),
    getters: {},
    actions: {
        setStatistics (payload) {
            this.statistics = payload;
            serialiseHomeStore(this);
        },
    }
});
