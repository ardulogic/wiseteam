import './assets/main.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { createNaiveUI } from './naive'
import i18n from './locales/i18n'

const app = createApp(App)
app.use(createNaiveUI())
app.use(createPinia())
app.use(router)
app.use(i18n)
app.mount('#app')


