<script setup>
import { getFullName, hasAttachments, shorten } from "@/utility/chat";
import { useAuthStore } from "@/stores";

import Typography from "@/components/messenger/reusables/Typography.vue";

const props = defineProps({
    message: Object,
    self: Boolean,
})

const auth = useAuthStore();
</script>

<template>
    <div class="border-l pl-3 cursor-pointer outline-none border-opacity-50 duration-200" :class="props.self ?
     ['border-white']
    : ['border-black', 'dark:border-white', 'dark:border-opacity-50']" tabindex="0"
        :aria-label="'reply to: ' + getFullName(props.message.sender) ">
        <!--name-->
        <p class="mb-3 font-semibold text-xs leading-4 tracking-[0.16px]"
            :class="props.self ? ['text-white'] : ['text-black', 'opacity-50', 'dark:text-white', 'dark:opacity-70'] ">
            {{message.sender.id !== auth.user.id ? getFullName(props.message.sender): 'Vous'}}
        </p>

        <!--content-->
        <Typography v-if="props.message.body" variant="body-2" :no-color="true"
            :class="props.self ? ['text-white']:['text-black', 'opacity-50', 'dark:text-white', 'dark:opacity-70']">
            {{ shorten(props.message, 60)}}
        </Typography>

        <!--attachments title-->
        <Typography v-else-if="hasAttachments(props.message)" variant="body-2"
            :class="props.self ? ['text-white']:['text-black', 'opacity-50', 'dark:text-white', 'dark:opacity-70']">
            {{ props.message?.attachment }}
        </Typography>
    </div>
</template>
