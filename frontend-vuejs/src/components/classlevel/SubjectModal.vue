<script setup>
import { computed, reactive } from "vue";
import CardBoxModal from '@/components/card/CardBoxModal.vue';
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Button from "@/components/Button.vue";
import { BookmarkSquareIcon, CloudIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { useClassLevelStore } from "@/stores";

const classLevelStore = useClassLevelStore();

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const subjectForm = reactive({
    subjectName: "",
    processing: false,
});

const classLevelSelected = computed(() => classLevelStore.classLevelSelected);

const subjects = computed(() => classLevelStore.getSubjectsByClassLevel);

const emit = defineEmits(["update:modelValue", "save", "delete"]);

const resetSubject = () => {
    subjectForm.subjectName = "";
}

const cancelSubject = () => {
    resetSubject();
    classLevelStore.setClassLevelSelected(null);
}

const saveSubject = () => {
    emit("save", {
        subjectName: subjectForm.subjectName,
        classLevelId: classLevelSelected.value.class_level_id,
    });
    resetSubject();
}

const deleteSubject = (subject) => {
    emit("delete", subject);
}
</script>

<template>
    <CardBoxModal
        v-model="value"
        :title="`Classe ${classLevelSelected?.label}`"
        @cancel="cancelSubject"
        hasCancel
    >
        <section class="container mx-auto lg:max-w-6xl">
            <div class="mb-6 pt-6 flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <h1 class="text-2xl leading-tight">
                        Liste des matières
                    </h1>
                </div>
            </div>

            <div v-for="subject in subjects" :key="subject.subject_id" class="flex flex-row py-2">
                <div class="space-y-2 basis-3/4 pr-0.5">
                    {{ subject.subject_name }}
                </div>
                <div class="space-y-2 basis-1/4 pr-0.5">
                    <Button
                        iconOnly
                        class="justify-center w-full gap-2"
                        v-slot="{iconSizeClasses}"
                        @click="deleteSubject(subject)"
                    >
                        <TrashIcon aria-hidden="true" :class="[iconSizeClasses]" />
                    </Button>
                </div>
            </div>

            <form @submit.prevent="saveSubject">
                <div class="flex flex-row">
                    <div class="space-y-2 basis-3/4 pr-0.5">
                        <InputIconWrapper>
                            <template #icon>
                                <CloudIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                            <Input
                                withIcon
                                id="subject"
                                type="text"
                                class="block w-full"
                                placeholder="Matiere"
                                v-model="subjectForm.subjectName"
                            />
                        </InputIconWrapper>
                    </div>
                    <div class="basis-1/4 pl-0.5">
                        <Button
                            iconOnly
                            type="submit"
                            class="justify-center w-full gap-2"
                            :disabled="subjectForm.processing"
                            v-slot="{iconSizeClasses}"
                        >
                            <BookmarkSquareIcon aria-hidden="true" :class="[iconSizeClasses]" />
                        </Button>
                    </div>
                </div>
            </form>
        </section>
    </CardBoxModal>
</template>

<style scoped>

</style>
