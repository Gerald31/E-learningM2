<script setup>
import { computed } from 'vue';
import { useHomeStore } from '@/stores';

const homeStore = useHomeStore();

const statistics = computed(() => homeStore.statistics);

</script>
<template>
    <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-2">
        <div>
            <div class="group/count-item count-item decoration-bottom w-full float-left relative h-80 md:py-5 after:left-7/10 after:w-7/10 after:top-40 after:absolute after:h-8 after:bg-center after:bg-no-repeat after:bg-circle-dec after:ease-in after:transition-all after:duration-300 max-991:h-auto max-991:py-5 max-991:before:hidden max-991:after:hidden">
                <strong class="group-hover/count-item:mt-28 transition duration-300 ease-in-out block text-center text-4xl tracking-wide mb-2 text-white mt-32 font-semibold max-991:group-hover/count-item:mt-0 max-991:mt-0">{{ statistics.tutors }}</strong>
                <span class="block text-center text-white font-medium text-2xl tracking-wide">Professeurs inscrits</span>
            </div>
        </div>
        <div>
            <div class="group/count-item count-item decoration-top w-full float-left relative h-80 md:py-5 after:left-7/10 after:w-7/10 after:top-32 after:h-8 after:absolute after:bg-center after:bg-no-repeat after:bg-circle-dec after:ease-in after:transition-all after:duration-300 max-991:h-auto max-991:py-5 max-991:before:hidden max-991:after:hidden">
                <strong class="group-hover/count-item:mt-28 transition duration-300 ease-in-out block text-center text-4xl tracking-wide mb-2 text-white mt-32 font-semibold max-991:group-hover/count-item:mt-0 max-991:mt-0">{{ statistics.students }}</strong>
                <span class="block text-center text-white font-medium text-2xl tracking-wide">Eleves inscrites</span>
            </div>
        </div>
        <div>
            <div class="group/count-item count-item decoration-bottom w-full float-left relative h-80 md:py-5 after:left-7/10 after:w-7/10 after:top-40 after:absolute after:h-8 after:bg-center after:bg-no-repeat after:bg-circle-dec after:ease-in after:transition-all after:duration-300 max-991:h-auto max-991:py-5 max-991:before:hidden max-991:after:hidden">
                <strong class="group-hover/count-item:mt-28 transition duration-300 ease-in-out block text-center text-4xl tracking-wide mb-2 text-white mt-32 font-semibold max-991:group-hover/count-item:mt-0 max-991:mt-0">{{ statistics.tutoratProgress }}</strong>
                <span class="block text-center text-white font-medium text-2xl tracking-wide">Cours en attentes</span>
            </div>
        </div>
        <div>
            <div class="group/count-item count-item w-full float-left relative h-80 md:py-5 max-991:h-auto max-991:py-5 max-991:before:hidden max-991:after:hidden">
                <strong class="group-hover/count-item:mt-28 transition duration-300 ease-in-out block text-center text-4xl tracking-wide mb-2 text-white mt-32 font-semibold max-991:group-hover/count-item:mt-0 max-991:mt-0">{{ statistics.tutoratDone }}</strong>
                <span class="block text-center text-white font-medium text-2xl tracking-wide">Cours en réalisés</span>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
