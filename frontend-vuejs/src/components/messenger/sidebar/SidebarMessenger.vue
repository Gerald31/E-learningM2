<script setup>
import Contacts from "@/components/messenger/sidebar/contacts/Contacts.vue";
</script>

<template>
    <aside
    class="xs:w-full md:w-[290px] h-full xs:px-5 md:p-0 flex flex-col overflow-visible transition-all duration-500">
    <FadeTransition>
        <component :is="Contacts" class="h-full flex flex-col" />
    </FadeTransition>
    </aside>
</template>

<style scoped>

</style>
