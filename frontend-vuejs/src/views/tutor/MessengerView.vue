<script setup>
import {inject, onMounted, ref, computed, onBeforeUnmount} from "vue";
import loading3 from "@/components/loading/loading3.vue";
import NoChatSelected from "@/components/messenger/emptyStates/NoChatSelected.vue";
import SidebarMessenger from "@/components/messenger/sidebar/SidebarMessenger.vue";
import Chat from "@/components/messenger/chat/Chat.vue";
import { useMessengerStore } from "@/stores";
import {fullPathPicture} from "@/utility/media";

const api = inject("$api");

const messengerStore = useMessengerStore();

const props = defineProps({
    tutorat: String,
});

const tutoratId = ref(props.tutorat);

onMounted(async () => {
    messengerStore.setStatus('loading');
    setTimeout(() => {
        messengerStore.setDelayLoading(false);
    });
    await api.messengerApi.setActiveStatus({ status:  true });
    const contactList = await api.messengerApi.fetchContacts(tutoratId.value);
    const messages = await api.messengerApi.fetchMessages(tutoratId.value);
    messengerStore.$patch({
        status: 'success',
        contacts: contactList.contacts,
        conversations: messages,
        activeTutoratId: tutoratId.value,
        activeTutorat: contactList.tutorat,
    });
});

const openContact = computed(() => messengerStore.openContact)

onBeforeUnmount(async () => {
    await api.messengerApi.setActiveStatus({ status:  false });
})

</script>

<template>
    <KeepAlive>
        <div class="xs:relative h-full bg-white w-full flex xs:flex-col md:flex-row overflow-hidden grid grid-cols-6 gap-4">
            <div
                :class="{ hidden: !openContact}">
                <SidebarMessenger class="xs:grow-1 md:grow-0 xs:overflow-y-scroll md:overflow-visible scrollbar-hidden" />
            </div>
            <div class="col-span-5 ml-8"
                 :class="{ 'col-span-6': !openContact}">
                <Chat />
            </div>
        </div>
    </KeepAlive>
</template>

<style scoped>
body {
    overflow: hidden;
}
</style>
