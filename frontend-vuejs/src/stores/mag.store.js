import { defineStore } from "pinia";
import { fullPathPicture } from '@/utility/media';

const magsStorageVersion = 1;
const magsStorageItemKey = `mags_store_${magsStorageVersion}`;

const defaultStoredMag = {
    mags: [],
};
const serialiseMagStore = (state) => {
    sessionStorage.setItem(magsStorageItemKey, JSON.stringify({
        mags: state.mags,
    }));
}

const deserialiseMagsStore = () => {
    const storedMags = sessionStorage.getItem(magsStorageItemKey);
    return storedMags ? JSON.parse(storedMags) : defaultStoredMag;
}

const magsStore = deserialiseMagsStore();

export const useMagStore = defineStore("mags", {
    state: () => ({
        mags: magsStore.mags,
    }),
    getters: {
        getMags: (state) => {
            return state.mags.map(mag => {
                return {
                    ...mag,
                    path: fullPathPicture(mag.picture)
                }
            });
        },
        getMagsIsPublish: (state) => {
            return state.mags.filter(mag => mag.enable).map(mag => {
                return {
                    ...mag,
                    path: fullPathPicture(mag.picture)
                }
            }).slice(0, 6);
        },
    },
    actions: {
        setMags (payload) {
            this.mags = payload;
            serialiseMagStore(this);
        },
        addMag (payload) {
            this.mags.push(payload)
            serialiseMagStore(this);
        },
        updatePublish(magId) {
            this.mags = this.mags.map(mag => {
                if (mag.mag_id === magId) {
                    return {
                        ...mag,
                        enable: !mag.enable
                    }
                }
                return mag;
            });
            serialiseMagStore(this);
        }
    }
});
