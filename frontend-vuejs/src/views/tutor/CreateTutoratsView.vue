<script setup>
import {computed, inject, onMounted, reactive, ref} from 'vue';
import { DocumentDuplicateIcon, BookmarkSquareIcon } from  "@heroicons/vue/24/outline";
import BaseCard from "@/components/BaseCard.vue";
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Button from "@/components/Button.vue";
import SelectClassLevel from '@/components/classlevel/SelectClassLevel.vue';
import FileViewer from '@/components/file-uploader/FileViewer.vue';
import DropOrBrowserFile from '@/components/file-uploader/DropOrBrowserFile.vue';
import { useAuthStore, useClassLevelStore, useTutoratStore } from '@/stores';
import router from "@/router";
import Textarea from "@/components/Textarea.vue";
import Checkbox from '@/components/Checkbox.vue';

const api = inject("$api");

const classLevelStore = useClassLevelStore();
const tutoratStore = useTutoratStore();
const authStore = useAuthStore();

const errors = ref([]);
const mySubjects = ref([]);

const isDisabled = computed(() => tutoratForm.accept);

const currentUser = computed(() => authStore.user);

const classLevels = computed(() => classLevelStore.getMyClassLevels);

const tutoratForm = reactive({
    classLevel: null,
    subject: null,
    title: null,
    file: [],
    date: null,
    time_start: null,
    time_end: null,
    price: null,
    description: null,
    accept: false
});

console.log(classLevels)

const saveTutorat = async() => {
    const tutorat = {
        class_level_id: tutoratForm.classLevel,
        subject_id: tutoratForm.subject,
        description: tutoratForm.description,
        documents: tutoratForm.file.length > 0 ? tutoratForm.file[0] : null,
        subject: tutoratForm.title,
        date: tutoratForm.date,
        time_start: tutoratForm.time_start,
        time_end: tutoratForm.time_end,
        price: tutoratForm.price,
        prof_id: currentUser.value?.id
    };
    api.tutoratApi.save(tutorat).then((tutoratSaved) => {
        tutoratStore.addTutorat(tutoratSaved);
        router.push({name: 'tutoratsDispoTutor'});
    }).catch((responseError) => {
        if (responseError.status === 422) {
            errors.value = responseError.data.errors;
        } else if (responseError.status === 500) {
            errors.value = [responseError.data.message];
        } else {
            errors.value = ['Erreur lors du téléversement du fichier.'];
        }
    });
}
const setClassLevelFilter = ($event) => {
    tutoratForm.classLevel = $event;
    mySubjects.value = classLevelStore.getMySubjectClassLevel($event);
}

const setSubjectFilter = ($event) => {
    tutoratForm.subject = $event;
}

