<script setup>
import { computed } from 'vue';
import blogItem01 from "@/assets/img/blog-item-01.png";
import blogItem02 from "@/assets/img/blog-item-02.png";
import blogItem03 from "@/assets/img/blog-item-03.png";
import moment from 'moment/moment';

const props = defineProps({
    mags: {
        type: Object,
        required: true,
    },
});

const getMonthMMM = (date) => {
    return moment(Date.parse(date)).utc().format('MMM');
}

const getDay = (date) => {
    return moment(Date.parse(date)).utc().format('DD');
}

const valueMags = computed(() => props.mags);
</script>
<template>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div></div>
        <div class="row flex-row flex items-center flex-wrap mx-4 ">
            <div class="relative w-full px-4">
                <div class="center-heading text-center">
                    <h2 class="section-title font-medium text-3xl text-black dark:text-white mb-5 tracking-widest">Nos dernières actualités</h2>
                </div>
            </div>
        </div>
        <div></div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 md:grid-cols-2">
        <template v-for="mag in valueMags" :key="mag.mag_id">
            <div>
                <div class="blog-post-thumb text-center mb-8">
                    <div class="img overflow-hidden rounded-3xl relative">
                        <img class="block w-full h-52 align-middle" :src="mag.path" :alt="mag.title">
                    </div>
                    <div class="blog-content -mt-8 pt-12 gap-3 flex">
                        <div class="flex-none w-16 h-16">
                            <div class="flex-col border-2 border-pink-500 text-pink-500 py-1 px-0.5 font-medium uppercase">
                                <div>{{ getDay(mag.created_at) }}</div>
                                <div>{{ getMonthMMM(mag.created_at) }}</div>
                            </div>
                        </div>
                        <div>
                            <h3 class="mb-5">
                                <a href="#" class="font-medium text-lg text-pink-500 dark:text-white tracking-wide leading-6 hover:text-black dark:hover:text-pink-500" v-text="mag.title"></a>
                            </h3>
                            <div class="text">
                                <p class="whitespace-normal truncate h-24 max-h-full" v-html="mag.subTitle"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>

</style>
