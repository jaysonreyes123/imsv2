import './assets/main.css'
// Import Swiper styles
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'jsvectormap/dist/jsvectormap.css'
import "flatpickr/dist/flatpickr.css";
import "vue-good-table-next/dist/vue-good-table-next.css";
import './assets/scss/tailwind.scss';
import "vue-select/dist/vue-select.css";
import 'sweetalert2/dist/sweetalert2.min.css';

import { createApp,markRaw } from 'vue'
import App from './App.vue'
import router from './router'
import VueApexCharts from 'vue3-apexcharts'
import VueGoodTablePlugin from 'vue-good-table-next';
import vSelect from "vue-select";
import flatPickr from 'vue-flatpickr-component';
import VueSweetalert2 from 'vue-sweetalert2';
import Skeleton from "@/components/Skeleton/index.vue";

import { createPinia } from 'pinia';
import piniaPluginPersistedState from "pinia-plugin-persistedstate";
import axiosIns from "./library/axios.ts";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const pinia = createPinia()
const app = createApp(App)
import echo from './library/pusher.ts'
app.use(router)
app.use(VueApexCharts)
app.use(pinia)
app.use(VueSweetalert2);
app.use(VueGoodTablePlugin);
pinia.use(({ store }) => {
    store.router = markRaw(router);
    store.axios = axiosIns;
});
pinia.use(piniaPluginPersistedState)

app.component('flat-pickr',flatPickr)
app.component("v-select", vSelect);
app.component('Loading',Loading);
app.component('Skeleton',Skeleton);
app.config.globalProperties.$axios = axiosIns;
app.config.globalProperties.user_logout = echo.channel('user-logout');
app.config.globalProperties.notification_event = echo.channel('notification-event');
app.config.globalProperties.session_expired = echo.channel('session-expired-event');
app.mount('#app')

