<script setup>
import { computed } from "vue";
import { useMessengerStore } from "@/stores";

const messengerStore = useMessengerStore();

const typingUsers = computed(() => messengerStore.typingIndicator);
</script>

<template>
 <div v-if="typingUsers.length > 0">
     <div class="currently-typing-wrapper">
         <div>
             <p v-if="typingUsers.length === 1">{{ typingUsers[0].lastname }} est en train d'écrire</p>
             <p v-if="typingUsers.length === 2">{{ typingUsers[0].lastname }} et {{ typingUsers[1].lastname }} sont en train d'écrire</p>
             <p v-if="typingUsers.length > 2">Plusieurs personnes sont en train d'écrire</p>
         </div>
         <div class="container-dot">
             <span class="dot"></span>
             <span class="dot"></span>
             <span class="dot"></span>
         </div>
     </div>
 </div>
</template>

<style scoped>
    .currently-typing-wrapper {
        background: #f8f9fb;
        padding: 12px;
        display: inline-flex;
        width: 100%;
        flex-direction: row;
        align-content: center;
        justify-items: center;
        height: 50px;
    }
    .container-dot {
        padding-left: 15px;
        display: inline-block;
    }
    .dot {
        height: 10px;
        width: 10px;
        border-radius: 100%;
        display: inline-block;
        background: #b4b5b9;
        animation: 1.2s typing-dot ease-in-out infinite;
    }
    .dot:nth-of-type(2) {
        animation-delay: 0.15s;
    }
    .dot:nth-of-type(3) {
        animation-delay: 0.25s;
    }
    @keyframes typing-dot {
        15% {
            transform: translateY(-35%);
            opacity: 0.5;
        }
        30% {
            transform: translateY(0%);
            opacity: 1;
        }
    }
</style>
