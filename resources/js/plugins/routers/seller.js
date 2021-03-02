export default [
    // 登录
    {path: '/Seller/login',name: 'seller_login',component: () => import('@/views/Seller/login')},

    // 后台界面
    {path:"/Seller/index",name:"seller_index",component:()=>import("@/views/Seller/index"),children:[

        {path:"/Seller/index",name:"seller_default",component:()=>import("@/views/Seller/default")}, // 打开默认页面

        // 配置中心
        {path:"/Seller/configs",name:"seller_configs",component:()=>import("@/views/Seller/configs/index")},

        // 商品管理
        {path:"/Seller/goods",name:"seller_goods",component:()=>import("@/views/Seller/goods/index")}, // 商品列表
        {path:"/Seller/goods/chose_class/:id?",name:"seller_goods_chose_class",component:()=>import("@/views/Seller/goods/chose_class")}, // 商品分类选择
        {path:"/Seller/goods/form/:id?",name:"seller_goods_form",component:()=>import("@/views/Seller/goods/form")}, // 商品编辑

        // 属性规格
        {path:"/Seller/goods_attrs",name:"seller_goods_attrs",component:()=>import("@/views/Seller/goods_attrs/index")}, // 商品列表
        {path:"/Seller/goods_attrs/form/:id?",name:"seller_goods_attrs_form",component:()=>import("@/views/Seller/goods_attrs/form")}, // 商品编辑

        // 订单管理
        {path:"/Seller/orders",name:"seller_orders",component:()=>import("@/views/Seller/orders/index")}, 
        {path:"/Seller/orders/form/:id?",name:"seller_orders_form",component:()=>import("@/views/Seller/orders/form")},

        // 售后管理
        {path:"/Seller/orders/refund/:id",name:"seller_refunds_form",component:()=>import("@/views/Seller/refunds/index")},

        // 订单评论
        {path:"/Seller/order_comments",name:"seller_order_comments",component:()=>import("@/views/Seller/order_comments/index")}, 
        {path:"/Seller/order_comments/form/:id?",name:"seller_order_comments_form",component:()=>import("@/views/Seller/order_comments/form")},

        // 配送运费
        {path:"/Seller/freights/form",name:"seller_freights",component:()=>import("@/views/Seller/freights/form")}, 

        // 分销活动
        {path:"/Seller/distributions",name:"seller_distributions",component:()=>import("@/views/Seller/distributions/index")}, 
        {path:"/Seller/distributions/form/:id?",name:"seller_distributions_form",component:()=>import("@/views/Seller/distributions/form")},

        // 分销日志
        {path:"/Seller/distribution_logs",name:"seller_distribution_logs",component:()=>import("@/views/Seller/distribution_logs/index")}, 

        // 结算日志
        {path:"/Seller/order_settlements",name:"seller_order_settlements",component:()=>import("@/views/Seller/order_settlements/index")}, 
        {path:"/Seller/order_settlements/form/:id",name:"seller_order_settlements_form",component:()=>import("@/views/Seller/order_settlements/form")}, 

        // 优惠券
        {path:"/Seller/coupons",name:"seller_coupons",component:()=>import("@/views/Seller/coupons/index")}, 
        {path:"/Seller/coupons/form/:id?",name:"seller_coupons_form",component:()=>import("@/views/Seller/coupons/form")},
        {path:"/Seller/coupon_logs",name:"seller_coupon_logs",component:()=>import("@/views/Seller/coupon_logs/index")}, // 优惠券日志

        // 满减
        {path:"/Seller/full_reductions",name:"seller_full_reductions",component:()=>import("@/views/Seller/full_reductions/index")}, 
        {path:"/Seller/full_reductions/form/:id?",name:"seller_full_reductionsform",component:()=>import("@/views/Seller/full_reductions/form")},

        // 秒杀
        {path:"/Seller/seckills",name:"seller_seckills",component:()=>import("@/views/Seller/seckills/index")}, 
        {path:"/Seller/seckills/form/:id?",name:"seller_seckills_form",component:()=>import("@/views/Seller/seckills/form")},

        // 拼团
        {path:"/Seller/collectives",name:"seller_collectives",component:()=>import("@/views/Seller/collectives/index")}, 
        {path:"/Seller/collectives/form/:id?",name:"seller_collectives_form",component:()=>import("@/views/Seller/collectives/form")},
        {path:"/Seller/collective_logs/:collective_id",name:"seller_collective_logs",component:()=>import("@/views/Seller/collective_logs/index")}, 

        // 店铺资金日志
        {path:"/Seller/money_logs",name:"seller_money_logs",component:()=>import("@/views/Seller/money_logs/index")}, 

        // 资金提现
        {path:"/Seller/cashes",name:"seller_cashes",component:()=>import("@/views/Seller/cashes/index")}, 
        {path:"/Seller/cashes/form",name:"seller_cashes_form",component:()=>import("@/views/Seller/cashes/form")}, 

        // 在线聊天
        {path:"/Seller/chats",name:"seller_chats",component:()=>import("@/views/Seller/chats/index")}, 

        // 数据统计
        {path:"/Seller/statistics/order",name:"seller_statistics_order",component:()=>import("@/views/Seller/statistics/order")}, 
    ]},
];