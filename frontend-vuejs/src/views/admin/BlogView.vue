<script setup>
import {inject, computed, ref, onMounted} from "vue";
import Button from "@/components/Button.vue";
import { DocumentDuplicateIcon, EyeIcon, TrashIcon, DocumentPlusIcon } from "@heroicons/vue/24/outline";
import CardBox from '@/components/card/CardBox.vue';
import ModalMag from '@/components/mag/ModalMag.vue';
import { useMagStore } from '@/stores';
import { errorToast } from '@/toast';

const api = inject("$api");

const showModalMag = ref(false);

const magStore = useMagStore();

onMounted(async () => {
    await getAllMag();
});

const mags = computed(() => magStore.getMags);

const getAllMag = () => {
   api.magApi.getAllMags().then((mags) => {
       magStore.setMags(mags);
   }).catch((error) => {
       errorToast({
           text: error.data.errors.message,
       });
   });
};

const onChangeCheckBox = (magId) => {
    magStore.updatePublish(magId);
    api.magApi.updateStateMag(magId);
}

const addMag = (mag) => {
    api.magApi.saveMag(mag).then((magSaved) => {
        magStore.addMag(magSaved);
        showModalMag.value = false;
    }).catch((error) => {
        const errorResponse = error.data.errors;
        if (error.status === 422) {
            for (const errorKey in errorResponse) {
                errorToast({
                    text: errorResponse[errorKey][0],
                });
            }
        } else {
            errorToast({
                text: errorResponse.message,
            });
        }
    });
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
                    Blog
                </h1>
            </div>
            <div class="inline-flex justify-center items-center whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 cursor-pointer rounded p-1 w-12 h-12" @click="showModalMag = true">
                <DocumentPlusIcon class="flex-shrink-0" aria-hidden="true" />
            </div>
        </div>
        <CardBox>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="flex justify-between py-3 pl-2">
                        <div class="relative max-w-xs">
                            <label for="search" class="sr-only"> Rechercher </label>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="block w-full p-3 pl-10 text-sm border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                                placeholder="Search..."
                            />
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-1.5 w-full inline-block align-middle">
                        <div class="overflow-hidden border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-auto bg-white">
                                <thead class="bg-gray-50">
                                    <tr class="flex flex-row">
                                        <th scope="col" class="px-3 py-3 basis-1/12">
                                            <div class="flex items-center h-5">
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-xs font-bold text-left text-gray-500 uppercase basis-3/12">
                                            Titre
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-xs font-bold text-left text-gray-500 uppercase basis-4/12">
                                            Extrait
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-xs font-bold text-left text-gray-500 uppercase basis-1/12">
                                            Date de dépôt
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-xs font-bold text-right text-gray-500 uppercase basis-1/12">
                                            Statut
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-xs font-bold text-right text-gray-500 uppercase basis-2/12">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="mag in mags" :key="mag.mag_id" class="flex flex-row h-28">
                                        <td class="px-3 py-3 basis-1/12">
                                            <div class="flex items-center">
                                                <img class="ease-in transition-all duration-300 w-32 h-12" :src="mag.path" alt="picture-blog"/>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-800 basis-3/12">
                                            {{ mag.title }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-800 basis-4/12 whitespace-normal truncate h-24 max-h-full" v-html="mag.subTitle">
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-800 basis-1/12">
                                            {{ $filters.datefr(mag.created_at) }}
                                        </td>
                                        <td class="px-3 py-3 text-sm font-medium text-right basis-1/12">
                                            <div class="flex justify-center">
                                                <label class="inline-flex relative items-center cursor-pointer">
                                                    <input type="checkbox" :value="mag.enable" class="sr-only peer" :checked="mag.enable" @change="onChangeCheckBox(mag.mag_id)">
                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-sm font-medium text-right whitespace-nowrap basis-2/12">
                                            <div>
                                                <Button
                                                    iconOnly
                                                    variant="secondary"
                                                    v-slot="{ iconSizeClasses }"
                                                    srText="Voir"
                                                >
                                                    <EyeIcon aria-hidden="true" :class="iconSizeClasses" />
                                                </Button>
                                                <Button
                                                    iconOnly
                                                    variant="secondary"
                                                    v-slot="{ iconSizeClasses }"
                                                    srText="Delete"
                                                >
                                                    <TrashIcon aria-hidden="true" :class="iconSizeClasses" />
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </CardBox>
        <ModalMag v-model="showModalMag" @save="addMag" />
    </section>
</template>

<style scoped>

</style>
