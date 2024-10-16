<script setup>
import { onClickOutside } from '@vueuse/core';
import { onMounted, onUnmounted, ref } from "vue";

import ScaleTransition from "@/components/transitions/ScaleTransition.vue";

const props = defineProps({
    show: Boolean,
    handleClickOutside: Function|String,
    preventClickOutside: Boolean,
    cordinates: { left: String, right: String, top: String, bottom: String },
    position: Array,
    closeDropdown: Function
});

// html element containing the dropdown.
const dropdown = ref();

// (event) close dropdown when typing esc button.
const handleCloseOnEscape = (event) => {
    if (['Escape', 'Esc'].includes(event.key)) {
        props.closeDropdown();
    }
};

onMounted(() => {
    // set the handleCloseOnEscape when mounting the component.
    document.addEventListener('keydown', handleCloseOnEscape);
    // when clicking outside
    onClickOutside(dropdown, (event) => props.handleClickOutside)
});

onUnmounted(() => {
    // remove handleCloseOnEscape when unmounting the component.
    document.removeEventListener('keydown', handleCloseOnEscape);
});

</script>

<template>
    <div>
        <div v-if="props.show" class="fixed left-0 top-0 z-[5] w-full h-full"> </div>

        <ScaleTransition>
            <div :class="props.position" :style="props.cordinates" v-show="props.show"
                v-click-outside="props.handleClickOutside" class="absolute z-[10] w-[200px] mt-2 rounded-sm bg-white dark:bg-gray-800 shadow-lg
                border border-gray-100 dark:border-gray-600 focus:outline-none" role="menu" aria-orientation="vertical"
                aria-labelledby="menu-button" tabindex="-1">
                <div role="none">
                    <slot></slot>
                </div>
            </div>
        </ScaleTransition>
    </div>
</template>
