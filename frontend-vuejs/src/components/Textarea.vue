<template>
    <textarea
        :class="[
      'py-2 rounded-md',
      'block w-full py-2 rounded-md dark:bg-dark-eval-1 dark:text-gray-300',
      'font-normal text-base h-32 min-h-full rounded-lg p-2 text-gray-700 bg-gray-200',
      {
        'px-4': !withIcon,
        'pl-11 pr-4': withIcon,
      },
    ]"
        :value="modelValue"
        @input="updateValue"
        @change="$emit('onChange', $event)"
        ref="textarea"
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
});

const textarea = ref(null);

const emit = defineEmits(["update:modelValue", "onChange"]);

const focus = () => {
    textarea.value.focus();
};

const updateValue = ($event) => {
    emit('update:modelValue', $event.target.value)
}
</script>
<style scoped>
textarea, textarea:focus {
    outline: 0 none;
    border: none;
    box-shadow: none;
}
</style>
