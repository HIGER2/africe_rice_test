import './bootstrap';
import { createApp } from "vue";
import HomePage from "../vue/HomePage.vue"
import 'vue3-toastify/dist/index.css';
import Vue3Toasity from 'vue3-toastify';

createApp({})
    .component('HomePage', HomePage)
    .use(Vue3Toasity)
    .mount("#app")

// console.log(HomePage)
// dropdown







