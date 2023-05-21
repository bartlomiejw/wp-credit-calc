import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
import installShared from '@/shared'
import './assets/index.scss'

const appCalculators = document.querySelectorAll('.calculator')
appCalculators.forEach((calculator) => {
    const app = createApp(App)
    app.use(router)
    // delay mount so we can load configuration
    setTimeout(() => {
        installShared(app, 'vue_wp_plugin_config_frontend_'+calculator.id.replace('-', '_'))
        app.mount('#'+ calculator.id)
    }, 200)
})
