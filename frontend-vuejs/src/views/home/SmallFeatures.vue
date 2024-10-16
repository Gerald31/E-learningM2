<script setup>
import featuredItem01 from "@/assets/img/featured-item-01.png";
</script>
<template>
    <div data-scroll-reveal="enter bottom move 50px over 0.6s after 0.2s">
        <div class="group/features-small-item ease-in transition-all duration-300 cursor-pointer block bg-white p-8 text-center relative mb-8 rounded-3xl shadow-xl">
            <div class="icon group-hover/features-small-item:bg-pink-500 ease-in transition-all duration-300 w-24 h-24 m-auto relative mb-8 bg-purple-600 flex items-center justify-center justify-items-center rounded-full leading-10">
                <i class="text-lg text-white">
                    <img
                        :src="featuredItem01"
                        alt=""
                    >
                </i>
            </div>
            <h5 class="features-title font-medium text-lg text-gray-900 mb-4 relative z-2 tracking-widest">Liberté de Tarif</h5>
            <p class="font-normal text-xs text-gray-600 leading-6 relative z-2 tracking-wider">Complétez vos revenus en fixant vous-même <br/> vos tarifs</p>
        </div>
    </div>
    <div data-scroll-reveal="enter bottom move 50px over 0.6s after 0.4s">
        <div class="group/features-small-item ease-in transition-all duration-300 cursor-pointer block bg-white p-8 text-center relative mb-8 rounded-3xl shadow-xl">
            <div class="group-hover/features-small-item:bg-purple-600 ease-in transition-all duration-300 w-24 h-24 m-auto relative mb-8 bg-pink-500 flex items-center justify-center justify-items-center rounded-full leading-10">
                <i class="text-lg text-white"><img :src="featuredItem01" alt=""></i>
            </div>
            <h5 class="font-medium text-lg text-gray-900 mb-4 relative z-2 tracking-widest">Sécuriser</h5>
            <p class="font-normal text-xs text-gray-600 leading-6 relative z-2 tracking-wider">Bénéficiez d'une plate-forme sécurisée et  <br/>en conformité avec la législation française</p>
        </div>
    </div>
    <div data-scroll-reveal="enter bottom move 50px over 0.6s after 0.6s">
        <div class="group/features-small-item ease-in transition-all duration-300 cursor-pointer block bg-white p-8 text-center relative mb-8 rounded-3xl shadow-xl">
            <div class="group-hover/features-small-item:bg-pink-500 ease-in transition-all duration-300 w-24 h-24 m-auto relative mb-8 bg-purple-600 flex items-center justify-center justify-items-center rounded-full leading-10">
                <i class="text-lg text-white"><img :src="featuredItem01" alt=""></i>
            </div>
            <h5 class="font-medium text-lg text-gray-900 mb-4 relative z-2 tracking-widest">Liberté de Lieu</h5>
            <p class="font-normal text-xs text-gray-600 leading-6 relative z-2 tracking-wider">Travaillez d'où vous voulez et <br/>quand vous voulez</p>
        </div>
    </div>
</template>

<style scoped>

</style>
