<script setup>
import { computed } from "vue";
import loading3 from "@/components/loading/loading3.vue";
import NoChatSelected from "@/components/messenger/emptyStates/NoChatSelected.vue";
import SelectedChat from "@/components/messenger/chat/SelectedChat.vue";
import { useMessengerStore } from "@/stores";

const messengerStore = useMessengerStore();

// the active chat component or loading component.
const activeChatComponent = computed(() => {
    if (messengerStore.status === 'loading' || messengerStore.delayLoading)
        return loading3;
    else if (messengerStore.activeTutoratId)
        return SelectedChat;
    else
        return NoChatSelected;
});

</script>

<template>
    <div id="mainContent"
         class="xs:absolute xs:z-10 md:static grow overflow-y-auto h-full xs:w-full scrollbar-hidden dark:bg-gray-800 transition-all duration-500"
         :class="['xs:left-[0px]','xs:static']" role="region">
        <Transition name="fade" mode="out-in">
            <component :is="activeChatComponent" />
        </Transition>
    </div>
</template>

<style scoped>
@media only screen and (min-width: 969px) {

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.2s ease-in;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
}
</style>
