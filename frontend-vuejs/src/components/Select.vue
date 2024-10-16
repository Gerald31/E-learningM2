<template>
    <select
      :class="[
        'py-2 rounded-md',
      'dark:bg-dark-eval-1 dark:text-gray-300',
      'font-normal text-base h-10 rounded-lg p-2 text-gray-700 bg-gray-200',
      {
        'px-4': !withIcon,
        'pl-11 pr-4': withIcon,
      },
      {
        'border-gray-200 focus:border-gray-200': !error,
        'border-red-500 focus:border-red-500': error,
      },
    ]"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      ref="select"
      >
        <option v-for="option in options" :value="option[targetId]">{{ option[targetLabel] }}</option>
    </select>
  </template>

  <script setup>
  import { ref } from "vue";

  const props = defineProps({
    modelValue: String,
    withIcon: {
      type: Boolean,
      default: false,
    },
    options: Array,
    targetId: String,
    targetLabel: String,
  });

  const select = ref(null);

  const emit = defineEmits(["update:modelValue"]);

  const focus = () => {
    select.value.focus();
  };
  </script>
