<script setup xmlns="http://www.w3.org/1999/html">
import {computed, inject, onMounted, reactive, ref} from "vue";
import router from "@/router";
import InputIconWrapper from "@/components/InputIconWrapper.vue";
import InputError from "@/components/InputError.vue";
import Label from "@/components/Label.vue";
import Input from "@/components/Input.vue";
import Select from "@/components/Select.vue";
import Checkbox from "@/components/Checkbox.vue";
import Button from "@/components/Button.vue";
import {useClassLevelStore} from "@/stores";
import {errorToast} from "@/toast";
import {ROLE_TUTOR, rolesArray} from "@/utility/roles";
import { genderArray } from "@/utility/gender";
import {
    CameraIcon,
    LockClosedIcon,
    EnvelopeIcon,
    PhoneIcon,
    PlusIcon,
    UserPlusIcon,
    UserIcon,
    MapIcon,
    MapPinIcon,
    EnvelopeOpenIcon,
    UsersIcon,
    CheckBadgeIcon,
    CalendarIcon,
    UserGroupIcon,
    XMarkIcon
} from "@heroicons/vue/24/outline";
const api = inject("$api");
const registerForm = reactive({
  firstname: "",
  lastname: "",
  roles: "",
  email: "",
  avatar: "",
  city: "",
  code_postal: "",
  address: "",
  phone: "",
  gender: "",
  password: "",
  password_confirmation: "",
  date_of_birth: "",
  terms: false,
  processing: false,
  skills: [],
  banking: {
      iban: null,
      ibanDocument: null
  },
  activeMenu: 'info',
  previewAvatar: null
});

const errorsRegister = ref({});

onMounted(() => {
  getAllClassLevel();
  getAllSubjects();
  addLine();
});

const setActiveMenu = (menu) => {
    registerForm.activeMenu = menu;
}

const classLevelStore = useClassLevelStore();

const skills = computed(() => classLevelStore.skills);

const isSubmitActive = computed(() => registerForm.terms);

const roleSelected = computed(() => registerForm.roles)

const allClassLevels = computed(() => classLevelStore.classLevels);

const getAllClassLevel = async () => {
  try {
    const classLevels = await api.classLevelApi.getAllClassLevel();
    classLevelStore.setClassLevels(classLevels)
  } catch (error) {
    errorToast({
      text: error.message,
    });
  }
}

const getAllSubjects = async () => {
  try {
    const subjects = await api.classLevelApi.getAllSubject();
    classLevelStore.setSubjects(subjects)
  } catch (error) {
    errorToast({
      text: error.message,
    });
  }
}

const addLine = () => {
    registerForm.skills.push({
        classLevel:'',
        subject:'',
        subjects: []
    })
}
const deleteLine = (index) => {
    registerForm.skills.splice(index, 1);
}

const openDialogueFiles = () => {
    document.querySelector('#drag-avatar-file').click();
}

const onFileChanged = ($event) => {
    const target = $event.target
    if (target && target.files) {
        let reader = new FileReader
        reader.onload = e => {
           registerForm.previewAvatar = e.target.result;
        }
        reader.readAsDataURL(target.files[0])
        registerForm.avatar = target.files[0];
    }
}

const onFileChangeDocumentIban = ($event) => {
    const target = $event.target
    if (target && target.files) {
        registerForm.banking.ibanDocument = target.files[0];
    }
}

const prev = () => {
    const currentActive = registerForm.activeMenu;
    switch (currentActive) {
        case 'banking':
            setActiveMenu('competences');
            break;
        case 'competences':
            setActiveMenu('access');
            break;
        case 'access':
            setActiveMenu('info');
            break;
        default:
            setActiveMenu('info');
            break;
    }
}

const next = () => {
    const currentActive = registerForm.activeMenu;
    switch (currentActive) {
        case 'info':
            setActiveMenu('access');
            break;
        case 'access':
            setActiveMenu('competences');
            break;
        case 'competences':
            setActiveMenu('banking');
            break;
        default:
            setActiveMenu('info');
            break;
    }
}

const updateClassLevel = (index, classLevelId) => {
    registerForm.skills[index].classLevel = classLevelId;
    registerForm.skills[index].subjects = classLevelStore.getSubjectClassLevel(classLevelId);
}

const updateSubject = (index, subject) => {
    registerForm.skills[index].subject = subject;
}

