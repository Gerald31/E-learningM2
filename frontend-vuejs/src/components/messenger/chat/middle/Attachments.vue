<script setup>
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PlayIcon } from "@heroicons/vue/24/solid";
import { computed, ref } from "vue";

import Typography from '@/components/messenger/reusables/Typography.vue';
import MediaCarousel from "@/components/messenger/chat/MediaCarousel.vue";
import { fullPathPicture } from "@/utility/media";

const props = defineProps({
    message: Object,
    self: Boolean,
});

const openCarousel = ref(false);

const selectedAttachment = ref();

// open the carousel with the selected index
const openCarouselWithAttachment = (attachment) => {
    selectedAttachment.value = attachment;
    openCarousel.value = true;
};

// close the carousel
const closeCarousel = () => {
    openCarousel.value = false;
};

// check if the message contians images or videos
const containsMedia = computed(() => {
    if (props.message) {
        if (['image', 'video'].includes(props.message.type))
            return true;
    }
    return false;
});
</script>

<template>
    <div>
        <div class="flex">
            <div class="mr-2 flex items-end mt-4">
                <!--image-->
                <button role="image" v-if="message.type === 'image'"
                    @click="openCarouselWithAttachment(message.attachment)" class="outline-none"
                    :aria-label="attachment">
                    <div :style="{ backgroundImage: `url(${fullPathPicture(message?.attachment.path)?? avatar})`}"
                        class="rounded bg-cover bg-center w-[200px] h-[200px]">

                        <!--first image-->
                        <div class="w-full h-full flex justify-center items-center rounded bg-black bg-opacity-20 hover:bg-opacity-10 transition duration-200">
                        </div>
                    </div>
                </button>
                <!--video-->
                <button role="video" v-if="message.type === 'video'"
                        @click="openCarouselWithAttachment(message.attachment)" class="overflow-hidden outline-none"
                        :aria-label="attachment">
                    <div :style="{ backgroundImage: `url(${fullPathPicture(message?.attachment.path)?? avatar})`}"
                         class="rounded bg-cover bg-center w-[200px] h-[200px]">

                        <!--first video-->
                        <div class="w-full h-full flex justify-center items-center rounded bg-black bg-opacity-20 hover:bg-opacity-10 transition duration-200">
                            <span class="p-3 rounded-full bg-white bg-opacity-40 transition-all duration-200">
                                <PlayIcon class="w-5 h-5 text-white" />
                            </span>
                        </div>
                    </div>
                </button>
                <!--file-->
                <div v-if="message.type === 'file' && !containsMedia">
                    <div class="flex">
                        <!--download button / icons-->
                        <button class="w-8 h-8 mr-4  flex justify-center rounded-full outline-none items-center duration-200"
                                :class="props.self ? ['bg-white', 'hover:bg-gray-100','active:bg-gray-200']
                            : ['bg-indigo-50', 'hover:bg-indigo-100', 'active:bg-indigo-200', 'dark:bg-gray-400', 'dark:hover:bg-gray-300', 'dark:focus:bg-gray-300', 'dark:active:bg-gray-200'] ">
                            <ArrowDownTrayIcon class="stroke-2 h-5 w-5 "
                                               :class="props.self ? ['text-black', 'text-opacity-50'] : ['text-blue-500', 'dark:text-gray-50']" />
                        </button>

                        <div class="flex flex-col justify-center ">
                            <Typography
                                variant="heading-2"
                                :no-color="true"
                                class="mb-3"
                                :class="props.self ? ['text-white'] : ['text-black', 'opacity-50', 'dark:text-white', 'dark:opacity-70']"
                            >
                                {{ message.attachment.name }}
                            </Typography>

                            <Typography
                                variant="body-2"
                                :no-color="true"
                                :class="props.self ? ['text-white'] : ['text-black', 'opacity-50', 'dark:text-white', 'dark:opacity-70']"
                            >
                                {{ message.attachment.size }}
                            </Typography>
                        </div>
                    </div>
                </div>
            </div>

            <!--carousel modal-->
            <MediaCarousel
                :open="openCarousel"
                :starting-id="selectedAttachment"
                @close-carousel="closeCarousel" />
        </div>
    </div>
</template>
