<template>
  <input
    :type="type"
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
    @input="updateValue"
    @change="$emit('onChange', $event)"
    ref="input"
  />
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  modelValue: String|Number,
  withIcon: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'text',
  },
  error: {
    type: Boolean,
    default: false,
  },
});

const input = ref(null);

const emit = defineEmits(["update:modelValue", "onChange"]);

const focus = () => {
  input.value.focus();
};

const updateValue = ($event) => {
    emit('update:modelValue', $event.target.value)
}
</script>
<style scoped>
input, input:focus {
    outline: 0 none;
    box-shadow: none;
}
</style>
