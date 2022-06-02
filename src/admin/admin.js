import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router'
import calendar from './pages/calendar'
import settings from './pages/settings'
import BaseNotification from './components/BaseNotification';
import axios from 'axios';

// axios
// axios.defaults({
//     baseUrl: 
// })

import App from './App.vue';
const routes = [
    {
        path: '/', 
        name: 'calendar',
        component: calendar
    },
    {
        path: '/settings',
        name: 'settings',
        component: settings
    }
]
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHashHistory(),
    routes, // short for `routes: routes`
  })
const app = createApp(App);
app.use(router);
app.config.globalProperties.$axios = axios;
app.component('notify', BaseNotification);
app.mount('#wpv-admin-app');