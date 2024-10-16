<template>
    <component
        :is="type"
        name="drop-down"
        tag="div"
        appear
        :duration="duration"
    >
        <slot v-if="opened" />
    </component>
</template>

<script setup>
const props = defineProps({
    opened: {
        required: true,
        type: Boolean,
    },
    type: {
        default: 'transition',
        required: false,
        validator: (val) => val === 'transition' || val === 'transition-group',
    },
    duration: {
        default: 200,
        required: false,
        type: Number,
    },
});
</script>

<style scoped>
/*.drop-down-leave-to,
.drop-down-enter {
    transform: scaleY(0);
}

.drop-down-enter-to,
.drop-down-leave {
    transform: scaleY(1);
}

.drop-down-enter-to,
.drop-down-enter-active {
    transform-origin: top;
    animation: .2s drop-down-anim;
}

.drop-down-leave-to,
.drop-down-leave-active {
    transform-origin: top;
    animation: .2s drop-down-anim reverse;
}

@keyframes drop-down-anim {
    0% {
        transform: scaleY(0);
        opacity: .3;
    }
    100% {
        transform: scaleY(1);
        opacity: 1;
    }
}*/
</style>

