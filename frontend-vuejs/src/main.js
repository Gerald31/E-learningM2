import { createApp } from "vue";
import { createPinia } from "pinia";

import moment from 'moment';

import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import Toast from "vue-toastification";

/* import the QuillEditor */
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import App from "@/App.vue";
import router from "@/router";
import Api from "@/services";

import "@/assets/css/main.css";

/* import specific icons */
import { faTwitter } from '@fortawesome/free-brands-svg-icons';
import { faFacebook } from '@fortawesome/free-brands-svg-icons';
import { faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { faUserSecret } from '@fortawesome/free-solid-svg-icons';

/* import custom directive */
import vClickOutside from "click-outside-vue3";
import clickOutside from '@/directives/vue-click-outside';
import scrollBottom from '@/directives/scrollBottom';
import filtersPlugin from "@/plugins/filters";
import pusherPlugin from "@/plugins/pusher";

/* add icons to the library */
library.add(faTwitter, faUserSecret, faLinkedin, faFacebook);

const app = createApp(App);

/* Init Pinia */
app.use(createPinia());

app.provide("$api", new Api());

/* Add custom directives*/
app.directive('clickOutside', clickOutside);
app.directive('scrollBottom', scrollBottom);

// eslint-disable-next-line vue/multi-word-component-names
app.component("Datepicker", Datepicker);

app.component('font-awesome-icon', FontAwesomeIcon);

app.component('QuillEditor', QuillEditor);

app.use(router);
app.use(vClickOutside);

app.use(pusherPlugin);
app.provide("$pusher", app.config.globalProperties.$pusher);

app.use(filtersPlugin);

app.use(Toast, {
  hideProgressBar: true,
  closeOnClick: false,
  closeButton: false,
  icon: false,
  timeout: false,
  transition: "Vue-Toastification__fade",
});

app.mount("#app");
