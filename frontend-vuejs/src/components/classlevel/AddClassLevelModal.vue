<script setup>
import {computed, reactive} from "vue";
import CardBoxModal from '@/components/card/CardBoxModal.vue';
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Select from "@/components/Select.vue";
import Button from "@/components/Button.vue";
import { BookmarkSquareIcon, BookOpenIcon, CloudIcon } from "@heroicons/vue/24/outline";
import { NIVEAU } from "@/utility/niveau";
const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const classLevelForm = reactive({
    niveau: "",
    classLevel: "",
    processing: false,
});

const emit = defineEmits(["update:modelValue", "cancel", "confirm", "save"]);

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

const resetClassLevel = () => {
    classLevelForm.classLevel = "";
    classLevelForm.niveau = "";
}

const saveClassLevel = () => {
    emit("save", {
        label: classLevelForm.classLevel,
        niveau: classLevelForm.niveau,
    });
    // resetClassLevel();
}
</script>

<template>
    <CardBoxModal
        v-model="value"
        title="Ajouter une classe"
        @cancel="resetClassLevel"
        hasCancel
    >
        <form @submit.prevent="saveClassLevel">
            <div class="grid gap-6">
                <div class="space-y-2">
                    <Label for="niveau" value="Niveau" />
                    <InputIconWrapper>
                        <template #icon>
                            <BookOpenIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Select
                            withIcon
                            id="niveau"
                            placeholder="Niveau"
                            class="block w-full"
                            v-model="classLevelForm.niveau"
                            target-id="id"
                            target-label="label"
                            :options="NIVEAU"
                        />
                    </InputIconWrapper>
                </div>

                <div class="space-y-2">
                    <Label for="classLevel" value="Classe" />
                    <InputIconWrapper>
                        <template #icon>
                            <CloudIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input
                            withIcon
                            id="classLevel"
                            type="text"
                            class="block w-full"
                            placeholder="Classe"
                            v-model="classLevelForm.classLevel"
                        />
                    </InputIconWrapper>
                </div>
            </div>
            <div class="py-5">
                <Button
                    type="submit"
                    class="justify-center w-full gap-2"
                    :disabled="classLevelForm.processing"
                    v-slot="{iconSizeClasses}"
                >
                    <BookmarkSquareIcon aria-hidden="true" :class="[iconSizeClasses]" />
                    <span>Enregistrer</span>
                </Button>
            </div>
        </form>
    </CardBoxModal>
</template>

<style scoped>

</style>
