<script setup>
import { ChevronRightIcon } from "@heroicons/vue/24/outline";

</script>

<template>
    <div class="py-2 mr-2.5 border-l-transparent border-t border-t-slate-500 border-l-4 text-gray-700 odd:bg-gray-200">
        <div class="flex items-center cursor-pointer">
            <div class="flex items-center w-full">
                <div class="pl-2.5 w-1/5">
                    <span>22 Sept 2022</span>
                </div>
                <div class="w-1/5 text-center">
                    <span>10:00 - 12:15</span>
                </div>
                <div class="w-1/5 text-center">
                    <span>Titre</span>
                </div>
                <div class="text-center w-1/5">
                    <span>5éme</span>
                </div>
                <div class="text-center w-1/5">
                    <span>Mathématique</span>
                </div>
                <div class="flex justify-center w-1/5">
                    <div class="odd:bg-white even:bg-gray-200 w-8 h-8 flex justify-center items-center rounded-full">
                        <ChevronRightIcon
                            class="flex-shrink-0 w-5 h-5 hover:cursor-pointer hover:text-slate-800"
                            aria-hidden="true" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
