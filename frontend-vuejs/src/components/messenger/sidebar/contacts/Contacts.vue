<script setup>
import {computed, ref} from "vue";

import { useMessengerStore, useAuthStore } from "@/stores";

import Loading2 from "@/components/loading/Loading2.vue";
import SidebarHeader from "@/components/messenger/sidebar/SidebarHeader.vue";
import ContactGroups from "@/components/messenger/sidebar/contacts/ContactGroups.vue";
import NoContacts from "@/components/messenger/emptyStates/NoContacts.vue";

const messengerStore = useMessengerStore();
const auth = useAuthStore();

// html element containing the contact groups
const contactContainer = ref(null);

// contact groups filtered by search text
const filteredContactGroups = computed(() => messengerStore.contactGroups(auth.user?.id));

</script>

<template>
    <div>
        <SidebarHeader>
            <!--title-->
            <template v-slot:title>Contacts</template>
        </SidebarHeader>

        <!--content-->
        <div ref="contactContainer" class="w-full h-full scroll-smooth scrollbar-hidden"
             style="overflow-x:visible; overflow-y: scroll;">
            <Loading2 v-if="messengerStore.status === 'loading'  || messengerStore.delayLoading" v-for="item in 5" />

            <ContactGroups
                v-else-if="messengerStore.status === 'success' && !messengerStore.delayLoading && (messengerStore.contacts)?.length > 0"
                :contactGroups="filteredContactGroups"
                :bottom-edge="contactContainer?.getBoundingClientRect().bottom" />

            <NoContacts v-else />
        </div>
    </div>
</template>
