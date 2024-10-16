<script setup>
import { ref } from "vue";
import {
  handleScroll,
  toggleDarkMode,
  scrolling,
  isDark,
} from "@/utility/theme";
import { MoonIcon, SunIcon } from "@heroicons/vue/24/outline";
import Button from "@/components/Button.vue";
import Logo from "@/assets/logo.png";
const open = ref(false);

const toogleMenuMobile = () => {
    open.value = !open.value;
}
</script>
<template>
    <nav :class="{'h-20': !open}" class=" rounded-4xl shadow md:shadow-lg shadow-xl bg-white min-h-max max-991:overflow-hidden">
        <a href="#" class="ease-in transition-all duration-300 float-left ml-4 mt-4 w-16 flex flex-row justify-items-center items-center">
            <img class="ease-in transition-all duration-300" :src="Logo" alt="Learn At Home"/>
            <span class="max-991:hidden block font-normal">Learn@Home</span>
        </a>
        <ul :class="[{'max-991:block': open, 'max-991:hidden': !open}]" class="nav flex float-right mt-5 ml-0 mr-8 relative z-999 max-991:float-none max-991:w-full max-991:mt-20 max-991:ml-0">
            <li class="pl-5 pr-5 max-991:border-t max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <a href="#welcome" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">Acceuil</a>
            </li>
            <li class="pl-5 pr-5 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <a href="#features" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">À propos</a>
            </li>
            <!--li class="pl-5 pr-5 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <a href="#work-process" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">Work Process</a>
            </li>
            <li class="pl-5 pr-5 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <a href="#pricing-plans" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">Tarifs</a>
            </li-->
            <li class="pl-5 pr-5 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <a href="#blog" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">Blog</a>
            </li>
            <li class="pl-5 pr-0 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <router-link tag="li" :to="{name: 'Register'}" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">S'inscrire</router-link>
            </li>
            <li class="pl-5 pr-5 max-991:w-full max-991:bg-white max-991:border max-991:border-solid max-991:pl-0 max-991:pr-0 max-991:border-gray-200">
                <router-link tag="li" :to="{name: 'Login'}" class="block font-medium text-xs text-black h-10 leading-10 border-transparent hover:text-pink-500 tracking-widest active:text-pink-500 max-991:h-12 max-991:p-0 max-991:border-none max-991:bg-white max-991:text-gray-700 max-991:hover:bg-gray-200">Se connecter</router-link>
            </li>
        </ul>
        <a class='group/menu-trigger menu-trigger cursor-pointer hidden absolute w-8 h-10 z-99 -indent-96 top-6 right-10 max-991:block' :class="{'active': open}" @click="toogleMenuMobile">
            <span class="bg-gray-700 block absolute top-4 w-8 h-0.5 left-0 before:z-10 before:-top-2.5 before:origin-top-left-1/3-4 before:bg-gray-700 before:block before:absolute before:w-9/12 before:h-0.5 before:left-0 after:top-2.5 after:origin-top-left-1/3-0 after:bg-gray-700 after:block after:absolute after:w-9/12 after:h-0.5 after:left-0"></span>
        </a>
    </nav>
</template>

<style scoped>

</style>
