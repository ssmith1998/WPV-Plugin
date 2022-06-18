import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router'
import calendar from './pages/calendar'
import settings from './pages/settings'
import NewBookings from './components/NewBookings';
import BaseNotification from './components/BaseNotification';
import axios from 'axios';
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/dist/style.css';

// axios
const instance = axios.create({
    baseURL: 'http://localhost:8000/wp-json/wpv/v1/'
  });

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
app.use(SetupCalendar, {})
app.config.globalProperties.$axios = instance;
app.component('notify', BaseNotification);
app.component('Calendar', Calendar);
app.component('date-picker', DatePicker);
app.component('new-bookings', NewBookings);
app.mount('#wpv-admin-app');