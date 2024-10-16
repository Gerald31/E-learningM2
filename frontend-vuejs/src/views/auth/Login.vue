<template>
  <form @submit.prevent="login">
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
            placeholder="votre adresse e-mail"
            v-model="loginForm.email"
            required
            autofocus
            autocomplete="username"
          />
        </InputIconWrapper>
      </div>

      <!-- Password input -->
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
            class="block w-full"
            placeholder="mot de passe"
            v-model="loginForm.password"
            required
            autocomplete="current-password"
          />
        </InputIconWrapper>
      </div>

      <!-- Remember me -->
      <div class="flex items-center justify-between">
        <label class="flex items-center">
        </label>

        <router-link
          :to="{ name: 'ForgotPassword' }"
          class="text-sm text-blue-500 hover:underline"
          >Mot de passe oublié ?</router-link
        >
      </div>

      <!-- Login button -->
      <div>
        <Button
          type="submit"
          class="justify-center w-full gap-2"
          :disabled="loginForm.processing"
          v-slot="{ iconSizeClasses }"
        >
          <ArrowLeftOnRectangleIcon aria-hidden="true" :class="iconSizeClasses" />
          <span>Se connecter</span>
        </Button>
      </div>

      <!-- Register link -->
      <p class="text-sm text-gray-600 dark:text-gray-400">
          Vous n'avez pas de compte ?
        <router-link
          :to="{ name: 'Register' }"
          class="text-blue-500 hover:underline"
          >Inscrivez-vous</router-link
        >
      </p>
    </div>
  </form>
</template>

<script setup>
import { inject, reactive } from "vue";
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Checkbox from "@/components/Checkbox.vue";
import Button from "@/components/Button.vue";
import { EnvelopeIcon, LockClosedIcon, ArrowLeftOnRectangleIcon } from "@heroicons/vue/24/outline";
import { useAuthStore } from "@/stores";
import router from "@/router";
import { redirectRouteName } from "@/utility/roles";
import { errorToast } from "@/toast";
const api = inject("$api");

const loginForm = reactive({
  email: "",
  password: "",
  remember: false,
  processing: false,
});

const login = () => {
  const { returnUrl, setUser } = useAuthStore();
  api.authApi.loginUser({
      email: loginForm.email,
      password: loginForm.password,
    }).then((response) => {
      setUser(response);
      // redirect to previous url or default to home page
      router.push(returnUrl || { name: redirectRouteName(response.roles) });
  }).catch((error) => {
      errorToast({
          text: error.data.message,
      });
  });
};
</script>
