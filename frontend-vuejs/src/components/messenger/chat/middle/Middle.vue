<script setup>
import { onMounted, provide, ref } from "vue";

import { useMessengerStore, useAuthStore } from "@/stores";

import MessageBubble from "@/components/messenger/chat/middle/MessageBubble.vue";
import TimelineDivider from "@/components/messenger/chat/middle/TimelineDivider.vue";
import moment from "moment";

const emit = defineEmits(["select-message-to-reply-to", "download-attachment"]);

const props = defineProps({
    activeConversation: Object,
});

const auth = useAuthStore();

const messengerStore = useMessengerStore()

const selectMessageToReplyTo = (message) => {
    emit('select-message-to-reply-to', message);
}

const downloadAttachment = (message) => {
    emit('download-attachment', message);
}

// checks whether the previous message was sent by the same user.
const isFollowUp = (index, previousIndex) => {
    if (previousIndex < 0) {
        return false;
    } else {
        let previousSender = props.activeConversation?.messages[previousIndex].sender.id;
        let currentSender = props.activeConversation?.messages[index].sender.id;
        return previousSender === currentSender;
    }
};

// checks whether the message is sent by the authenticated user.
const isSelf = (message) => {
    return message.sender.id === auth.user.id
};

// checks wether the new message has been sent in a new day or not.
const renderDivider = (index, previousIndex) => {
    const previousDate = props.activeConversation?.messages[previousIndex]?.created_at;
    const currentDate = props.activeConversation?.messages[index]?.created_at;
    return moment(previousDate).format("YYYY-MM-DD") !== moment(currentDate).format("YYYY-MM-DD");
};

// get message to reply to.
const getReplyToMessage = (message) => {
    if (message.reply_to) {
        return props.activeConversation?.messages.find(item => item.id === message.reply_to);
    }
};

// provide the active conversation to all children
provide('activeConversaion', props.activeConversation);
</script>

<template>
    <div
        v-if="messengerStore.status !== 'loading'"
        v-for="(message, index) in props.activeConversation?.messages"
        :key="index"
    >
        <TimelineDivider v-if="renderDivider(index, index-1)" :message="message" />

        <MessageBubble
            :message="message"
            :self="isSelf(message)"
            :follow-up="isFollowUp(index, index - 1)"
            :divider="renderDivider(index, index - 1)"
            :reply-to-message="getReplyToMessage(message)"
            @select-message-to-reply-to="selectMessageToReplyTo"
            @download-attachment="downloadAttachment"
        />
    </div>
</template>
