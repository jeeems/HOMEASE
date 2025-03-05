import './bootstrap';
import { createApp } from 'vue'
import AdminDashboard from './components/AdminDashboard.vue'

const app = createApp({
    components: {
        'admin-dashboard': AdminDashboard
    }
})

app.mount('#admin-dashboard-app')
