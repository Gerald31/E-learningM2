<template>
  <div v-show="messages">
    <p v-for="message in messages" class="text-sm text-red-500">
      {{ message }}
    </p>
  </div>
</template>

<script setup>
const props = defineProps({
  messages: Array,
});
</script>
