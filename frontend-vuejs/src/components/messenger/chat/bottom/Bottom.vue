<script setup>
import { CheckIcon, FaceSmileIcon, MicrophoneIcon, PaperAirplaneIcon, PaperClipIcon, XCircleIcon } from "@heroicons/vue/24/outline";
import {reactive, ref, inject, onMounted, onUnmounted} from "vue";
import EmojiPicker from 'vue3-emoji-picker';

import {useAuthStore, useMessengerStore} from "@/stores";

import IconButton from "@/components/messenger/reusables/IconButton.vue";
import PrimaryButton from "@/components/messenger/reusables/PrimaryButton.vue";
import ScaleTransition from "@/components/transitions/ScaleTransition.vue";
import Typography from "@/components/messenger/reusables/Typography.vue";
import SelectedReply from "@/components/messenger/chat/bottom/SelectedReply.vue";

import 'vue3-emoji-picker/css';
import { type_file, type_text } from "@/utility/chat";

const emit = defineEmits([
    "remove-message-to-reply-to",
    "scroll-bottom",
    "typing"
]);

const api = inject('$api');
const $pusher = inject('$pusher');

const props = defineProps({
    activeConversation: Object,
    selectedMessageToReplyTo: Object
});

const messengerStore = useMessengerStore();

// the content of the message
const content = reactive({
    body: null,
    attachment: null
});

// open emoji picker.
const showPicker = ref(false);

const typingNow = ref(0);

const typingTimeout = ref(null);

const handleContent = (event) => {
    content.body = event.target.value;
};

const onSelectEmoji = (emoji) => {
    content.body = emoji;
}
// close picker when you click outside.
const handleClickOutside = (event) => {
    let target = event.target;
    let parent = target.parentElement;

    if ((target && !target.classList.contains('toggle-picker-button')) && (parent && !parent.classList.contains('toggle-picker-button'))) {
        showPicker.value = false;
    }
};

const removeMessageToReplyTo = () => {
    emit('remove-message-to-reply-to');
}

const sendMessage = async (type, messages = null, attachment = null) => {
    await api.messengerApi.sendMessage(messengerStore.activeTutoratId, {
        message: messages,
        type: type,
        replyTo: props.selectedMessageToReplyTo?.id,
        attachment: attachment
    }).then(({success, message}) => {
        if (success) {
            content.body = null;
            content.attachment = null;
            // remove message reply
            removeMessageToReplyTo();
            // update list messengerStore.conversations
            messengerStore.updateMessages(message);
        }
    }).catch((error) => {
        if (error.status === 422) {
            console.log('error =>', error.data.errors)
        }
    });
}

const sendText = async () => {
    if (content.body !== null) {
        const messages = content.body.replace(/\s/g, '');
        if (messages.length > 0) {
            await sendMessage(type_text, content.body);
            emit('scroll-bottom');
        }
    }
}

const openDialogueFiles = () => {
    document.querySelector('#drag-input-file').click();
}

const handleFileInputChange = async (event) => {
    event.stopPropagation();
    event.preventDefault();
    const files = event.type === 'change' ? event.target.files : event.dataTransfer.files;
    if (files.length > 0 && files[0].size < 157286400 /*150M*/) {
        content.attachment = files[0];
        await sendMessage(type_file, null, files[0]);
        emit('scroll-bottom');
    }
}

const keyUpEvent = async (event) => {
    // if enter key pressed.
    if (event.which === 13 || event.keyCode === 13) {
        // if shift + enter key pressed, do nothing (new line).
        // if only enter key pressed, send message.
        if (!event.shiftKey) {
            emit('typing', false);
            await sendText();
        }
    }
}

// typing indicator on [input] keyDown
const keyDownEvent = () => {
    if (typingNow.value < 1) {
        // Trigger typing
        emit('typing', true);
        // Typing now
        typingNow.value = 1;
    }
    // Clear typing timeout
    clearTimeout(typingTimeout.value);
    // Typing timeout
    typingTimeout.value = setTimeout(function () {
        emit('typing', false);
        // Clear typing now
        typingNow.value = 0;
    }, 1000);
}
</script>

<template>
    <div>
        <!--selected reply display-->
        <div class="relative transition-all duration-200" :class="{'pt-[60px]': props.selectedMessageToReplyTo}">
            <SelectedReply
                :selected-message-to-reply-to="props.selectedMessageToReplyTo"
                @remove-message-to-reply-to="removeMessageToReplyTo" />
        </div>

        <div class="h-[80px] px-5 flex items-center" v-if="messengerStore.status !== 'loading'">
            <!--select attachments button-->
            <IconButton @click="openDialogueFiles" class="group w-10 h-10 mr-5 ">
                <PaperClipIcon class="text-gray-300 group-hover:text-indigo-300 " />
                <label
                    class="absolute opacity-0 left-0 cursor-pointer inline-block max-w-full mb-0 font-normal"
                    for="drag-input-file">
                    <input
                        id="drag-input-file"
                        ref="files"
                        class="z-0 relative block w-full h-full hidden cursor-pointer"
                        name="files"
                        type="file"
                        accept="image/png,image/gif,image/jpg,image/jpeg,application/pdf,.zip,.txt,.rar,video/mp4,.mkv"
                        @change="handleFileInputChange">
                </label>
            </IconButton>

            <!--message textarea-->
            <div class="grow mr-5">
                <textarea
                    cols="30"
                    rows="1"
                    placeholder="Ecrire un messsage ...."
                    aria-label="Ecrire un messsage ...."
                    class="resize-none py-6 w-full outline-none text-sm text-black opacity-60
                    dark:text-white dark:opacity-70 font-normal leading-4 tracking-[0.16px] placeholder:text-black
                    placeholder:opacity-50 dark:placeholder:text-white dark:placeholder:opacity-70 bg-transparent border-0 focus:ring-transparent scrollbar"
                    @input="handleContent"
                    @keyup="keyUpEvent"
                    @keydown="keyDownEvent"
                    :value="content.body"
                >
                </textarea>
            </div>

            <!--emojies-->
            <div class="relative">
                <!--emoji button-->
                <IconButton @click="showPicker = !showPicker" class="toggle-picker-button group w-10 h-10 mr-5"
                    aria-label="toggle emoji picker">
                    <XCircleIcon v-if="showPicker"
                        class=" w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
                    <FaceSmileIcon v-else class="w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
                </IconButton>

                <!--emoji picker-->
                <ScaleTransition>
                    <div v-click-outside="handleClickOutside" v-show="showPicker"
                        class="absolute z-10 bottom-[45px] right-0 mt-2">
                        <div role="none">
                            <EmojiPicker :native="true" hide-group-icons hide-group-names
                                class="dark:bg-gray-800 dark:text-white" @select="onSelectEmoji" />
                        </div>
                    </div>
                </ScaleTransition>
            </div>

            <!--send message button-->
            <button
                class="group w-10 h-10 flex justify-center items-center outline-none rounded-full focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-all duration-200"  @click="sendText">
                <PaperAirplaneIcon class="w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
            </button>
        </div>
    </div>
</template>


<style>
input[placeholder="Search emoji"] {
    background: rgba(0, 0, 0, 0.0);
}

.v3-emoji-picker .v3-header {
    border-bottom: 0;
}

.v3-emoji-picker .v3-footer {
    border-top: 0;
}
</style>
