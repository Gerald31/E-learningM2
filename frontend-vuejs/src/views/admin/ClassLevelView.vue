<script setup>
import { inject, onMounted, computed, ref } from "vue";
import CardClassLevel from "@/components/classlevel/CardClass.vue";
import CardBox from '@/components/card/CardBox.vue';
import { BriefcaseIcon, DocumentPlusIcon } from "@heroicons/vue/24/outline";
import { useClassLevelStore } from '@/stores';
import { errorToast } from '@/toast';
import AddClassLevelModal from '@/components/classlevel/AddClassLevelModal.vue';
import SubjectModal from '@/components/classlevel/SubjectModal.vue';

const api = inject("$api");

const classLevelStore = useClassLevelStore();
const modalAddClassLevel = ref(false);
const modalSubject = ref(false);

onMounted(async () => {
    await getAllClassLevel();
    await getAllSubjects();
});

const classLevels = computed(() => classLevelStore.getclassLevels);

const getAllClassLevel = async () => {
    try {
        const classLevels = await api.classLevelApi.getAllClassLevel();
        classLevelStore.setClassLevels(classLevels);
    } catch (error) {
        errorToast({
            text: error.data.errors.message,
        });
    }
};

const getAllSubjects = async () => {
    try {
        const subjects = await api.classLevelApi.getAllSubject();
        classLevelStore.setSubjects(subjects);
    } catch (error) {
        errorToast({
            text: error.data.errors.message,
        });
    }
};

const addClassLevel = (data) => {
    api.classLevelApi.saveClassLevel(data).then((classLevel) => {
        classLevelStore.addClassLevel(classLevel);
        modalAddClassLevel.value = false;
    }).catch((error) => {
        if (error.status === 422) {
            if (error?.data?.errors?.label[0]) {
                errorToast({
                    text: error.data.errors.label[0],
                });
            }
            if (error?.data?.errors?.niveau[0]) {
                errorToast({
                    text: error.data.errors.niveau[0],
                });
            }
        } else {
            errorToast({
                text: error.data.errors.message,
            });
        }
    });
}

const addSubject =  (data) => {
    api.classLevelApi.saveSubject(data).then((classLevel) => {
        classLevelStore.addSubject(classLevel);
    }).catch((error) => {
        if (error.status === 422) {
            errorToast({
                text: error.data.errors.subjectName[0],
            });
        } else {
            errorToast({
                text: error.data.errors.message,
            });
        }
    });
}

const deleteSubject = async (subject) => {
    api.classLevelApi.deleteSubject(subject).then((subject) => {
        classLevelStore.deleteSubject(subject);
    }).catch((error) => {
        errorToast({
            text: error.data.errors.message,
        });
    });
}

const openModalAddSubject = (classLevelSelected) => {
    classLevelStore.setClassLevelSelected(classLevelSelected);
    modalSubject.value = true;
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <AddClassLevelModal v-model="modalAddClassLevel" @save="addClassLevel" />
        <SubjectModal
            v-model="modalSubject"
            @save="addSubject"
            @delete="deleteSubject"
        />
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <BriefcaseIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Classes
                </h1>
            </div>
            <div class="inline-flex justify-center items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 cursor-pointer rounded p-1 w-12 h-12" @click="modalAddClassLevel = true">
                <DocumentPlusIcon class="flex-shrink-0" aria-hidden="true" />
            </div>
        </div>
        <CardBox>
            <CardClassLevel :class-level="classLevels" @open-modal="openModalAddSubject" />
        </CardBox>
    </section>
</template>

<style scoped>

</style>
