import './bootstrap';

import { createApp } from 'vue'
import App from './views/App.vue'
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import router from './router'
import axios from "axios";
import vSelect from 'vue-select'
import VueTheMask from 'vue-the-mask'
import Notifications from './views/components/Notifications.vue'

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/api/v1";

// Toastification options
const toastOptions = {
    timeout: 3000, // Default toast duration
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false,
};

const app = createApp(App);

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            if (error.response.status === 401) {
                localStorage.removeItem('authToken');
                localStorage.removeItem('userRole');
                delete axios.defaults.headers.common['Authorization'];
                window.location.href = '/admin/login';
            }
        }
        return Promise.reject(error);
    }
);

// Use the plugin
app.use(Toast, toastOptions);
app.component('Notifications', Notifications);
app.component('v-select', vSelect)
app.use(VueTheMask)

// Mount the app
app.use(router).mount('#admin-app');