const onChangeFile = ($event) => {
    tutoratForm.file = $event;
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <DocumentDuplicateIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Nouveau Tutorat
                </h1>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <BaseCard>
                    <form @submit.prevent="saveTutorat">
                        <div class="py-4">
                            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 py-2">
                                <div class="space-y-2">
                                    <Label value="Niveau concerné" />
                                    <InputIconWrapper type="select">
                                        <SelectClassLevel
                                            class="mr2"
                                            :items="classLevels"
                                            item-id-key="class_level_id"
                                            item-label-key="label"
                                            :selected-id="tutoratForm.classLevel"
                                            @update:selected-id="setClassLevelFilter($event);" />
                                    </InputIconWrapper>
                                </div>
                                <div class="space-y-2">
                                    <Label value="Matière concernée" />
                                    <InputIconWrapper type="select">
                                        <SelectClassLevel
                                            class="mr2"
                                            :items="mySubjects"
                                            item-id-key="subject_id"
                                            item-label-key="subject_name"
                                            :selected-id="tutoratForm.subject"
                                            @update:selected-id="setSubjectFilter($event);" />
                                    </InputIconWrapper>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-8 py-2">
                                <div class="space-y-2">
                                    <Label for="title" value="Précisez la notion" />
                                    <InputIconWrapper>
                                        <Input
                                            id="title"
                                            type="text"
                                            class="block w-full"
                                            placeholder="Titre du chapitre"
                                            v-model="tutoratForm.title"
                                        />
                                    </InputIconWrapper>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 py-2">
                                <div class="space-y-2">
                                    <Label value="Joindre des document" />
                                    <span><i>( cours, traveaux notés, exercices ...) max 150mO</i></span>
                                    <InputIconWrapper>
                                        <FileViewer
                                            v-if="tutoratForm.file.length > 0"
                                            title="Ajouter une pièce justificative"
                                            :files="tutoratForm.file"
                                            @change="onChangeFile"
                                            accept="application/pdf,application/octet-stream,image/*,application/doc,application/docx"
                                        />
                                        <template v-else>
                                            <DropOrBrowserFile
                                                @change="onChangeFile"
                                                accept="application/pdf,application/octet-stream,image/*,application/doc,application/docx"
                                            />
                                        </template>
                                    </InputIconWrapper>
                                </div>
                                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                                    <div class="space-y-2">
                                        <Label for="date" value="Date :" />
                                        <InputIconWrapper>
                                            <Input
                                                id="date"
                                                type="date"
                                                class="block w-full"
                                                v-model="tutoratForm.date"
                                            />
                                        </InputIconWrapper>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="time_start" value="Heure debut:" />
                                        <InputIconWrapper>
                                            <Input
                                                id="time_start"
                                                type="time"
                                                class="block w-full"
                                                v-model="tutoratForm.time_start"
                                            />
                                        </InputIconWrapper>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="time_end" value="Heure de fin:" />
                                        <InputIconWrapper>
                                            <Input
                                                id="time_end"
                                                type="time"
                                                class="block w-full"
                                                v-model="tutoratForm.time_end"
                                            />
                                        </InputIconWrapper>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 py-2">
                                <div class="space-y-2">
                                    <Label for="price" value="Indiquer le tarif d'accès à ce tutorat" />
                                    <InputIconWrapper type="money">
                                        <Input
                                            id="price"
                                            type="number"
                                            step=".01"
                                            class="block w-full"
                                            placeholder="Prix de ce tutorat"
                                            v-model="tutoratForm.price"
                                        />
                                    </InputIconWrapper>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-8 py-2">
                                <div class="space-y-2">
                                    <Label for="comment" value="Indiquez le sommaire, les objectifs visés, les thèmes abordés et les pré-requis nécessaires (maximum 350 mots)" />
                                    <InputIconWrapper>
                                        <Textarea
                                            id="comment"
                                            class="block w-full py-2 rounded-md dark:bg-dark-eval-1 dark:text-gray-300 px-4"
                                            v-model="tutoratForm.description"
                                        >
                                        </Textarea>
                                    </InputIconWrapper>
                                </div>
                            </div>
                        </div>
                        <div class="flex font-medium mt-4">
                            <div class="space-y-2">
                                <Label>
                                    <div class="flex items-center">
                                        <Checkbox
                                            name="publish"
                                            id="publish"
                                            v-model:checked="tutoratForm.accept"
                                        />
                                        <div class="ml-2">
                                            En cochant cette case, vous reconnaissez avoir pris connaissance et accepter les
                                            <router-link to="" target="_blank">Conditions Générales de Vente</router-link> et les <router-link to="" target="_blank">Condition Générales d’Utilisation</router-link>
                                        </div>
                                    </div>
                                </Label>
                            </div>
                        </div>
                        <div class="py-5 grid grid-cols-1 gap-8 md:grid-cols-3">
                            <div></div>
                            <div></div>
                            <div>
                                <Button
                                    type="submit"
                                    class="justify-center gap-2"
                                    v-slot="{iconSizeClasses}"
                                    :disabled="!isDisabled"
                                >
                                    <BookmarkSquareIcon aria-hidden="true" :class="[iconSizeClasses]" />
                                    <span>Enregistrer</span>
                                </Button>
                            </div>
                        </div>
                    </form>
                </BaseCard>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
