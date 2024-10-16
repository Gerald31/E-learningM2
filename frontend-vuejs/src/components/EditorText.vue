<script setup>
import BlotFormatter from 'quill-blot-formatter';

const modules = {
    name: 'blotFormatter',
    module: BlotFormatter,
    options: {}
}

const props = defineProps({
    modelValue: String,
    values: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(["update:modelValue"]);

const updateText = (content) => {
    emit('update:modelValue', content)
}
</script>

<template>
    <QuillEditor
        :modules="modules"
        toolbar="full"
        placeholder="Rediger la contenu de votre article"
        contentType="html"
        :content="values"
        v-model:content="modelValue"
        @update:content="updateText" />
</template>

<style scoped>

</style>
