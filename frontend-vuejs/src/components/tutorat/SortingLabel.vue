<script setup>
import { ChevronUpIcon, ChevronDownIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["update-sorting"]);

 const props = defineProps({
    activeSortBy: {
        type: String,
        required: true,
    },
    sortBy: {
        type: String,
        required: true,
    },
    orderType: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex cursor-pointer items-center"
         @click="$emit('update-sorting',
                     { sortBy: sortBy,
                       orderType: activeSortBy === sortBy && orderType === 'desc' ? 'asc' : 'desc'
                     })">
        <p><slot name="column-name" /></p>
        <div class="flex flex-col ml-1 text-gray-200">
            <ChevronUpIcon
                class="flex-shrink-0  w-2.5 h-2.5 hover:cursor-pointer hover:text-slate-800"
                :class="{'active': orderType === 'asc' && activeSortBy === sortBy}"
                aria-hidden="true"
                @click.stop="$emit('update-sorting', { sortBy: sortBy, orderType: 'asc' })" />
            <ChevronDownIcon
                class="flex-shrink-0 w-2.5 h-2.5 -mt-1 hover:cursor-pointer hover:text-slate-800"
                aria-hidden="true"
                :class="{'active': orderType === 'desc' && activeSortBy === sortBy}"
                @click.stop="$emit('update-sorting', { sortBy: sortBy, orderType: 'desc' })" />
        </div>
    </div>
</template>

<style scoped>
</style>

