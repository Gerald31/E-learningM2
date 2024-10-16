<script setup>
import { computed } from "vue";
import {
    XIconModal,
} from "@/components/icons/outline";
import Button from "@/components/Button.vue";
import CardBox from "@/components/card/CardBox.vue";
import OverlayLayer from "@/components/card/OverlayLayer.vue";
import CardBoxComponentTitle from "@/components/card/CardBoxComponentTitle.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    button: {
        type: String,
        default: "info",
    },
    buttonLabel: {
        type: String,
        default: "Done",
    },
    hasCancel: {
        type: Boolean,
        default: false
    },
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue", "cancel", "confirm"]);

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
    value.value = false;
    emit(mode);
};

const confirm = () => confirmCancel("confirm");

const cancel = () => confirmCancel("cancel");

window.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && value.value) {
        cancel();
    }
});
</script>

<template>
    <OverlayLayer v-show="value" @overlay-click="cancel">
        <CardBox
            v-show="value"
            class="shadow-lg w-full h-full max-w-7xl md:h-auto z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal"
            is-modal
        >
            <CardBoxComponentTitle :title="title">
                <Button
                    v-if="hasCancel"
                    color="whiteDark"
                    iconOnly
                    pill
                    size="sm"
                    class="px-0 py-0"
                    @click.prevent="cancel"
                    v-slot="{ iconSizeClasses }"
                    variant="secondary"
                >
                    <XIconModal
                        aria-hidden="true"
                        :class="[iconSizeClasses]"
                    />
                </Button>
            </CardBoxComponentTitle>

            <div class="space-y-3">
                <slot />
            </div>
        </CardBox>
    </OverlayLayer>
</template>

