<script setup>
import {inject, onBeforeMount, onMounted, reactive, ref} from "vue";
import { loadStripe } from "@stripe/stripe-js";
import router from "@/router";
import { fullPathPicture } from "@/utility/media";
import { CreditCardIcon } from "@heroicons/vue/24/outline";
import avatar from "@/assets/avatar.webp";
import Checkbox from '@/components/Checkbox.vue';
import Label from "@/components/Label.vue";
import Button from "@/components/Button.vue";
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Input from "@/components/Input.vue";
import InputError from "@/components/InputError.vue";
import Loading4 from '@/components/loading/Loading4.vue';

const STRIPE_KEY = import.meta.env.VITE_STRIPE_KEY;

const api = inject("$api");

const baseUrl = window.location.origin;

const props = defineProps({
    tutorat: Number,
});

const card = reactive({
    cardName: ''
})
const tutoratId = ref(props.tutorat);
const tutoratDetail = ref(null);

const stripe = ref({});
const cardNumber = ref({});
const cardExpiry = ref({});
const cardCVC = ref({});

const clientSecret = ref(null);

const stripeError = ref(null);
const cardNumberError = ref(null);
const cardExpiryError = ref(null);
const cardCVCError = ref(null);
const cardNameError = ref(null);

const cardNumberComplete = ref(false);
const cardExpiryComplete = ref(false);
const cardCVCComplete = ref(false);

const CLASSES = {
    base: 'rounded-md dark:bg-dark-eval-1 dark:text-gray-300 font-normal text-base h-10 rounded-lg text-gray-700 bg-gray-200 block w-full p-3 leading-8 transition-colors duration-200 ease-in-out'
}

const stripeLoaded = ref(false);

onBeforeMount(() => {
    stripeLoaded.value = true;
    const stripePromise = loadStripe(STRIPE_KEY)
    stripePromise.then((stripeResponse) => {
        stripe.value = stripeResponse
        const elements = stripeResponse.elements();
        cardNumber.value = elements.create('cardNumber', {
            classes: CLASSES
        });
        cardExpiry.value = elements.create('cardExpiry', {
            classes: CLASSES
        });
        cardCVC.value = elements.create('cardCvc', {
            classes: CLASSES
        });
        cardNumber.value.mount('#card-number');
        cardExpiry.value.mount('#card-expiry');
        cardCVC.value.mount('#card-cvc');
        listenForErrors();
        setTimeout(() => {
            stripeLoaded.value = false;
        }, 1000)
    })
})

onMounted(async() => {
    await getTutoratDetails(tutoratId.value);
});

const listenForErrors = () => {
    cardNumber.value.addEventListener('change', function (event) {
        toogleError(event);
        cardNumberError.value = null;
        cardNumberComplete.value = event.complete;
    })
    cardExpiry.value.addEventListener('change', function (event) {
        toogleError(event);
        cardExpiryError.value = null;
        cardExpiryComplete.value = event.complete;
    })
    cardCVC.value.addEventListener('change', function (event) {
        toogleError(event);
        cardCVCError.value = null;
        cardCVCComplete.value = event.complete;
    })
}

const toogleError = ($event) => {
    if ($event.error) {
        stripeError.value = $event.error.message;
    } else {
        stripeError.value = null;
    }
}

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
        createPaymentIntent(tutoratId);
    }).catch((errors) => {
        console.log('errors =>', errors)
    });
}

const createPaymentIntent = (tutoratId) => {
    api.stripeApi.createPaymentIntent(tutoratId, {}).then((payementIntent) => {
        clientSecret.value = payementIntent.client_secret;
    }).catch((errors) => {
        console.log('createPayementIntent errors =>', errors)
    })
}

const submitPayment = () => {
    stripeLoaded.value = true;
    clearCardErrors();
    let isValid = true;
    if (!cardNumberComplete) {
        isValid = false;
        cardNumberError.value = "Numéro de carte est un obligatoire";
    }
    if (!cardExpiryComplete) {
        isValid = false;
        cardExpiryError.value = "Date d'éxpiration est un obligatoire";
    }
    if (!cardCVCComplete) {
        isValid = false;
        cardCVCError.value = "Cryptogramme est un obligatoire";
    }
    if (card.cardName === '') {
        isValid = false;
        cardNameError.value = "Nom sur la carte est un obligatoire";
    }
    if (isValid) {
        updatePaymentIntent();
    } else {
        stripeLoaded.value = false;
    }
}

const updatePaymentIntent = async() => {
    const confirmPayment = await stripe.value.confirmCardPayment(clientSecret.value, {
        payment_method: {
            type: 'card',
            card: cardNumber.value,
            billing_details: {
                name: card.cardName
            }
        },
    });
    await stripePaymentMethodHandler(confirmPayment);
}

const stripePaymentMethodHandler = async (paymentMethodHandler) => {
    if (paymentMethodHandler.error) {
        stripeLoaded.value = false;
        // Show error in payment form
    } else {
        // Otherwise send paymentIntent.id to your server
        await api.stripeApi.paymentIntent({
            payment_intent_id: paymentMethodHandler.paymentIntent.id,
            return_url: routePaymentSuccess(),
            tutorat_id: tutoratId.value,
        }).then(async (paymentResponse) => {
            await handleServerResponse(paymentResponse);
        }).catch((errors) => {
            stripeLoaded.value = false;
            console.log('paymentIntent errors =>', errors)
        });
    }
}

