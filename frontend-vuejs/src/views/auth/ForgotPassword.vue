<template>
  <form @submit.prevent="submit">
      <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
          Entrez l'adresse e-mail vérifiée de votre compte utilisateur et nous vous enverrons un lien de réinitialisation de mot de passe.
      </div>
    <div class="grid gap-6">
      <!-- Email input -->
      <div class="space-y-2">
        <Label for="email" value="Adresse e-mail" />
        <InputIconWrapper>
          <template #icon>
            <EnvelopeIcon aria-hidden="true" class="w-5 h-5" />
          </template>
          <Input
            withIcon
            id="email"
            type="email"
            class="block w-full"
            placeholder="Entrez votre adresse e-mail"
            v-model="forgotPasswordForm.email"
          />
        </InputIconWrapper>
      </div>

      <!-- Submit button -->
      <div>
        <Button
          type="submit"
          class="justify-center w-full gap-2"
          :disabled="forgotPasswordForm.processing"
          v-slot="{ iconSizeClasses }"
        >
          <PaperAirplaneIcon aria-hidden="true" :class="iconSizeClasses" />
          <span class="text-sm">Envoyer un e-mail de réinitialisation du mot de passe</span>
        </Button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { reactive } from "vue";
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Button from "@/components/Button.vue";
import Input from "@/components/Input.vue";
import Label from "@/components/Label.vue";
import { EnvelopeIcon, PaperAirplaneIcon } from "@heroicons/vue/24/outline";

const forgotPasswordForm = reactive({
  email: "",
  processing: false,
});

const submit = () => {
  //
};
</script>
