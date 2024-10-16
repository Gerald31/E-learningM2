<script setup>
import { computed, ref, onMounted, inject } from "vue";
import { UsersIcon, EyeIcon } from "@heroicons/vue/24/outline";
import { useAuthStore, useTutoratStore } from "@/stores";
import { ROLE_TUTOR } from '@/utility/roles';
import router from "@/router";

const tutoratStore = useTutoratStore();

const api = inject("$api");

const authStore = useAuthStore();

const currentUser = ref(null);

onMounted(async () => {
    currentUser.value = await authStore.getCurrentUser();
    await getMyTutorat();
});

const getMyTutorat =  () => {
    api.tutoratApi.fetchMyTutorat(currentUser.value?.id, ROLE_TUTOR).then((tutorats) => {
        tutoratStore.setTutorats(tutorats);
    }).catch((errors) => {
        console.log('errors =>', errors)
    });
};

const tutoratsAffecteds = computed(() => tutoratStore.tutoratsAffectedTutor);


const pushToChat = async (tutorat) => {
    await router.push({
        name: 'messengerTutor',
        params: {
            tutorat: tutorat.tutorat_id
        }
    })
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <UsersIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Tutorats Affectés
                </h1>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="flex justify-between py-3 pl-2">
                    <div class="relative max-w-xs">
                        <label for="search" class="sr-only"> Rechercher </label>
                        <input
                            type="text"
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
                        <table class="w-full divide-y divide-gray-200 table-fixed">
                            <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Niveau
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Matiere
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Objet
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Description
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                >
                                    Tarifs
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="tutoratsAffected in tutoratsAffecteds" @click="pushToChat(tutoratsAffected)" class="cursor-pointer">
                                <td
                                    class="px-2 py-4 text-sm text-gray-800 text-center"
                                >
                                    {{ $filters.datefr(tutoratsAffected.date) }} <br/>à <br/>{{ $filters.timefr(tutoratsAffected.time_start) }} - {{ $filters.timefr(tutoratsAffected.time_end) }}
                                </td>
                                <td class="py-3 pl-4">
                                    <div class="flex items-center h-5">
                                        {{ tutoratsAffected.class_level.label }}
                                    </div>
                                </td>
                                <td class="py-3 pl-4">
                                    <div class="flex items-center h-5">
                                        {{ tutoratsAffected.subject_class.subject_name }}
                                    </div>
                                </td>
                                <td class="py-3 pl-4">
                                    <div class="flex items-center h-5">
                                        {{ tutoratsAffected.subject }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-800 truncate"
                                >
                                    {{ tutoratsAffected.description }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                >
                                    {{ tutoratsAffected.price }} €
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>


