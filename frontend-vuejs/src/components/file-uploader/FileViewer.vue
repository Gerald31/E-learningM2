<script setup>
import { computed, ref } from 'vue';
import { generateUploadingFile, updateUploadingFileInformations } from '@/utility/files';
import { TrashIcon } from  "@heroicons/vue/24/outline";

const isFile = ref(false);
const currentFileIndex = ref(0);
const isConfirmRemoveFileVisible = ref(false);
const hoverOnDragAndDrop = ref(false);

const emit = defineEmits(["change"]);

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    files: {
        type: Array,
        required: true,
    },
    isEditable: {
        type: Boolean,
        required: true,
    },
    multiple: {
        type: Boolean,
        default: false
    },
    accept: {
        type: String,
        default: 'image/*'
    },
});

const uploadFiles = ref(props.files);

const removeFile = () => {
    uploadFiles.value.shift();
    emit('change', uploadFiles.value);
}

const convertByteMo = (value) => {
    return Math.round(parseInt(value) * Math.pow(10, -6) * 100) / 100
}

const handleFileInputChange = (event) => {
    event.stopPropagation();
    event.preventDefault();
    const files = event.type === 'change' ? event.target.files : event.dataTransfer.files;

    if (props.multiple) {
        uploadFiles.value.push(files[0]);
    } else {
        uploadFiles.value = [files[0]];
    }
    emit('change', uploadFiles.value);
    hoverOnDragAndDrop.value = false;
}
const dragOverInput = (e) => {
    e.stopPropagation();
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    if (e.dataTransfer.types) {
        isFile.value = e.dataTransfer.types[0] === 'Files';
    }
}
const openDialogueFiles = () => {
    document.querySelector('#drag-input-file').click();
}
</script>

<template>
    <div class="justify-between h-full w-full">
        <div class="flex flex-row flex-col justify-between h-full bg-gray-200 p-2 rounded-lg border-transparent border-solid border-2">
            <div class="pt-1 flex flex-col justify-between h-full">
                <div v-if="props.files && props.files.length > 0"
                     class="h-32 overflow-y-auto pt-1 p-0 box-border files_preview"
                     :class="{ 'is-draghovered': hoverOnDragAndDrop && isFile }"
                     draggable="true"
                     @dragover="dragOverInput"
                     @dragenter="hoverOnDragAndDrop = true"
                     @dragleave.self="hoverOnDragAndDrop = false"
                     @drop="handleFileInputChange($event)">
                    <div v-for="(file, index) in props.files"
                         :key="index"
                         class="flex items-center justify-between py-2.5 px-0 border-gray-400 [&:not(:first-child)]:border-t">
                        <p class="font-semibold text-sm cursor-pointer truncate"
                           :title="file.name">
                            {{ file.name }} - {{ convertByteMo(file.size) }} Mo
                        </p>
                        <button v-if="!isEditable"
                                class="flex justify-end items-center text-gray-700 bg-gray-200 pl-2.5 border-0 outline-0"
                                @click="removeFile(index)">
                            <TrashIcon class="flex-shrink-0 w-9 h-9 p-2" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-center pt-1">
                <div class="flex-row items-center flex justify-around flex-wrap">
                    <button
                        class="w-24 m-0 py-1 px-4 bg-transparent text-gray-700 font-normal text-sm border border-gray-700 relative inline-block cursor-pointer rounded-3xl shadow-none  hover:bg-gray-200"
                        @click="openDialogueFiles"
                    >
                        Parcourir
                    </button>
                </div>
                <label
                    class="absolute opacity-0 left-0 cursor-pointer inline-block max-w-full mb-0 font-normal"
                    for="drag-input-file">
                    <input
                        id="drag-input-file"
                        ref="files"
                        class="z-0 relative block w-full h-full opacity-0 cursor-pointer"
                        name="files"
                        type="file"
                        :accept="accept"
                        :multiple="multiple"
                        @change="handleFileInputChange">
                </label>
            </div>
        </div>
    </div>
</template>

<style scoped>
.files_preview::-webkit-scrollbar {
    position: absolute;
    width: 5px;
    background-color: #F5F5F5;
}
.files_preview::-webkit-scrollbar-track {
    background-color: #F5F5F5;
    background-clip: padding-box;
}
.files_preview::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-clip: padding-box;
    border-radius: 20px;
    width: 7px;
    background-color: #A8A8A7;
}
</style>
