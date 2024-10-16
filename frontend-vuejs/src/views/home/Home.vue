<script setup>
import { computed, inject, onMounted } from "vue";
import Navbar from "@/views/home/NavBar.vue";
import Welcome from "@/views/home/Welcome.vue";
import SmallFeatures from "@/views/home/SmallFeatures.vue";
import BigFeatures from "@/views/home/BigFeatures.vue";
import BigFeaturesItem from "@/views/home/BigFeaturesItem.vue";
import WorkProcess from "@/views/home/WorkProcess.vue";
import PrincingPlan from "@/views/home/PrincingPlan.vue";
import Blog from "@/views/home/Blog.vue";
import Counter from "@/views/home/Counter.vue";
import { errorToast } from '@/toast';
import { useHomeStore, useMagStore } from '@/stores';

const api = inject("$api");

const magStore = useMagStore();
const homeStore = useHomeStore();

const getAllMag = () => {
    api.magApi.getAllMags().then((mags) => {
        magStore.setMags(mags);
    }).catch((error) => {
        errorToast({
            text: error.data.errors.message,
        });
    });
};

const fetchHomeStats = () => {
    api.homeApi.fetchHomeStats().then(({statistics}) => {
        homeStore.setStatistics(statistics);
    }).catch((error) => {
        errorToast({
            text: error.data.errors.message,
        });
    });
};

const mags = computed(() => magStore.getMagsIsPublish);

onMounted(async () => {
    await getAllMag();
    await fetchHomeStats()
});
</script>
<template>
    <div class="header-area ease-in transition-all duration-300 z-100 header-sticky fixed left-0 right-0 top-6 h-24 max-991:px-4 max-991:h-20 max-991:text-center max-991:shadow-none">
      <div class="container mx-auto lg:max-w-6xl">
          <div class="flex flex-wrap -mr-4 -ml-4">
              <div class="relative w-full pr-4 pl-4">
                  <Navbar />
              </div>
          </div>
      </div>
  </div>

    <div class="welcome-area relative flex overflow-hidden items-center justify-center h-screen bg-no-repeat bg-center bg-cover bg-welcome-area max-767:mb-24" id="welcome">
        <div class="header-text absolute text-center w-full top-1/2 -translate-y-1/2 max-767:top-1/2 max-767:text-center max-767:-translate-y-1/2 max-820:top-2/3 max-820:-translate-y-2/3 max-991:top-2/3 max-991:text-center max-991:-translate-y-2/3" >
            <div class="container mx-auto lg:max-w-6xl">
                <div class="flex flex-wrap -mr-4 -ml-4">
                    <Welcome />
                </div>
            </div>
        </div>
    </div>

    <section class="section z-9 pb-0 pt-8 -mt-56 max-991:pb-0 max-991:pt-0 max-991:mt-0">
        <div class="container mx-auto lg:max-w-6xl">
            <div class="flex flex-wrap -mr-4 -ml-4">
                <div class="w-full">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <SmallFeatures />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-20 pb-0 pb-0" id="features">
        <div class="container mx-auto lg:max-w-6xl">
            <BigFeatures />
        </div>
    </section>

    <section class="section pb-28">
        <div class="container mx-auto lg:max-w-6xl">
            <BigFeaturesItem />
        </div>
    </section>

    <!--section class="min-h-max py-24 relative overflow-hidden before:absolute before:bg-no-repeat before:bg-cover before:bg-center before:bg-work-process before:z-2 before:-top-1/5 before:-left-1/5 before:w-5/7 before:h-5/7 before:opacity-95" id="work-process">
        <div class="mini-content relative z-10">
            <div class="container mx-auto lg:max-w-6xl">
                <WorkProcess />
            </div>
        </div>
    </section-->

    <!--section class="section bg-indigo-50 pt-24 pb-20" id="pricing-plans">
        <div class="container mx-auto lg:max-w-6xl">
            <PrincingPlan />
        </div>
    </section-->

    <section class="counter overflow-hidden relative before:absolute before:bg-no-repeat before:bg-cover before:bg-center before:bg-fun-facts before:z-2 before:-top-1/5 before:-left-1/5 before:w-5/7 before:h-5/7 before:opacity-90">
        <div class="content relative w-full z-10 md:relative md:top-0">
            <div class="container mx-auto lg:max-w-6xl">
                <Counter />
            </div>
        </div>
    </section>

    <section class="section  pt-24 pb-20" id="blog">
        <div class="container mx-auto lg:max-w-6xl">
            <Blog :mags="mags"/>
        </div>
    </section>

</template>
<style scoped>

</style>
