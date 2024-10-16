<script setup>
import { ArrowUturnLeftIcon, BookmarkIcon, BookmarkSquareIcon, TrashIcon, ArrowDownTrayIcon } from "@heroicons/vue/24/outline";

import linkifyStr from 'linkify-string';
import { ref } from "vue";

import Dropdown from "@/components/messenger/reusables/Dropdown.vue";
import DropdownLink from "@/components/messenger/reusables/DropdownLink.vue";
import Typography from "@/components/messenger/reusables/Typography.vue";
import ReplyPreview from "@/components/messenger/chat/MessagePreview.vue";
import Attachments from "@/components/messenger/chat/middle/Attachments.vue";
import LinkPreview from "@/components/messenger/chat/middle/LinkPreview.vue";
import Recording from "@/components/messenger/chat/middle/Recording.vue";
import { getFullName } from "@/utility/chat";
import { fullPathPicture } from "@/utility/media";
import avatar from "@/assets/avatar.webp";

const emit = defineEmits(["select-message-to-reply-to", "download-attachment"]);

const props = defineProps({
    message: Object,
    followUp: Boolean,
    self: Boolean,
    divider: Boolean,
    replyToMessage: Object
});

const showContextMenu = ref(false);

const contextMenuCordinations = ref();

const selectMessageToReplyTo = (message) => {
    emit('select-message-to-reply-to', message);
}

const downloadFile = (message) => {
    emit('download-attachment', message);
}

// open context menu.
const handleShowContextMenu = (event) => {
    showContextMenu.value = true;
    contextMenuCordinations.value = {
        x: window.innerWidth - 220 <= event.pageX ? window.innerWidth - 250 : event.pageX,
        y: window.innerHeight - 200 <= event.pageY ? window.innerHeight - 200 : event.pageY
    };
};

// closes the context menu
const handleCloseContextMenu = () => {
    showContextMenu.value = false;
};

// close context menu when opening a new one.
const contextConfig = {
    handler: handleCloseContextMenu,
    events: ['contextmenu'],
};

// decide whether to show or hide avatar next to the image.
const hideAvatar = () => {
    if (props.divider && !props.self) {
        return false;
    }
    else {
        if (props.followUp) {
            return true;
        }
        if (props.self) {
            return true;
        }
    }
};

// decide whether to show or hide avatar next to the image.
const showAvatar = () => {
    if (props.message.sender.avatar) {
        return fullPathPicture(props.message.sender.avatar);
    }
    return avatar;
};
</script>

<template>
    <div>
        <div class="xs:mb-6 md:mb-5 flex" :class="{'justify-end': props.self}">
            <!--avatar-->
            <div class="mr-4" :class="{'ml-[36px]': props.followUp && !divider}">
                <div v-if="!hideAvatar()" tabindex="0" :aria-label="getFullName(props.message.sender)"
                    class="outline-none">
                    <div :style=" { backgroundImage: `url(${showAvatar()})`}"
                        class="w-[36px] h-[36px] bg-cover bg-center rounded-full">
                    </div>
                </div>
            </div>

            <div class="flex-rows items-end">
                <!--bubble-->
                <div
                    @click="handleCloseContextMenu"
                    v-click-outside="contextConfig"
                    @contextmenu.prevent="handleShowContextMenu"
                    class="group max-w-[500px] p-5 rounded-b transition duration-500"
                    :class="[props.self ? ['rounded-tl', 'ml-4', 'order-1', 'bg-purple-500', 'dark:bg-indigo-400'] : ['rounded-tr', 'mr-4', 'bg-gray-300', 'dark:bg-gray-600']]">

                    <!--reply to-->
                    <ReplyPreview v-if="props.replyToMessage" :message="props.replyToMessage"
                        :self="props.self" class="mb-5 px-3" />

                    <!--content-->
                    <Typography variant="body-2" noColor
                        v-if='props.message.body'
                        :class="props.self ? ['text-white'] : ['text-black', 'opacity-60', 'dark:text-white', 'dark:opacity-70']"
                        v-html="linkifyStr(props.message.body, {
                        className: props.self ? 'text-black opacity-50' : 'text-indigo-500 dark:text-indigo-300',
                        format: {url:(value) => value.length > 50 ? value.slice(0, 50) + `…` : value}
                        })" tabindex="0">
                    </Typography>

                    <!--attachments-->
                    <Attachments v-if="props.message.attachment" :message="props.message"
                        :self="props.self" />

                    <!--link preview-->
                    <LinkPreview v-if="props.message.previewData && !props.message.attachment" :self="props.self"
                        :preview-data="props.message.previewData" class="mt-5" />
                </div>

                <!--date-->
                <div :class="props.self ? 'pr-4 text-right' : 'pl-4'">
                    <Typography variant="body-1" class="whitespace-pre">
                        {{ $filters.timefrByFullDate(props.message?.created_at) }}
                    </Typography>
                </div>
            </div>
        </div>

        <!--custom context menu-->
        <Dropdown :close-dropdown="() => showContextMenu = false" :show="showContextMenu"
            :handle-close="handleCloseContextMenu" :handle-click-outside="handleCloseContextMenu"
            :cordinates="{left: contextMenuCordinations?.x + 'px', top: contextMenuCordinations?.y + 'px'}"
            :position="['top-0']">
            <DropdownLink :handle-click="()=>{handleCloseContextMenu(); selectMessageToReplyTo(props.message)}">
                <ArrowUturnLeftIcon class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                Repondrer
            </DropdownLink>
            <DropdownLink v-if="props.message.attachment" :handle-click="()=>{handleCloseContextMenu(); downloadFile(props.message)}">
                <ArrowDownTrayIcon class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                Télécharger
            </DropdownLink>
            <DropdownLink :handle-click="handleCloseContextMenu"
                class="text-red-500 hover:bg-red-100 active:bg-red-100 dark:text-red-500">
                <TrashIcon class="h-5 w-5 mr-3 text-red-500" />
                Supprimer
            </DropdownLink>
        </Dropdown>
    </div>
</template>
