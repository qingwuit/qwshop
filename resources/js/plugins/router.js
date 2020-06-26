import Vue from 'vue'
import Router from 'vue-router'
import admin from './routers/admin' // 后台路由

Vue.use(Router)

export default new Router({
    mode:'history',
    routes: [
        ...admin,
        {path: '*',name: '404',component: () => import('@/views/Error/404')},
    ]
})
