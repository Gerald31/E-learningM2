<script setup>
const props = defineProps({
    showing: {
        type: Boolean,
        default: false,
    },
});

const { showing } = props;
</script>
<template>
    <div class="preloader overflow-hidden left-0 right-0 top-0 bottom-0 fixed text-white" :class="{'hidden': !showing}">
        <div class="jumper left-0 top-0 right-0 bottom-0 block absolute m-auto w-12 h-12">
            <div class="bg-white absolute opacity-0 w-12 h-12 rounded-full"></div>
            <div class="bg-white absolute opacity-0 w-12 h-12 rounded-full"></div>
            <div class="bg-white absolute opacity-0 w-12 h-12 rounded-full"></div>
        </div>
    </div>
</template>

<style scoped>

    @-webkit-keyframes jumper {
        0% {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        5% {
            opacity: 1;
        }
        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 0;
        }
    }
    @keyframes jumper {
        0% {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        5% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    .preloader {
        background-image: linear-gradient(135deg, #a759d1 0%, #8261ee 100%);
        z-index: 9999;
    }
    .preloader .jumper > div {
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation: jumper 1s 0s linear infinite;
        animation: jumper 1s 0s linear infinite;
    }
    .preloader .jumper > div:nth-child(2) {
        -webkit-animation-delay: 0.33333s;
        animation-delay: 0.33333s;
    }
    .preloader .jumper > div:nth-child(3) {
        -webkit-animation-delay: 0.66666s;
        animation-delay: 0.66666s;
    }
</style>
