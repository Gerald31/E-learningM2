<script setup>
import { computed, ref } from "vue";
import { useTutoratStore } from '@/stores';
import { BookOpenIcon, EyeIcon } from "@heroicons/vue/24/outline";
import Button from "@/components/Button.vue";
import SortingLabel from "@/components/tutorat/SortingLabel.vue";
import TutoratItem from "@/components/tutorat/TutoratItem.vue";

const tutoratStore = useTutoratStore();
const isModal = ref(false);
const isHoverable = ref(false);

const componentClass = computed(() => {
    const base = [
        'rounded-2xl',
        'flex-col',
        isModal ? "dark:bg-slate-900" : "dark:bg-slate-900/70",
    ];

    if (isHoverable) {
        base.push("hover:shadow-lg transition-shadow duration-500");
    }

    return base;
});

const sortBy = computed(() => tutoratStore.filters.sortBy);
const orderType = computed(() => tutoratStore.filters.orderType);

const updateSorting = (sorting) => {
    tutoratStore.setSortBy(sorting.sortBy);
    tutoratStore.setOrderType(sorting.orderType);
};

const loadMoreTutorats = () => {}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <BookOpenIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Tutorats
                </h1>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="overflow-hidden border rounded-lg p-5 mb-7 bg-white max-h-full">
                    <div class="flex flex-col h-full">
                        <div class="flex mr-2 h-10">
                            <div class="w-1/5 pl-2.5 flex justify-center">
                                <SortingLabel
                                    :sort-by="'date'"
                                    :active-sort-by="sortBy"
                                    :order-type="orderType"
                                    @update-sorting="updateSorting"
                                >
                                    <template v-slot:column-name>Date</template>
                                </SortingLabel>
                            </div>
                            <div class="w-1/5 text-center">
                                <span>Heure</span>
                            </div>
                            <div class="w-1/5 flex justify-center">
                                <SortingLabel
                                    :sort-by="'date'"
                                    :active-sort-by="sortBy"
                                    :order-type="orderType"
                                    @update-sorting="updateSorting"
                                >
                                    <template v-slot:column-name>Titre</template>
                                </SortingLabel>
                            </div>
                            <div class="w-1/5 flex justify-center">
                                <SortingLabel
                                    :sort-by="'date'"
                                    :active-sort-by="sortBy"
                                    :order-type="orderType"
                                    @update-sorting="updateSorting"
                                >
                                    <template v-slot:column-name>Classe</template>
                                </SortingLabel>
                            </div>
                            <div class="w-1/5 flex justify-center">
                                <SortingLabel
                                    :sort-by="'date'"
                                    :active-sort-by="sortBy"
                                    :order-type="orderType"
                                    @update-sorting="updateSorting"
                                >
                                    <template v-slot:column-name>Matiere</template>
                                </SortingLabel>
                            </div>
                            <div class="w-1/5 flex justify-center"></div>
                        </div>
                        <div class="tutorat__list__body h-full overflow-y-auto overflow-x-hidden" v-scroll-bottom="loadMoreTutorats">
                            <template v-for="n in 20">
                                <TutoratItem
                                    :tutorat="n"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
body {
    height: 100%;
    overflow-y: hidden;
}
.tutorat__list__body::-webkit-scrollbar {
    position: absolute;
    width: 5px;
    background-color: #F5F5F5;
}
.tutorat__list__body::-webkit-scrollbar-track {
    background-color: #F5F5F5;
    background-clip: padding-box;
}
.tutorat__list__body::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-clip: padding-box;
    border-radius: 20px;
    width: 7px;
    background-color: #A8A8A7;
}
</style>
