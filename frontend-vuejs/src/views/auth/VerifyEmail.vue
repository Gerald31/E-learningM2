<template>
  <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    Thanks for signing up! Before getting started, could you verify your email
    address by clicking on the link we just emailed to you? If you didn't
    receive the email, we will gladly send you another.
  </div>

  <div
    class="mb-4 text-sm font-medium text-green-600"
    v-if="verificationLinkSent"
  >
    A new verification link has been sent to the email address you provided
    during registration.
  </div>

  <div class="flex items-center justify-center mt-4">
    <template v-if="!hasEmailValidate">
      <form @submit.prevent="submit">
        <Button
          type="submit"
          :class="{ 'opacity-25': verifyEmailForm.processing }"
          :disabled="verifyEmailForm.processing"
          >Resend Verification Email</Button>
        </form>
      </template>
      <template v-else>
        <Button :to="{ name: 'Login' }">Login</Button>
    </template>
  </div>
</template>

<script setup>
import { inject, onMounted, reactive, ref } from "vue";
import Button from "@/components/Button.vue";
import { loadStripe } from '@stripe/stripe-js';
import { ROLE_TUTOR } from '@/utility/roles';
const STRIPE_KEY = import.meta.env.VITE_STRIPE_KEY;

const props = defineProps({
  status: String,
  token: String,
});

const api = inject("$api");


const stripe = ref(null);
const userVerified = ref(null);
const tokenBank = ref(null);
const tokenAccount = ref(null);
const hasError = ref(false);
const hasEmailValidate = ref(true);

onMounted(async() => {
    stripe.value = await loadStripe(STRIPE_KEY);
    await verifyUser();
})

const verifyUser = async() => {
    await api.authApi.verifyEmail(props.token).then(async(userVerify) => {
        userVerified.value = userVerify;
        if (userVerify.roles === ROLE_TUTOR) {
            tokenBank.value = await createTokenBank();
            if (tokenBank.value) {
                tokenAccount.value = await createTokenAccount();
                if (tokenAccount.value) {
                    await createCustomer();
                }
            }
        }
    }).catch((errors) => {
        console.log('errors =>', errors)
        hasEmailValidate.value = false;
    });
}

const createTokenBank = async() => {
    const { token, error } = await stripe.value
        .createToken('bank_account', {
            country: 'FR',
            currency: 'EUR',
            account_number: userVerified?.value?.banking_information?.iban,
            account_holder_name: userVerified?.value?.firstname,
            account_holder_type: 'individual',
        });

    if (error) {
        hasError.value = true;
    }
    return token.id;
}

const createTokenAccount = async() => {
    const { token, error } = await stripe.value
        .createToken('account', {
            business_type: 'individual',
            individual: {
                first_name: userVerified?.value?.firstname,
                last_name: userVerified?.value?.lastname,
                email: userVerified?.value?.email,
                address: {
                    line1: userVerified?.value?.address,
                    city: userVerified?.value?.city,
                    postal_code: userVerified?.value?.code_postal,
                },
                dob: {
                    day: 5,
                    month: 2,
                    year: 2007,
                },
                phone: userVerified?.value?.phone
            },
            tos_shown_and_accepted: true,
        });

    if (error) {
        hasError.value = true;
    }

    return token.id;
}

const createCustomer = async () => {
    await api.stripeApi.createCustomer({
        token_bank: tokenBank.value,
        token_account: tokenAccount.value,
        email: userVerified.value?.email
    }).then((result) => {
        console.log('result =>', result)
    }).catch((error) => {
        console.log('error =>', error)
    })
}

const verifyEmailForm = reactive({
  processing: false,
});

const verificationLinkSent = ref(false);

const submit = () => {
  verificationLinkSent.value = true;
};
</script>
