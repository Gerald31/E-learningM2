<script setup>
import { computed, ref, watch, nextTick } from "vue";
import { ChevronDownIcon, XMarkIcon } from "@heroicons/vue/24/solid";
import DropDownTransition from '@/components/classlevel/DropDownTransition.vue';

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    items: {
        type: Array,
        required: true,
    },
    itemIdKey: {
        type: String,
        required: true,
    },
    itemLabelKey: {
        type: String,
        required: true,
    },
    selectedId: {
        type: Number,
        default: 0,
    },
});

const isOpened = ref(false);
const isAnimating = ref(false);
const selectedItemId = ref(props.selectedId);
const selectedItemLabel = ref(null);

const selectedItem = computed(() => props.items?.find((item) => item[props.itemIdKey] === selectedItemId));
selectedItemLabel.value = selectedItem?.value?.[props.itemLabelKey];

const emit = defineEmits(["update:selected-id", "validate", "toggle-open"]);

const resetSelect = () => {
    selectedItemId.value = 0;
    emit('update:selected-id', selectedItemId);
}

const selectItem = (itemId) => {
    selectedItemId.value = itemId;
    const selectedItem = computed(() => props.items?.find((item) => item[props.itemIdKey] === itemId));
    selectedItemLabel.value = selectedItem?.value?.[props.itemLabelKey];
    // Give chance to momentarily see selection for better UX
    nextTick(toggleOpen);
    emit('update:selected-id', itemId);
}

const validate = () => {
    emit('validate');
}

const toggleOpen = () => {
    isOpened.value = !isOpened.value;
    emit('toggle-open', isOpened);
    isAnimating.value = true;
    nextTick(() => { isAnimating.value = false; });
}

const onClickOutside = () => {
    if (isOpened.value) toggleOpen();
}

const resetSelection = () => {
    resetSelect();
    validate();
}
const shouldShowSelected = () => {
    return selectedItemId.value !== 0;
}

</script>

<template>
    <div v-click-outside="onClickOutside"
         class="relative"
         :class="{open: isOpened, animating: isAnimating}">
        <div class="cursor-default select-none bg-gray-200 h-12 text-left relative flex justify-center items-center font-medium rounded-xl px-5 py-0"
             @click="toggleOpen">
            <div class="mr-2 block whitespace-normal overflow-hidden flex-grow text-base font-medium text-gray-700 text-ellipsis">
                {{ title || selectedItemLabel }}
                <slot name="custom-title" />
            </div>
            <XMarkIcon v-if="isOpened" class="w-5 absolute text-xs text-gray-700 cursor-pointer right-5 leading-none" />
            <ChevronDownIcon v-else class="w-5 absolute text-xs text-gray-700 cursor-pointer right-5 leading-none" />
        </div>
        <div v-if="isOpened" class="w-full z-20 absolute border-gray-200 border-solid" />
        <DropDownTransition v-if="items" :opened="isOpened">
            <div key="content-slide" class="w-full absolute p-0 font-medium text-base bg-white overflow-hidden z-30 cursor-default select-none rounded-tl-none rounded-b-xl rounded-tr-xl max-h-96 top-12">
                <ul class="mt-1 mr-1 mb-0 ml-0 max-h-64 overflow-y-auto p-0">
                    <li v-for="({[`${itemIdKey}`]: id, [`${itemLabelKey}`]: label}) in items"
                        :key="id"
                        class="text-gray-700 m-0 flex items-center py-2 pr-0 pl-6 hover:bg-gray-200 hover:cursor-pointer"
                        @click="selectItem(id)">
                        <div class="relative border-2 border-gray-700 w-5 h-5 mr-3 rounded-full after:block after:absolute after:rounded-lg after:top-1/2 after:left-1/2 after:h-3/4 after:w-3/4 after:-translate-y-1/2 after:-translate-x-1/2" :class="{'after:bg-gray-700': id === selectedItemId}" />
                        <div class="operation-filter__container col-10">
                          <span class="operation-filter__label">
                            {{ label }}
                          </span>
                        </div>
                    </li>
                </ul>
            </div>
        </DropDownTransition>
    </div>
</template>

<style scoped>

ul::-webkit-scrollbar {
    position: absolute;
    width: 5px;
    background-color: #F5F5F5;
}
ul::-webkit-scrollbar-track {
    background-color: #F5F5F5;
    background-clip: padding-box;
}
ul::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-clip: padding-box;
    border-radius: 20px;
    width: 7px;
    background-color: #A8A8A7;
}
</style>
