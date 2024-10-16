<script setup>
import { computed, ref } from "vue";
import CardBoxComponentEmpty from '@/components/card/CardBoxComponentEmpty.vue';

const props = defineProps({
    classLevel: {
        type: Array,
        required: true,
    }
});

const emit = defineEmits(["open-modal"]);

const classLevels = computed(() => props.classLevel);

const openModal = (classLevel) => {
    emit("open-modal", classLevel);
}
</script>

<template>
    <div v-if="classLevels.length" class="grid grid-cols-1 gap-6 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2">
        <div v-for="classLevel in classLevels" :key="classLevel.class_level_id">
            <a href="#" @click="openModal(classLevel)" class="mini-box relative text-center p-5 bg-gray-200 dark:bg-gray-500 block rounded-3xl hover:-mt-3 before:absolute before:rounded-3xl before:bg-white before:-bottom-2.5 before:-right-2.5 before:w-full before:h-full before:opacity-20 before:mx-auto">
                <strong class="block font-normal text-base">{{ classLevel.label }}</strong>
                <span class="block font-normal text-sm text-gray-400">{{ classLevel.niveauLabel }}</span>
            </a>
        </div>
    </div>
    <div v-else>
        <CardBoxComponentEmpty />
    </div>
</template>

<style scoped>

</style>