const register = async () => {
    await api.authApi.signUpUser({
        firstname: registerForm.firstname,
        lastname: registerForm.lastname,
        roles: registerForm.roles,
        email: registerForm.email,
        avatar: registerForm.avatar,
        city: registerForm.city,
        code_postal: registerForm.code_postal,
        address: registerForm.address,
        phone: registerForm.phone,
        gender: registerForm.gender,
        password: registerForm.password,
        password_confirmation: registerForm.password_confirmation,
        date_of_birth: registerForm.date_of_birth,
        userSkill: registerForm.skills,
        bankingInformation: registerForm.banking
    }).then(async (result) => {
        await router.push({ name: 'Login' });
    }). catch((error) => {
        console.log(error.data.errors)
        errorsRegister.value = error.data.errors
    })
};
</script>
<template>
  <div class="grid grid-cols-5 gap-4 md:mx-auto">
    <div class="pt-10 space-y-6">
         <!--avatar-->
        <div
            class="w-32 h-32 block cursor-pointer bg-cover mx-auto mb-7 px-10 py-10 bg-center shadow-xl rounded-full items-center cursor-pointer"
            @click="openDialogueFiles"
            :style="{'background-image': `url(${registerForm.previewAvatar})`}"
        >
            <div v-if="!registerForm.previewAvatar">
                <CameraIcon aria-hidden="true" class="w-6 h-6 rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none transition duration-200 text-purple-500 md:mx-auto"/>
                <Label
                    for="drag-avatar-file"
                    value="Avatar" class="text-purple-500 text-xl md:mx-auto">
                </Label>
            </div>
        </div>
        <input
            type="file"
            id="drag-avatar-file"
            ref="files"
            accept="image/x-png,image/jpg,image/jpeg"
            hidden
            @change="onFileChanged"
        />
        <div>
            <InputError v-if="errorsRegister.avatar" :messages="errorsRegister.avatar" />
        </div>
         <!--menu-->
        <div class="">
           <div class="space-y-4 text-lg text-gray-500">
               <div
                   class="cursor-pointer"
                   @click="setActiveMenu('info')"
                   :class="{ 'text-purple-500': registerForm.activeMenu === 'info' }"
               >
                   Informations personnelles
               </div>
               <div
                   class="cursor-pointer active:text-purple-500"
                   :class="{ 'text-purple-500': registerForm.activeMenu === 'access'}"
                   @click="setActiveMenu('access')"
               >
                   Accès professionnels
               </div>
               <div
                   class="cursor-pointer active:text-purple-500"
                   :class="{ 'text-purple-500': registerForm.activeMenu === 'competences'}"
                   @click="setActiveMenu('competences')"
               >
                   Compétences
               </div>
               <div
                   v-if="roleSelected === ROLE_TUTOR"
                   class="cursor-pointer active:text-purple-500"
                   :class="{ 'text-purple-500': registerForm.activeMenu === 'banking'}"
                   @click="setActiveMenu('banking')"
               >
                   Information Bancaire
               </div>
           </div>
        </div>
    </div>
    <div class="col-span-4">
      <form @submit.prevent="register">
        <div class="-mx-2 my-6">
          <div v-if="registerForm.activeMenu === 'info'">
            <p class="text-center text-gray-600 dark:text-gray-400 text-2xl mb-2">Informations personnelles</p>
            <div class="w-full px-6 py-4 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-dark-eval-1 mt-3">
               <div class="grid gap-6 mb-3">
                  <div class="flex gap-4 items-center">
                    <!-- Firstname input -->
                    <div class="space-y-2 block w-full">
                        <Label for="firstname" value="Nom"/>
                        <InputIconWrapper>
                            <template #icon>
                                <UserIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                        <Input
                            withIcon
                            id="firstname"
                            type="text"
                            class="block w-full"
                            v-model="registerForm.firstname"
                        />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.firstname" :messages="errorsRegister.firstname" />
                    </div>

                    <!-- Lastname input -->
                    <div class="space-y-2 block w-full">
                        <Label for="lastname" value="Prénoms" />
                        <InputIconWrapper>
                            <template #icon>
                                <UserIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                        <Input
                            withIcon
                            id="lastname"
                            type="text"
                            class="block w-full"
                            v-model="registerForm.lastname"
                        />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.lastname" :messages="errorsRegister.lastname" />
                    </div>
                  </div>
                  <div class="flex gap-4 items-center">
                    <!-- Date de naissance input -->
                    <div class="space-y-2 block w-full">
                        <Label for="date_of_birth" value="Date de naissance" />
                        <InputIconWrapper>
                            <template #icon>
                                <CalendarIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                         <Input
                               withIcon
                               id="date_of_birth"
                               type="date"
                               class="block w-full"
                               v-model="registerForm.date_of_birth"
                         />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.date_of_birth" :messages="errorsRegister.date_of_birth" />
                    </div>
                    <!-- Sexe input -->
                    <div class="space-y-2 block w-full">
                        <Label for="gender" value="Genre" />
                        <InputIconWrapper>
                            <template #icon>
                                <UserGroupIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                           <Select
                               withIcon
                               id="gender"
                               class="block w-full"
                               v-model="registerForm.gender"
                               :options="genderArray"
                               target-id="value"
                               target-label="label"
                           />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.gender" :messages="errorsRegister.gender" />
                    </div>
                  </div>
                  <div class="flex gap-4 items-center">
                    <!-- Adress input -->
                    <div class="space-y-2 block w-full">
                        <Label for="address" value="Adresse" />
                        <InputIconWrapper>
                            <template #icon>
                                <MapPinIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                          <Input
                            withIcon
                            id="address"
                            type="text"
                            class="block w-full"
                            v-model="registerForm.address"
                          />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.address" :messages="errorsRegister.address" />
                    </div>
                    <!-- City input -->
                    <div class="space-y-2 block w-full">
                        <Label for="city" value="Ville" />
                        <InputIconWrapper>
                            <template #icon>
                                <MapIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                           <Input
                               withIcon
                               id="city"
                               type="text"
                               class="block w-full"
                               v-model="registerForm.city"
                           />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.city" :messages="errorsRegister.city" />
                    </div>
                  </div>
                  <div class="flex gap-4 items-center">
                    <!-- Code_postal input -->
                    <div class="space-y-2 block w-full">
                        <Label for="code_postal" value="Code postale" />
                        <InputIconWrapper>
                            <template #icon>
                                <EnvelopeOpenIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                          <Input
                            withIcon
                            id="code_postal"
                            type="text"
                            class="block w-full"
                            v-model="registerForm.code_postal"
                          />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.code_postal" :messages="errorsRegister.code_postal" />
                    </div>
                    <!-- Phone input -->
                    <div class="space-y-2 block w-full">
                        <Label for="phone" value="Numéro de téléphone" />
                        <InputIconWrapper>
                            <template #icon>
                                <PhoneIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                          <Input
                            withIcon
                            id="phone"
                            type="text"
                            class="block w-full"
                            v-model="registerForm.phone"
                          />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.phone" :messages="errorsRegister.phone" />
                    </div>
                  </div>
               </div>
            </div>
          </div>
          <div v-if="registerForm.activeMenu === 'access'">
            <p class="text-center text-gray-600 dark:text-gray-400 text-2xl">Accès professionnels</p>
            <div class="w-full px-6 py-4 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-dark-eval-1 mt-3">
                <div class="grid gap-6 -ml-2">
                    <!-- Email input -->
                    <div class="space-y-2">
                        <Label for="email" value="Email" />
                        <InputIconWrapper>
                            <template #icon>
                                <EnvelopeIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                          <Input
                            withIcon
                            id="email"
                            type="email"
                            class="block w-full"
                            v-model="registerForm.email"
                          />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.email" :messages="errorsRegister.email" />
                    </div>
                    <div class="flex gap-4 items-center">
                        <!-- Password input -->
                        <div class="space-y-2 block w-full">
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
                                v-model="registerForm.password"
                              />
                            </InputIconWrapper>
                            <InputError v-if="errorsRegister.password" :messages="errorsRegister.password" />
                        </div>
                        <!-- Password confirmation input -->
                        <div class="space-y-2 block w-full">
                            <Label for="password_confirmation" value="Confirmer le mot de passe" />
                            <InputIconWrapper>
                                <template #icon>
                                    <LockClosedIcon aria-hidden="true" class="w-5 h-5" />
                                </template>
                              <Input
                                withIcon
                                id="password_confirmation"
                                type="password"
                                class="block w-full"
                                v-model="registerForm.password_confirmation"
                              />
                            </InputIconWrapper>
                            <InputError v-if="errorsRegister.password_confirmation" :messages="errorsRegister.password_confirmation" />
                        </div>
                    </div>
                    <!-- roles input -->
                    <div class="space-y-2">
                        <Label for="roles" value="Roles" />
                        <InputIconWrapper>
                            <template #icon>
                                <CheckBadgeIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                          <Select
                            withIcon
                            id="roles"
                            class="block w-full"
                            v-model="registerForm.roles"
                            :options="rolesArray"
                            target-id="id"
                            target-label="label"
                          />
                        </InputIconWrapper>
                        <InputError v-if="errorsRegister.roles" :messages="errorsRegister.roles" />
                    </div>
                </div>
            </div>
          </div>
          <div v-if="registerForm.activeMenu === 'competences'">
            <p class="text-center text-gray-600 dark:text-gray-400 text-2xl">Compétences</p>
            <div class="container w-full px-6 py-4 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-dark-eval-1 mt-3">
                <div v-for="(skill, index) in registerForm.skills" :key="index" class="grid gap-6 mb-3">
                    <div class="flex gap-3 items-center w-full relative">
                        <!-- classLevel input -->
                        <div class="space-y-2 block w-full">
                            <Label value="Classe" />
                            <InputIconWrapper>
                                <select
                                    :class="[
                                    'py-2 rounded-md',
                                    'dark:bg-dark-eval-1 dark:text-gray-300',
                                    'font-normal text-base h-10 rounded-lg p-2 text-gray-700 bg-gray-200',
                                    'px-4',
                                    'w-full'
                                  ]"
                                    :value="skill.classLevel"
                                    @change="updateClassLevel(index, $event.target.value)"
                                    ref="select"
                                >
                                    <option v-for="option in allClassLevels" :value="option.class_level_id">{{ option.label }}</option>
                                </select>
                            </InputIconWrapper>
                            <InputError v-if="errorsRegister[`userSkill.${index}.classLevel`]" :messages="errorsRegister[`userSkill.${index}.classLevel`]" />
                        </div>
                        <!-- subject input -->
                        <div v-if="roleSelected === ROLE_TUTOR" class="space-y-2 block w-full">
                            <Label value="Matière" />
                            <InputIconWrapper>
                                    <select
                                        :class="[
                                        'py-2 rounded-md',
                                        'dark:bg-dark-eval-1 dark:text-gray-300',
                                        'font-normal text-base h-10 rounded-lg p-2 text-gray-700 bg-gray-200',
                                        'px-4',
                                        'w-full'
                                      ]"
                                        :value="skill.subject"
                                        @change="updateSubject(index, $event.target.value)"
                                        ref="select"
                                    >
                                        <option v-for="option in skill.subjects" :value="option.subject_id">{{ option.subject_name }}</option>
                                    </select>
                                </InputIconWrapper>
                            <InputError v-if="errorsRegister[`userSkill.${index}.subject`]" :messages="errorsRegister[`userSkill.${index}.subject`]" />
                        </div>
                        <!-- deleteLine input -->
                        <div class="absolute top-5 -right-7">
                            <Button
                                class="w-10 w-10 bg-transparent text-red-600 hover:bg-transparent px-0 py-0"
                                @click="deleteLine(index)"
                                v-slot="{ iconSizeClasses }"
                            >
                                <XMarkIcon  aria-hidden="true" :class="iconSizeClasses"/>
                            </Button>
                        </div>
                    </div>
                </div>
                <!-- addLine input -->
                <div class="space-y-2 my-2">
                    <Button
                        class="justify-center w-full gap-2"
                        v-slot="{ iconSizeClasses }"
                        @click="addLine"
                    >
                        <PlusIcon aria-hidden="true" :class="iconSizeClasses" />
                        <span>Ajouter</span>
                    </Button>
                </div>
            </div>
          </div>
          <div v-if="registerForm.activeMenu === 'banking'">
                <p class="text-center text-gray-600 dark:text-gray-400 text-2xl mb-2">Information Bancaire</p>
                <div class="w-full px-6 py-4 overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-dark-eval-1 mt-3">
                    <div class="grid gap-6 mb-3">
                            <!-- Iban input -->
                            <div class="space-y-2">
                                <Label for="iban" value="Iban"/>
                                <InputIconWrapper>
                                    <template #icon>
                                        <UserIcon aria-hidden="true" class="w-5 h-5" />
                                    </template>
                                    <Input
                                        withIcon
                                        id="iban"
                                        type="text"
                                        placeholder="Votre iban"
                                        class="block w-full"
                                        v-model="registerForm.banking.iban"
                                        autofocus
                                        autocomplete=""
                                    />
                                </InputIconWrapper>
                                <InputError v-if="errorsRegister['bankingInformation.iban']" :messages="errorsRegister['bankingInformation.iban']" />
                            </div>
                            <!--Upload document-->
                            <div class="flex justify-center">
                                <div class="space-y-2 w-full block">
                                    <Label for="document" value="Document" />
                                    <InputIconWrapper>
                                        <input
                                            withIcon
                                            class="form-control
                                            block
                                            w-full
                                            px-3
                                            py-1.5
                                            text-base
                                            font-normal
                                            text-gray-700
                                            bg-white bg-clip-padding
                                            border border-solid border-black-500
                                            rounded-md
                                            transition
                                            ease-in-out
                                            m-0
                                            file:bg-gradient-to-b file:from-purple-500 file:to-gray-500
                                            file:rounded-full file:text-white
                                            file:shadow-xl
                                            focus:text-gray-700 focus:bg-white focus:border-gray-600 focus:outline-none
                                            py-2 rounded-md
                                            dark:bg-dark-eval-1 dark:text-gray-300
                                            font-normal text-base h-10 rounded-lg p-2 text-gray-700 bg-gray-200"
                                            type="file"
                                            id="document"
                                            @change="onFileChangeDocumentIban($event)"
                                            accept="image/x-png,image/jpeg,image/jpeg,application/pdf"
                                        />
                                    </InputIconWrapper>
                                    <InputError v-if="errorsRegister['bankingInformation.ibanDocument']" :messages="errorsRegister['bankingInformation.ibanDocument']" />
                                </div>
                            </div>
                    </div>
                </div>
            </div>
          <div v-if="roleSelected === ROLE_TUTOR && registerForm.activeMenu === 'banking' || roleSelected !== ROLE_TUTOR && registerForm.activeMenu === 'competences'" class="mt-12">
            <!-- Terms -->
            <div class="mb-5">
                <Label for="terms">
                    <div class="flex items-center">
                        <Checkbox
                            name="terms"
                            id="terms"
                            v-model:checked="registerForm.terms"
                        />
                        <div class="ml-2">
                            Je suis d'accord avec les
                            <a
                                target="_blank"
                                href="#"
                                class="text-sm text-blue-600 underline hover:text-blue-900"
                            >conditions de service
                            </a>
                            et de
                            <a
                                target="_blank"
                                href="#"
                                class="text-sm text-blue-600 underline hover:text-blue-900"
                            >politique de confidentialité
                            </a>
                        </div>
                    </div>
                </Label>
            </div>
            <!-- Register button -->
            <div class="mb-5 flex justify-center items-center">
                <div
                    type="button"
                    @click="prev"
                    class="inline-block px-3.5 py-2.5 border-2 border-purple-600 text-purple-600 font-medium text-xs leading-tight uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out cursor-pointer mr-3"
                >
                    Précedent
                </div>
                <Button
                    type="submit"
                    class="justify-center gap-2"
                    :disabled="!isSubmitActive"
                    v-slot="{ iconSizeClasses }"
                >
                    <UserPlusIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>S'inscrire</span>
                </Button>
            </div>
            <!-- Login link -->
            <p class="text-sm text-gray-600 dark:text-gray-400">
                    Déjà un compte?
                    <router-link
                        :to="{ name: 'Login' }"
                        class="text-blue-500 hover:underline"
                    >Se connecter</router-link
                    >
                </p>
          </div>
          <div v-else class="mt-12 flex justify-center">
              <div
                  v-if="registerForm.activeMenu !=='info'"
                  type="button"
                  @click="prev"
                  class="inline-block  px-3.5 py-2.5 border-2 border-purple-600 text-purple-600 font-medium text-xs leading-tight uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out cursor-pointer mr-3"
              >
                  Précedent
              </div>
              <div
                  v-if="roleSelected === ROLE_TUTOR && registerForm.activeMenu !== 'banking' || roleSelected !== ROLE_TUTOR && registerForm.activeMenu !== 'competences'"
                  @click="next"
                  class="inline-block  px-3.5 py-2.5 bg-purple-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out cursor-pointer"
              >
                  Suivant
              </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>


