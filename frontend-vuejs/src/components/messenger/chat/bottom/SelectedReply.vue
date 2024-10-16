<script setup>
import { XCircleIcon } from "@heroicons/vue/24/outline";

import IconButton from "@/components/messenger/reusables/IconButton.vue";
import SlideTransition from "@/components/transitions/SlideTransition.vue";
import ReplyPreview from "@/components/messenger/chat/MessagePreview.vue";

const emit = defineEmits(["remove-message-to-reply-to"]);

const props = defineProps({
    selectedMessageToReplyTo: Object
});

const removeMessageToReplyTo = () => {
    emit('remove-message-to-reply-to');
}
</script>

<template>
    <SlideTransition animation="shelf-up">
        <div class="absolute bottom-0 w-full px-5 py-2 bg-white dark:bg-gray-800 flex items-center justify-between transition-all duration-200"
            v-if="props.selectedMessageToReplyTo">
            <!--selected message overview-->
            <ReplyPreview :message="props.selectedMessageToReplyTo" />

            <!--close selected Message-->
            <IconButton @click="removeMessageToReplyTo" class="group">
                <XCircleIcon class="w-[16px] h-[16px] text-gray-300 group-hover:text-red-300" />
            </IconButton>
        </div>
    </SlideTransition>
</template>
