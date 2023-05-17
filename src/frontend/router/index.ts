import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import Calculator from '@/frontend/views/Calculator.vue'

const routes: RouteRecordRaw[] = [
    {name: 'Calculator', path: '/calculator', component: Calculator},
]

const router = createRouter({history: createWebHistory(), routes})

export default router
