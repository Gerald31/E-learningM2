<script setup>
import { computed, ref, onMounted, inject, reactive } from "vue";
import { UsersIcon, EyeIcon } from "@heroicons/vue/24/outline";
import { useAuthStore, useTutoratStore } from "@/stores";
import { ROLE_STUDENT } from '@/utility/roles';
import { fullPathPicture, setIconType } from "@/utility/media";
import avatar from "@/assets/avatar.webp";
import Checkbox from '@/components/Checkbox.vue';
import Label from "@/components/Label.vue";
import Button from "@/components/Button.vue";
import router from "@/router";

const api = inject("$api");

const props = defineProps({
    tutorat: Number,
});

const tutoratId = ref(props.tutorat);
const tutoratDetail = ref(null);

const detailForm = reactive({
    souhait: false,
    accept: false,
});

const isDisabled = computed(() => detailForm.souhait && detailForm.accept)

onMounted(() => {
    getTutoratDetails(tutoratId.value);
});

const getTutoratDetails = async(tutoratId) => {
    api.tutoratApi.fetchTutoratById(tutoratId).then((tutorat) => {
        tutoratDetail.value = {
            ...tutorat,
            documents: fullPathPicture(tutorat?.documents),
            tutor: {
                ...tutorat?.tutor,
                avatar: fullPathPicture(tutorat?.tutor?.avatar)
            }
        };
    }).catch((errors) => {
        console.log('errors =>', errors)
    });
}

const goToPayment = async() => {
    await router.push({
        name: 'tutoratsPayment',
        params: {
            tutorat: tutoratDetail.value?.tutorat_id
        }
    })
}

const openInNewTab = (url) => {
    window.open(url, '_blank', 'noreferrer');
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <UsersIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight" v-text="tutoratDetail?.subject"></h1>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="p-2.5 w-full inline-block align-middle">
                    <div class="overflow-hidden border rounded-lg flex items-center justify-between p-6 bg-white my-6">
                        <div class="flex font-medium">
                            <div class="mr-1.5">Matiere : </div>
                            <div v-text="tutoratDetail?.subject_class?.subject_name"></div>
                        </div>
                        <div class="flex font-medium">
                            <div class="mr-1.5">Classe : </div>
                            <div v-text="tutoratDetail?.class_level?.label"></div>
                        </div>
                        <div class="flex font-medium">
                            <div class="mr-1.5">Le : </div>
                            <div>{{ $filters.datefr(tutoratDetail?.date) }} à {{ $filters.timefr(tutoratDetail?.time_start) }} - {{ $filters.timefr(tutoratDetail?.time_end) }}</div>
                        </div>
                        <div class="flex font-medium">
                            <div class="text-purple-500">{{ $filters.totalAmount(tutoratDetail?.price) }} €</div>
                        </div>
                    </div>

                    <div class="overflow-hidden border rounded-lg p-6 bg-white my-6">
                        <div class="flex font-medium">Contenu / Sommaire : </div>
                        <div class="flex font-normal mt-2" v-text="tutoratDetail?.description"></div>
                    </div>

                    <div class="overflow-hidden border rounded-lg flex justify-between p-6 bg-white my-6">
                        <div class="flex-col font-medium">
                            <div class="mr-1.5">Documents : </div>
                            <div v-if="tutoratDetail?.documents !== null" role="link" @click="openInNewTab(tutoratDetail?.documents)" class="cursor-pointer">
                                <img :src="setIconType(tutoratDetail?.documents)" width="80">
                            </div>
                            <div v-else> Aucun document deposés</div>
                        </div>
                        <div class="flex font-medium flex-col justify-center items-center">
                            <div class="text-center">
                                <img
                                    :src="tutoratDetail?.tutor?.avatar ?? avatar"
                                    class="rounded-full w-32 mb-4 mx-auto"
                                    alt="Avatar"
                                />
                                <h5 class="text-xl font-medium leading-tight mb-2" v-text="tutoratDetail?.tutor?.lastname +' '+ tutoratDetail?.tutor?.firstname"></h5>
                                <p class="text-gray-500">Professeur</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden border rounded-lg items-center justify-between p-6 bg-white my-6">
                        <div class="flex font-medium uppercase">
                            Voulez vous suivre ce tutorat ?
                        </div>
                        <div class="flex font-medium mt-4">
                            <div class="space-y-2">
                                <Label>
                                    <div class="flex items-center">
                                        <Checkbox
                                            name="publish"
                                            id="publish"
                                            v-model:checked="detailForm.accept"
                                        />
                                        <div class="ml-2">
                                            Je reconnais accepter de ce Tutorat avec {{ tutoratDetail?.tutor?.sexe }} {{ tutoratDetail?.tutor?.lastname }} conformément aux
                                            <router-link to="" target="_blank">Conditions générales de School@Home</router-link>
                                        </div>
                                    </div>
                                </Label>
                            </div>
                        </div>
                        <div class="flex font-medium mt-4">
                            <div class="space-y-2">
                                <Label>
                                    <div class="flex items-center">
                                        <Checkbox
                                            name="publish"
                                            id="publish"
                                            v-model:checked="detailForm.souhait"
                                        />
                                        <div class="ml-2">
                                            Je souhaite bénéficier immédiatement de la prestation à compter de la validation
                                            de ma commande et à ce titre je renonce expressément à exercer mon droit de rétractation
                                        </div>
                                    </div>
                                </Label>
                            </div>
                        </div>
                        <div class="flex font-medium mt-12">
                            <Button
                                type="submit"
                                class="justify-center w-full gap-2"
                                v-slot="{iconSizeClasses}"
                                :disabled="!isDisabled"
                                @click="goToPayment"
                            >
                                <span>S'inscrire</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>


