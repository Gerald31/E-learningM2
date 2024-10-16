<script setup>
import { computed, reactive } from "vue";
import CardBoxModal from '@/components/card/CardBoxModal.vue';
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Button from "@/components/Button.vue";
import EditorText from "@/components/EditorText.vue";
import Checkbox from '@/components/Checkbox.vue';
import DropOrBrowserFile from '@/components/file-uploader/DropOrBrowserFile.vue';
import FileViewer from '@/components/file-uploader/FileViewer.vue';
import { BookmarkSquareIcon, BookOpenIcon, CloudIcon } from "@heroicons/vue/24/outline";
const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const magForm = reactive({
    title: "",
    subTitle: "",
    content: "",
    picture: "",
    enable: false,
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

const resetMag = () => {
    magForm.title = null;
    magForm.subTitle = null;
    magForm.content = null;
    magForm.picture = [];
    magForm.publish = false;
}

const onFileChanged = ($event) => {
    magForm.picture = $event;
}

const saveMag = () => {
    emit("save", {
        title: magForm.title,
        subTitle: magForm.subTitle,
        contenu: magForm.content,
        picture: magForm.picture.length > 0 ? magForm.picture[0] : null,
        enable: magForm.publish,
    });
    // resetMag();
}
</script>

<template>
    <CardBoxModal
        v-model="value"
        title="Rediger un article"
        @cancel="resetMag"
        hasCancel
    >
        <form @submit.prevent="saveMag">
            <div class="grid gap-6">
                <div class="space-y-2">
                    <Label for="magTitle" value="Titre" />
                    <InputIconWrapper>
                        <template #icon>
                            <CloudIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input
                            withIcon
                            id="magTitle"
                            type="text"
                            class="block w-full"
                            placeholder="Ajouter un titre d'un article"
                            v-model="magForm.title"
                        />
                    </InputIconWrapper>
                </div>
                <div class="space-y-2">
                    <Label for="subMagTitle" value="Sous-Titre" />
                    <InputIconWrapper>
                        <template #icon>
                            <CloudIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input
                            withIcon
                            id="subMagTitle"
                            type="text"
                            class="block w-full"
                            placeholder="Ajouter un sous-titre d'un article"
                            v-model="magForm.subTitle"
                        />
                    </InputIconWrapper>
                </div>
                <div class="space-y-2">
                    <Label  value="Contenu" />
                    <InputIconWrapper type="editor">
                        <EditorText v-model="magForm.content"/>
                    </InputIconWrapper>
                </div>
                <div class="space-y-2">
                    <Label for="magPicture" value="Ajouter une fichier" />
                    <InputIconWrapper>
                        <FileViewer
                            v-if="magForm.picture.length > 0"
                            title="Ajouter une pièce justificative"
                            :files="magForm.picture"
                            @change="onFileChanged"
                            accept="image/*"
                        />
                        <template v-else>
                            <DropOrBrowserFile @change="onFileChanged" accept="image/*" />
                        </template>
                    </InputIconWrapper>
                </div>
                <div class="space-y-2">
                    <Label for="publish">
                        <div class="flex items-center">
                            <div class="mr-2">
                                Publier
                            </div>
                            <Checkbox
                                name="publish"
                                id="publish"
                                v-model:checked="magForm.publish"
                            />
                        </div>
                    </Label>
                </div>
            </div>
            <div class="py-5">
                <Button
                    type="submit"
                    class="justify-center w-full gap-2"
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

