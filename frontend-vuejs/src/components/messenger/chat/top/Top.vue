<script setup>
import {
    ChevronLeftIcon,
    EllipsisVerticalIcon,
    InformationCircleIcon,
    MagnifyingGlassIcon,
    NoSymbolIcon,
    VideoCameraIcon,
    ShareIcon,
    ChatBubbleOvalLeftIcon,
    UserGroupIcon
} from "@heroicons/vue/24/outline";
import { ref, computed } from "vue";

import { useMessengerStore } from "@/stores";

import Dropdown from "@/components/messenger/reusables/Dropdown.vue";
import DropdownLink from "@/components/messenger/reusables/DropdownLink.vue";
import IconButton from "@/components/messenger/reusables/IconButton.vue";
import Typography from "@/components/messenger/reusables/Typography.vue";
import avatar from "@/assets/avatar.webp";

const messengerStore = useMessengerStore();

const showDropdown = ref(false);

const openSearch = ref(false);

const openInfo = ref(false);

// (event) close dropdown menu when click item
const handleCloseDropdown = () => {
    showDropdown.value = false;
};

// (event) close dropdown menu when clicking outside the menu.
const handleClickOutside = (event) => {
    let target = event.target;
    let parentElement = target.parentElement;

    if ((target && !target.classList.contains('open-top-menu'))
        && (parentElement && !parentElement.classList.contains('open-top-menu'))) {
        handleCloseDropdown();
    }
};

const activeVideo = computed(() => messengerStore.openVideo);
const activeContact = computed(() => messengerStore.openContact);

// (event) close the selected conversation
const handleCloseConversation = () => {
    messengerStore.conversationOpen = 'close';
};

// (event) open the voice call modal and expand call
const handleOpenCloseVideo = () => {
    if (!activeVideo.value) {
        messengerStore.setOpenCloseContacts(false);
    } else {
        messengerStore.setOpenCloseContacts(true);
    }
    messengerStore.setActiveVideo();
};

const handleOpenCloseContacts = () => {
    messengerStore.setOpenCloseContacts(!activeContact.value);
}

// (event) close the voice call modal and minimize the call
const handleCloseVoiceCallModal = (endCall) => {
    if (endCall) {
        messengerStore.activeCall = null;
        messengerStore.callMinimized = false;
    }

    if (messengerStore.openVoiceCall) {
        messengerStore.openVoiceCall = false;
        messengerStore.callMinimized = true;
    }
};
</script>

<template>
    <div>
        <!--Top section-->
        <div class="w-full px-5 py-6 flex justify-center items-center">
            <!--back button-->
            <div class="group mr-4 md:hidden">
                <IconButton class="w-7 h-7" @click="handleCloseConversation">
                    <ChevronLeftIcon aria-label="close conversation"
                        class="w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
                </IconButton>
            </div>

            <div v-if="messengerStore.status !== 'loading'" class="flex grow">
                <!--avatar-->
                <button class="mr-5 outline-none" @click="() => openInfo = true" aria-label="profile avatar">
                    <div :style="{ backgroundImage: `url(${avatar})`}"
                        class="w-[36px] h-[36px] rounded-full bg-cover bg-center">
                    </div>
                </button>

                <!--name and last seen-->
                <div class="flex flex-col">
                    <Typography variant="heading-2" @click="() => openInfo = true" class="mb-2 outline cursor-pointer"
                        tabindex="0">
                        {{ messengerStore.activeTutorat?.subject }}
                    </Typography>

                    <Typography variant="body-2" class="font-extralight" tabindex="0"
                        :aria-label="messengerStore.activeTutorat?.subject_class?.subject_name">
                        {{ messengerStore.activeTutorat?.subject_class?.subject_name }}
                    </Typography>
                </div>
            </div>

            <div class="flex" :class="{'hidden': messengerStore.status === 'loading'}">
                <!--search button-->
                <IconButton @click="openSearch = true" aria-label="Search messages" class="group w-10 h-10 mr-3">
                    <MagnifyingGlassIcon class="w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
                </IconButton>

                <div class="relative">
                    <!--dropdown menu button-->
                    <IconButton id="open-conversation-menu" @click="showDropdown = !showDropdown" tabindex="0"
                        class="open-top-menu group w-10 h-10" :aria-expanded="showDropdown"
                        aria-controls="conversation-menu" aria-label="toggle conversation menu">
                        <EllipsisVerticalIcon
                            class="open-top-menu w-[20px] h-[20px] text-gray-300 group-hover:text-indigo-300" />
                    </IconButton>

                    <!--dropdown menu-->
                    <Dropdown id="conversation-menu" :close-dropdown="() => showDropdown = false" :show="showDropdown"
                        :position="['right-0']" :handle-click-outside="handleClickOutside"
                        aria-labelledby="open-conversation-menu">
                        <DropdownLink :handle-click="handleCloseDropdown">
                            <InformationCircleIcon
                                class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                            Information
                        </DropdownLink>
                        <DropdownLink :handle-click="()=>{handleCloseDropdown(); handleOpenCloseContacts()}">
                            <UserGroupIcon
                                class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                            Contacts
                        </DropdownLink>

                        <DropdownLink v-if="activeVideo" :handle-click="()=>{handleCloseDropdown(); handleOpenCloseVideo()}">
                            <ChatBubbleOvalLeftIcon class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                            Messages
                        </DropdownLink>
                        <DropdownLink v-else :handle-click="()=>{handleCloseDropdown(); handleOpenCloseVideo()}">
                            <VideoCameraIcon class="h-5 w-5 mr-3 text-black opacity-60 dark:text-white dark:opacity-70" />
                            Rejoindre
                        </DropdownLink>
                    </Dropdown>
                </div>
            </div>
        </div>
    </div>
</template>
