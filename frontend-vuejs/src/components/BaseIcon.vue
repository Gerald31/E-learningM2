<script setup>
import { computed } from "vue";
const emit = defineEmits(["click"]);

const props = defineProps({
    path: {
        type: String,
        required: true,
    },
    w: {
        type: String,
        default: "w-6",
    },
    h: {
        type: String,
        default: "h-6",
    },
    size: {
        type: [String, Number],
        default: null,
    },
});

const spanClass = computed(
    () => `inline-flex ${props.w} ${props.h}`
);

const iconSize = computed(() => props.size ?? 16);

const clickIcon = () => {
    emit("click");
};
</script>

<template>
  <span :class="spanClass" @click="clickIcon">
    <svg
        viewBox="0 0 24 24"
        :width="iconSize"
        :height="iconSize"
        class="inline-block"
    >
      <path fill="currentColor" :d="path" />
    </svg>
    <slot />
  </span>
</template>

