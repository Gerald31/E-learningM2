<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: "text",
        validator(value) {
            return [
                "text",
                "money",
                "select",
                "editor",
            ].includes(value);
        },
    }
});

const { type } = props;

const baseClasses = [
    "relative text-gray-500 focus-within:text-gray-900 dark:focus-within:text-gray-400 items-center",
];
const classes = computed(() => [
    ...baseClasses,
    {
        "after:font-semibold after:content-['€'] after:absolute after:right-8": type === "money",
        "flex": type === "text" || type === "money",
    }
]);
</script>
<template>
  <div
    class=""
    :class="classes"
  >
    <div
      aria-hidden="true"
      class="absolute inset-y-0 flex items-center px-4 pointer-events-none"
    >
      <slot name="icon" />
    </div>
    <slot />
  </div>
</template>