const handleServerResponse = async (paymentResponse) => {
    if (paymentResponse.error) {
        stripeLoaded.value = false;
        // Show error from server on payment form
        console.log('handleServerResponse paymentResponse.error =>', paymentResponse.error)
    } else if (paymentResponse.requires_action) {
        // Use Stripe.js to handle the required next action
        const {
            error,
            paymentIntent
        } = await stripe.value.handleNextAction({
            clientSecret: paymentResponse.payment_intent_client_secret
        });

        if (error) {
            stripeLoaded.value = false;
            // Show error from Stripe.js in payment form
            console.log('paymentIntent handleNextAction error =>', error)
        } else {
            // Actions handled, show success message
            await api.tutoratApi.updateTutoratState({
                paymentIntentId: paymentIntent.id,
                tutoratId: tutoratId.value,
            }).then(async (result) => {
                if (result.success) {
                    await paymentSuccess();
                }
            })
        }
    } else {
        // No actions needed, show success message
        await paymentSuccess();
    }
}

const routePaymentSuccess = () => {
    const { href } = router.resolve({
        name: 'successPayment',
        params: {
            tutorat: tutoratId.value
        }
    });
    return baseUrl + href;
}

const paymentSuccess = async () => {
    await router.push({
        name: 'successPayment',
        params: {
            tutorat: tutoratId.value
        }
    })
}

const clearCardErrors = () => {
   stripeError.value = null;
   cardNumberError.value = null;
   cardExpiryError.value = null;
   cardCVCError.value = null;
   cardNameError.value = null;
}
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="mb-6 pt-6 flex items-center justify-between">
            <div class="flex items-center justify-start">
                <div class="mr-3 w-12 h-12 rounded-full dark:text-white bg-gray-50 dark:bg-slate-800">
                    <CreditCardIcon class="flex-shrink-0 w-full h-full p-2" aria-hidden="true" />
                </div>
                <h1 class="text-2xl leading-tight">
                    Paiement du Tutorat
                </h1>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="p-2.5 w-full inline-block align-middle">
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-start-2 col-span-4 overflow-hidden border rounded-lg flex items-center justify-center p-6 bg-white">
                            <div class="flex font-medium">
                                <div class="text-purple-500">Paiement de : {{ $filters.totalAmount(tutoratDetail?.price) }} €</div>
                            </div>
                        </div>
                        <div class="col-start-1 col-span-3 overflow-hidden border rounded-lg flex-col items-center p-6 bg-white">
                            <div class="text-center">
                                <img
                                    :src="tutoratDetail?.tutor?.avatar ?? avatar"
                                    class="rounded-full w-32 mb-4 mx-auto"
                                    alt="Avatar"
                                />
                                <h5 class="text-xl font-medium leading-tight mb-2" v-text="tutoratDetail?.tutor?.lastname +' '+ tutoratDetail?.tutor?.firstname"></h5>
                                <p class="text-gray-500">Professeur</p>
                            </div>
                            <div class="flex font-medium uppercase my-4">Tutorat</div>
                            <div class="text-center ml-10">
                                <div class="flex">
                                    <div class="mr-1.5 font-medium">Matiere : </div>
                                    <div class="font-normal" v-text="tutoratDetail?.subject_class?.subject_name"></div>
                                </div>
                                <div class="flex">
                                    <div class="mr-1.5 font-medium">Classe : </div>
                                    <div class="font-normal" v-text="tutoratDetail?.class_level?.label"></div>
                                </div>
                                <div class="flex">
                                    <div class="mr-1.5 font-medium">Notion : </div>
                                    <div class="font-normal" v-text="tutoratDetail?.subject"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-3 col-end-7 overflow-hidden border rounded-lg items-center p-6 bg-white">
                            <div class="flex flex-col font-medium">
                                <Label for="cardName" value="Nom sur la carte" class="mb-3"/>
                                <InputIconWrapper>
                                    <template #icon>
                                        <CreditCardIcon aria-hidden="true" class="w-5 h-5" />
                                    </template>
                                    <Input
                                        withIcon
                                        id="cardName"
                                        type="text"
                                        class="block w-full"
                                        placeholder="Entrez votre nom sur la carte"
                                        v-model="card.cardName"
                                        :error="cardNameError"
                                    />
                                </InputIconWrapper>
                                <InputError v-if="cardNameError" :messages="[cardNameError]" />
                            </div>
                            <div class="flex flex-wrap -mx-2 mt-4">
                                <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="card-number" class="leading-7 text-sm text-gray-600">Numéro de carte</label>
                                        <div id="card-number" :class="{'border-red-500 focus:border-red-500': cardNumberError}"></div>
                                        <InputError v-if="cardNumberError" :messages="[cardNumberError]" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-2 mt-4">
                                <div class="p-2 w-1/2">
                                    <div class="relative">
                                        <label for="card-expiry" class="leading-7 text-sm text-gray-600">Date d'éxpiration</label>
                                        <div id="card-expiry"></div>
                                        <InputError v-if="cardExpiryError" :messages="[cardExpiryError]" />
                                    </div>
                                </div>
                                <div class="p-2 w-1/2">
                                    <div class="relative">
                                        <label for="card-cvc" class="leading-7 text-sm text-gray-600">Cryptogramme</label>
                                        <div id="card-cvc"></div>
                                        <InputError v-if="cardCVCError" :messages="[cardCVCError]" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex font-normal">
                                <InputError v-if="stripeError" :messages="[stripeError]" />
                            </div>
                            <div class="flex font-medium mt-16">
                                <Button
                                    type="submit"
                                    class="justify-center w-full gap-2"
                                    v-slot="{iconSizeClasses}"
                                    @click="submitPayment"
                                >
                                    <span>Payer</span>
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Loading4 v-if="stripeLoaded" />
    </section>
</template>

<style scoped>

</style>
