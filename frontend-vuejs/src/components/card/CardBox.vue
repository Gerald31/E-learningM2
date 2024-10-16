<script setup>
import { computed, useSlots } from "vue";
import CardBoxComponentBody from "@/components/card/CardBoxComponentBody.vue";
import CardBoxComponentFooter from "@/components/card/CardBoxComponentFooter.vue";

const props = defineProps({
    rounded: {
        type: String,
        default: "rounded-2xl",
    },
    flex: {
        type: String,
        default: "flex-col",
    },
    hasComponentLayout: Boolean,
    hasTable: Boolean,
    isHoverable: Boolean,
    isModal: Boolean,
});

const slots = useSlots();

const hasFooterSlot = computed(() => slots.footer && !!slots.footer());

const componentClass = computed(() => {
    const base = [
        props.rounded,
        props.flex,
        props.isModal ? "bg-white dark:bg-gray-700" : "bg-white dark:bg-gray-500",
    ];

    if (props.isHoverable) {
        base.push("hover:shadow-lg transition-shadow duration-500");
    }

    return base;
});

</script>

<template>
    <component
        is="div"
        class="flex"
        :class="componentClass"
    >
        <slot v-if="hasComponentLayout" />
        <template v-else>
            <CardBoxComponentBody :no-padding="hasTable">
                <slot />
            </CardBoxComponentBody>
            <CardBoxComponentFooter v-if="hasFooterSlot">
                <slot name="footer" />
            </CardBoxComponentFooter>
        </template>
    </component>
</template>

