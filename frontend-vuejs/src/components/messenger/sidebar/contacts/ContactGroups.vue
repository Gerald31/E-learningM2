<script setup>
import { ref } from 'vue';
import Typography from "@/components/messenger/reusables/Typography.vue";
import { getFullName, getRolesContact } from "@/utility/chat";
import avatar from "@/assets/avatar.webp";
import { fullPathPicture } from '@/utility/media';

const props = defineProps({
    contactGroups: Array,
    bottomEdge: Number,
});

// the position of the dropdown menu.
const dropdownMenuPosition = ref(['top-6', 'right-0']);

// controll the states of contact dropdown menus
const dropdownMenuStates = ref(props.contactGroups?.map((contactGroup) => {
    let group = contactGroup.contacts.map(() => false);
    return group;
}));

// close all contact dropdown menus
const handleCloseAllMenus = () => {
    dropdownMenuStates.value = props.contactGroups?.map((contactGroup) => {
        let group = contactGroup.contacts.map(() => false);
        return group;
    });
};

</script>

<template>
    <div v-for="(group, groupIndex) in props.contactGroups" :key="groupIndex">
        <!--group title-->
        <Typography variant="heading-3" class="w-full px-5 pb-3 pt-5">
            {{ group.letter }}
        </Typography>
        <!--contacts-->
        <div v-for="(contact, index) in group.contacts" :key="index">
            <div class="entry cursor-pointer transform hover:scale-105 duration-300 transition-transform bg-white mb-4 rounded p-4 flex shadow-md border-l-4">
                <div class="flex-2">
                    <div class="w-12 h-12 relative">
                        <img class="w-12 h-12 rounded-full mx-auto" :src="fullPathPicture(contact?.avatar)?? avatar" alt="chat-user" />
                        <span class="absolute w-4 h-4 rounded-full right-0 bottom-0 border-2 border-white"
                              :class="[{'bg-green-500': contact?.active_status, 'bg-gray-400': !contact?.active_status}]"></span>
                    </div>
                </div>
                <div class="flex-1 px-2">
                    <div class="truncate w-32"><span class="text-gray-800">{{ getFullName(contact) }}</span></div>
                    <div><small class="text-gray-600">{{ getRolesContact(contact)}}</small></div>
                </div>
                <div class="flex-2 text-right">
                    <div><small class="text-gray-500"></small></div>
                </div>
            </div>
        </div>
    </div>
</template>

