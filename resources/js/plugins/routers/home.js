export default [
    // 首页
    {path: '/',name: 'home_index',component: () => import('@/views/Home/index')},

    

    // 所有父视图
    {path: '/store/join/index',name: 'home_base_view',component: () => import('@/views/Home/default'),children:[

        // 登录注册
        {path: '/user/login',name: 'home_user_login',component: () => import('@/views/Home/auth/login')}, // 登录
        {path: '/user/register',name: 'home_user_register',component: () => import('@/views/Home/auth/register')}, // 注册
        {path: '/user/forget_password',name: 'home_user_forget_password',component: () => import('@/views/Home/auth/forget_password')}, // 忘记密码

        // 商家入驻
        {path: '/store/join/index',name: 'home_store_join_default',component: () => import('@/views/Home/store/join/default')}, // 入驻页面
        {path: '/store/join/step_1',name: 'home_store_join_step_1',component: () => import('@/views/Home/store/join/step_1')}, // 入驻协议
        {path: '/store/join/step_2',name: 'home_store_join_step_2',component: () => import('@/views/Home/store/join/step_2')}, // 资料填写
        {path: '/store/join/step_3',name: 'home_store_join_step_3',component: () => import('@/views/Home/store/join/step_3')}, // 等待审核

        // 商品
        {path: '/goods/:id',name: 'home_goods_info',component: () => import('@/views/Home/goods/info')}, // 详情
        {path: '/s/:params?',name: 'home_goods_list',component: () => import('@/views/Home/goods/list')}, // 列表

        // 购买
        {path: '/order/create_order/:params',name: 'home_create_order',component: () => import('@/views/Home/orders/index')}, // 创建订单
        {path: '/order/order_pay/:params',name: 'home_order_pay',component: () => import('@/views/Home/orders/order_pay')}, // 支付订单
        {path: '/order/pay_success',name: 'home_pay_success',component: () => import('@/views/Home/orders/pay_success')}, // 支付成功

        // 购物车
        {path: '/cart',name: 'home_pay_success',component: () => import('@/views/Home/carts/index')}, 

        // 店铺列表
        {path: '/store',name: 'home_store',component: () => import('@/views/Home/store/index')}, 
        {path: '/store/:id',name: 'home_store_info',component: () => import('@/views/Home/store/info')}, 

        // 积分商城
        {path: '/integral/index',name: 'home_integral',component: () => import('@/views/Home/integral/index')}, // 积分商城首页
        {path: '/integral/goods/:id',name: 'home_integral_goods_info',component: () => import('@/views/Home/integral/info')},  // 积分商品详情
        {path: '/integral/search/:params?',name: 'home_integral_goods_list',component: () => import('@/views/Home/integral/goods')},  // 积分商品列表
        {path: '/integral/order/:id/:num',name: 'home_integral_order',component: () => import('@/views/Home/integral/order/index')},  // 创建订单

        // 拼团页面
        {path: '/collective/:params',name: 'home_collective',component: () => import('@/views/Home/collectives/index')}, // 积分商城首页

        // 秒杀页面
        {path: '/seckill',name: 'home_seckill',component: () => import('@/views/Home/seckills/index')}, // 秒杀首页

        // 用户中心
        {path: '/user',name: 'home_user',component: () => import('@/views/Home/users/index'),children:[
            {path: '/user',name: 'home_user_default',component: () => import('@/views/Home/users/default')}, // 用户中心首页
            {path: '/user/address',name: 'home_user_address',component: () => import('@/views/Home/users/address/index')}, // 用户收货地址列表
            {path: '/user/address/form/:id?',name: 'home_user_address_form',component: () => import('@/views/Home/users/address/form')}, // 用户收货地址编辑
            {path: '/user/user_info',name: 'home_user_user_info',component: () => import('@/views/Home/users/user_info')}, // 用户资料编辑
            {path: '/user/safe',name: 'home_user_safe',component: () => import('@/views/Home/users/safe/index')}, // 用户安全
            {path: '/user/safe/edit_password',name: 'home_user_safe_edit_password',component: () => import('@/views/Home/users/safe/edit_password')}, // 用户安全
            {path: '/user/safe/edit_pay_password',name: 'home_user_safe_edit_pay_password',component: () => import('@/views/Home/users/safe/edit_pay_password')}, // 支付密码
            {path: '/user/safe/edit_phone',name: 'home_user_safe_edit_phone',component: () => import('@/views/Home/users/safe/edit_phone')}, // 手机修改
            {path: '/user/safe/edit_check',name: 'home_user_safe_edit_check',component: () => import('@/views/Home/users/safe/edit_check')}, // 身份认证
            {path: '/user/oauth',name: 'home_user_oauth',component: () => import('@/views/Home/users/oauth/index')}, // 用户账户绑定
            {path: '/user/order',name: 'home_user_order',component: () => import('@/views/Home/users/order/index')}, // 用户订单
            {path: '/user/order/:id',name: 'home_user_order_info',component: () => import('@/views/Home/users/order/info')}, // 用户订单详情
            {path: '/user/comment/add/:id',name: 'home_user_order_comment',component: () => import('@/views/Home/users/order/comment')}, // 用户添加评论

            // 积分订单
            {path: '/user/integral_order',name: 'home_user_integral_order',component: () => import('@/views/Home/users/integral_order/index')}, // 用户订单

            // 申请售后
            {path: '/user/refund/:id',name: 'home_user_refund',component: () => import('@/views/Home/users/refund/index')}, // 申请售后
            {path: '/user/refund/form/:id',name: 'home_user_refund_form',component: () => import('@/views/Home/users/refund/form')}, // 售后信息查看

            {path:"/user/order_comments",name:"home_user_comments",component:()=>import("@/views/Home/users/comment/index")},  // 评论列表
            {path:"/user/order_comments/form/:id",name:"home_user_comments_form",component:()=>import("@/views/Home/users/comment/form")}, // 评论修改

            {path: '/user/favorite',name: 'home_user_favorite',component: () => import('@/views/Home/users/favorite/index')}, // 用户收藏
            {path: '/user/follows',name: 'home_user_follows',component: () => import('@/views/Home/users/favorite/follows')}, // 用户关注店铺
            {path: '/user/money',name: 'home_user_money',component: () => import('@/views/Home/users/money_log/money')}, // 用户资金日志
            {path: '/user/frozen_money',name: 'home_user_frozen_money',component: () => import('@/views/Home/users/money_log/frozen_money')}, // 用户冻结资金日志
            {path: '/user/integral',name: 'home_user_integral',component: () => import('@/views/Home/users/money_log/integral')}, // 用户积分日志

            // 提现
            {path: '/user/cash',name: 'home_user_cash',component: () => import('@/views/Home/users/cash/index')}, // 提现列表
            {path: '/user/cash/form',name: 'home_user_cash_form',component: () => import('@/views/Home/users/cash/form')}, // 提现列表

            // 帮助中心
            {path: '/user/article/:ename',name: 'home_user_article_form',component: () => import('@/views/Home/users/article/form')}, // 提现列表

            // 分销日志
            {path:"/user/distribution_logs",name:"home_user_distribution_logs",component:()=>import("@/views/Home/users/distribution_log/index")}, 
            {path:"/user/distribution_users",name:"home_user_distribution_users",component:()=>import("@/views/Home/users/distribution_log/user")}, // 分销会员
            {path:"/user/distribution",name:"home_user_distribution_link",component:()=>import("@/views/Home/users/distribution_log/link")}, // 分销信息

            {path:"/user/coupon",name:"home_user_coupon",component:()=>import("@/views/Home/users/coupon/index")}, 
        ]}, 
    ]},

];