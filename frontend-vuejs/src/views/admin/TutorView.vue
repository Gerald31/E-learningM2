<script setup>
import { computed, inject, onMounted } from "vue";
import Button from "@/components/Button.vue";
import { UserIcon, EyeIcon } from "@heroicons/vue/24/outline";
import avatar from "@/assets/avatar.webp";
import { useAuthStore, useUserStore } from "@/stores";
import { ROLE_TUTOR } from "@/utility/roles";
import { getFullName } from "@/utility/chat";

const authStore = useAuthStore();
const userStore = useUserStore();

const api = inject('$api');

const currentUser = computed(() => authStore.user);

const allTutors = computed(() => userStore.allTutors);

onMounted(() => {
    fetchTutors();
})

const fetchTutors = () => {
    api.userApi.fetch(ROLE_TUTOR).then((tutors) => {
        userStore.setTutors(tutors);
    }).catch((error) => {
        console.log('error =>', error)
    })
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <UserIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Tuteurs
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

                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold"
                                >
                                    Nom
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold"
                                >
                                    Téléphone
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-right"
                                >
                                    Statut
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-bold text-right"
                                >
                                    Voir
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="tutor in allTutors">
                                <td scope="row"
                                    class="flex items-center px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                >
                                    <img class="w-10 h-10 rounded-full" :src="tutor?.avatar?? avatar" alt="Jese image">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ getFullName(tutor) }}</div>
                                        <div class="font-normal text-gray-500">{{ tutor.email }}</div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                >
                                    {{ tutor.phone }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm font-medium text-right"
                                >
                                    <span v-if="tutor.status" class="text-xs inline-block w-20 py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-green-500 text-white rounded-full">Activé</span>
                                    <span v-else class="text-xs inline-block w-20 py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-yellow-500 text-white rounded-full">En attente</span>
                                </td>
                                <td
                                    class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap"
                                >
                                    <Button
                                        iconOnly
                                        variant="secondary"
                                        v-slot="{ iconSizeClasses }"
                                        srText="Voir"
                                    >
                                        <EyeIcon aria-hidden="true" :class="iconSizeClasses" />
                                    </Button>
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
