import Vue from 'vue'
import Router from 'vue-router'
import admin from './routers/admin' // 后台路由
import seller from './routers/seller' // 商家路由
import home from './routers/home' // 前台路由

Vue.use(Router)

export default new Router({
    mode:'history',
    routes: [
        ...admin,
        ...seller,
        ...home,
        {path: '*',name: '404',component: () => import('@/views/Error/404')},
    ]
})
