<script setup>
import { ref } from 'vue';
import { generateUploadingFile, updateUploadingFileInformations } from '@/utility/files';

const props = defineProps({
    accept: {
        type: String,
        default: 'image/*'
    },
    multiple: {
        type: Boolean,
        default: false
    }
});
const isFile = ref(false);
const showUploadFiles = ref(true);
const hoverOnDragAndDrop = ref(false);
const uploadFiles = ref([]);

const emit = defineEmits(["change"]);

const handleFileInputChange = (event) => {
    event.stopPropagation();
    event.preventDefault();
    const files = event.type === 'change' ? event.target.files : event.dataTransfer.files;
    hoverOnDragAndDrop.value = false;
    if (props.multiple) {
        uploadFiles.value.push(files[0]);
    } else {
        uploadFiles.value = [files[0]];
    }
    emit('change', uploadFiles.value);
}

const dragOverInput = (e) => {
    e.stopPropagation();
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    if (e.dataTransfer.types) {
        isFile.value = e.dataTransfer.types[0] === 'Files';
    }
}
</script>

<template>
    <div class="flex flex-col w-full h-full box-border">
        <div class="flex flex-col items-stretch h-full">
            <div class="flex-grow flex flex-col items-center justify-center bg-gray-200 rounded-lg text-center relative border-transparent border-solid mt-2 py-2.5 border-2"
               :class="[
                {'is-draghovered': hoverOnDragAndDrop && isFile }
              ]">
                <div class="text-gray-500 flex flex-col justify-center items-center align-baseline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <label class="text-base text-gray-700 font-medium px-2.5 pt-2.5 cursor-pointer inline-block max-w-full mb-0">
                        Glisser et déposer une pièce ou cliquer ici
                    </label>
                    <input
                        id="files-to-upload"
                        ref="files"
                        class="absolute w-full h-full opacity-0 cursor-pointer top-0 left-0 block"
                        name="files"
                        type="file"
                        draggable="true"
                        :accept="accept"
                        :multiple="multiple"
                        @dragover="dragOverInput"
                        @dragenter="hoverOnDragAndDrop = true"
                        @dragleave="hoverOnDragAndDrop = false"
                        @drop="handleFileInputChange($event)"
                        @change="handleFileInputChange">
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
