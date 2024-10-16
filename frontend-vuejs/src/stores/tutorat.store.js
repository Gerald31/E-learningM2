import { defineStore } from "pinia";
import { fullPathPicture } from '@/utility/media';

const tutoratsStorageVersion = 1;
const tutoratsStorageItemKey = `tutorats_store_${tutoratsStorageVersion}`;

const defaultStoredTutorats = {
    tutorats: [],
    filters: {
        sortBy: 'date',
        orderType: 'desc',
    }
};
const serialiseTutoratStore = (state) => {
    sessionStorage.setItem(tutoratsStorageItemKey, JSON.stringify({
        tutorats: state.tutorats,
        filters: state.filters,
    }));
}

const deserialiseTutoratStore = () => {
    const storedTutorats = sessionStorage.getItem(tutoratsStorageItemKey);
    return storedTutorats ? JSON.parse(storedTutorats) : defaultStoredTutorats;
}

const tutoratsStore = deserialiseTutoratStore();

export const useTutoratStore = defineStore("tutorats", {
    state: () => ({
        tutorats: tutoratsStore.tutorats,
        filters: tutoratsStore.filters
    }),
    getters: {
        tutoratsAvailableTutor: (state) => {
            return state.tutorats.filter(tutorat => tutorat.tutorat_states?.length < 1).map(tutorat => {
                return {
                    ...tutorat,
                    fullPath: fullPathPicture(tutorat.documents)
                }
            });
        },
        tutoratsAffectedTutor: (state) => {
            return state.tutorats.filter(tutorat => tutorat.tutorat_states?.length >= 1).map(tutorat => {
                return {
                    ...tutorat,
                    fullPath: fullPathPicture(tutorat.documents)
                }
            });
        },
        tutoratsAvailableStudent: (state) => (userId) => {
            return state.tutorats.filter(tutorat => tutorat.tutorat_states?.length < 1 || !tutorat.tutorat_states?.some((tutoratsState) => tutoratsState?.users_id === userId)).map(tutorat => {
                return {
                    ...tutorat,
                    fullPath: fullPathPicture(tutorat.documents)
                }
            });
        },
        tutoratsAffectedStudent: (state) => (userId) =>  {
            return state.tutorats.filter(tutorat => tutorat.tutorat_states?.length >= 1 && tutorat.tutorat_states?.some((tutoratsState) => tutoratsState?.users_id === userId)).map(tutorat => {
                return {
                    ...tutorat,
                    fullPath: fullPathPicture(tutorat.documents)
                }
            });
        },
    },
    actions: {
        setTutorats (payload) {
            this.tutorats = payload;
            serialiseTutoratStore(this);
        },
        addTutorat (payload) {
            this.tutorats.push(payload);
            serialiseTutoratStore(this);
        },
        setSortBy (value) {
            this.filters.sortBy = value;
            serialiseTutoratStore(this);
        },
        setOrderType (value) {
            this.filters.orderType = value;
            serialiseTutoratStore(this);
        }
    }
});
