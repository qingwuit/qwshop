import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    mode:'history',
    routes: [
        // 登录
        {path: '/Admin/login',name: 'admin_login',component: () => import('@/views/Admin/login.vue')},

        // 后台界面
        {path:"/Admin/index",name:"admin_index",component:()=>import("@/views/Admin/index"),children:[

            // {path:"/Admin/index",name:"admin_default",component:()=>import("./views/Admin/default")}, // 打开默认页面

        ]},

    ]
})
