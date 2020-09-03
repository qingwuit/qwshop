export default [
    // 首页
    {path: '/',name: 'home_index',component: () => import('@/views/Home/index')},

    

    // 所有父视图
    {path: '/store/join/index',name: 'home_base_view',component: () => import('@/views/Home/default'),children:[
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
    ]},

];