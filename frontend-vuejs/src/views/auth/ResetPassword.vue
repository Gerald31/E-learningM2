<template>
  <form @submit.prevent="submit">
    <div class="grid gap-4">
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
            placeholder="Entrez votre adresse e-mail"
            class="block w-full"
            v-model="resetPasswordForm.email"
          />
        </InputIconWrapper>
      </div>

      <div class="space-y-2">
        <Label for="password" value="Mot de passe" />
        <InputIconWrapper>
          <template #icon>
            <LockClosedIcon aria-hidden="true" class="w-5 h-5" />
          </template>
          <Input
            withIcon
            id="password"
            type="password"
            placeholder="Votre mot de passe"
            class="block w-full"
            v-model="resetPasswordForm.password"
          />
        </InputIconWrapper>
      </div>

      <div class="space-y-2">
        <Label for="password_confirmation" value="Confirmation mot de passe" />
        <InputIconWrapper>
          <template #icon>
            <LockClosedIcon aria-hidden="true" class="w-5 h-5" />
          </template>
          <Input
            withIcon
            id="password_confirmation"
            type="password"
            placeholder="Confirmation mot de passe"
            class="block w-full"
            v-model="resetPasswordForm.password_confirmation"
          />
        </InputIconWrapper>
      </div>

      <div>
        <Button
          type="submit"
          class="justify-center w-full"
          :disabled="resetPasswordForm.processing"
          >Réinitialiser le mot de passe</Button
        >
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
import { EnvelopeIcon, LockClosedIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
  email: String,
  token: String,
});

const resetPasswordForm = reactive({
  token: "",
  email: "",
  password: "",
  password_confirmation: "",
  processing: false,
});

const submit = () => {
  //
};
</script>
