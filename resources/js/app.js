import './bootstrap';
import { createApp } from "vue";
import HomePage from "../vue/HomePage.vue"
import settingTable from '../vue/settingTable.vue';
import 'vue3-toastify/dist/index.css';
import Vue3Toasity from 'vue3-toastify';
import { createPinia } from 'pinia';

const pinia = createPinia()

createApp({})
    .component('HomePage', HomePage)
    .component('settingPage', settingTable)
    .use(Vue3Toasity)
    .use(pinia)
    .mount("#app")

// console.log(HomePage)
// dropdown







