import {_import} from '@/plugins/config'
export const baseRoute = [
    // 基本页面
    {path:'/',component:_import('Home/index'),children:[
        {path:'/',component:_import('Home/default')},
        {path:'/login',component:_import('Home/auth/login')},
        {path:'/register',component:_import('Home/auth/register')},
        {path:'/forget_password',component:_import('Home/auth/forget_password')},
        
        {path:'/stores',component:_import('Home/stores/list')},
        {path:'/store/:id',component:_import('Home/stores/info')},
        {path:'/store/join',component:_import('Home/stores/index')},
        {path:'/store/step_1',component:_import('Home/stores/step_1')},
        {path:'/store/step_2',component:_import('Home/stores/step_2')},
        {path:'/store/step_3',component:_import('Home/stores/step_3')},

        {path:'/s/:params?',component:_import('Home/goods/index')},
        {path:'/goods/:id',component:_import('Home/goods/info')},
        {path:'/carts',component:_import('Home/carts/index')},

        {path:'/integral',component:_import('Home/integral/index')},
        {path:'/integral/search/:params?',component:_import('Home/integral/goods')},
        {path:'/integral/goods/:id',component:_import('Home/integral/info')},
        {path:'/integral/order/:id/:buy_num',component:_import('Home/integral/orders/index')},

        {path:'/seckills',component:_import('Home/seckills/index')},
        {path:'/collectives/:params?',component:_import('Home/collectives/index')},


        // 个人中心
        {path:'/user',component:_import('Home/users/index'),children:[

            {path:'/user',component:_import('Home/users/default')},
            {path:'/user/address',component:_import('Home/users/address/index')},
            {path:'/user/address/form/:id?',component:_import('Home/users/address/form')},
            {path:'/user/comments',component:_import('Home/users/comments/index')},
            {path:'/user/comment/add/:id',component:_import('Home/users/orders/comment')},
            {path:'/user/info',component:_import('Home/users/info')},
            {path:'/user/safe',component:_import('Home/users/safe/index')},
            {path:'/user/safe/password',component:_import('Home/users/safe/password')},
            {path:'/user/safe/pay_password',component:_import('Home/users/safe/pay_password')},
            {path:'/user/safe/phone',component:_import('Home/users/safe/tel')},
            {path:'/user/safe/check',component:_import('Home/users/safe/check')},
            {path:'/user/oauth',component:_import('Home/users/oauth/index')},
            {path:'/user/cashes',component:_import('Home/users/cashes/index')},
            {path:'/user/favorites',component:_import('Home/users/favorites/index')},
            {path:'/user/money',component:_import('Home/users/money_logs/money')},
            {path:'/user/integral',component:_import('Home/users/money_logs/integral')},
            {path:'/user/frozen_money',component:_import('Home/users/money_logs/frozen_money')},
            {path:'/user/orders',component:_import('Home/users/orders/index'),name:'user_order'},
            {path:'/user/order/:id',component:_import('Home/users/orders/info')},
            {path:'/user/order/comment/:id',component:_import('Home/users/orders/comment')},
            {path:'/user/order/refund/:id',component:_import('Home/users/orders/refund')},
            {path:'/user/order/refund_form/:id',component:_import('Home/users/orders/refund_form')},
            {path:'/user/integral_order',component:_import('Home/users/integrals/index')},
            {path:'/user/coupons',component:_import('Home/users/coupons/index')},
            {path:'/user/distribution',component:_import('Home/users/distribution/index')},
            {path:'/user/distribution_users',component:_import('Home/users/distribution/user')},
            {path:'/user/distribution_logs',component:_import('Home/users/distribution/money')},
            {path:'/user/article/:name',component:_import('Home/users/articles/index')},
        ]},

        // 订单
        {path:'/order/before/:params',component:_import('Home/orders/before')},
        {path:'/order/pay/:params',component:_import('Home/orders/pay')},
        {path:'/order/success',component:_import('Home/orders/success')},
    ]},
    
    {path: '/Admin/login', component: _import('Admin/login')},
    {path: '/Seller/login', component: _import('Seller/login')},
    // {path: "/:catchAll(.*)",name: '404',component: _import('Error/404')},
    
]