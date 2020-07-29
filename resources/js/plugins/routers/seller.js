export default [
    // 登录
    {path: '/Seller/login',name: 'seller_login',component: () => import('@/views/Seller/login')},

    // 后台界面
    {path:"/Seller/index",name:"seller_index",component:()=>import("@/views/Seller/index"),children:[

        {path:"/Seller/index",name:"seller_default",component:()=>import("@/views/Seller/default")}, // 打开默认页面

        // 商品管理
        {path:"/Seller/goods",name:"seller_goods",component:()=>import("@/views/Seller/goods/index")}, // 商品列表
        {path:"/Seller/goods/chose_class",name:"seller_goods_chose_class",component:()=>import("@/views/Seller/goods/chose_class")}, // 商品分类选择
        {path:"/Seller/goods/form/:id?",name:"seller_goods_form",component:()=>import("@/views/Seller/goods/form")}, // 商品编辑

        // 属性规格
        {path:"/Seller/goods_attrs",name:"seller_goods_attrs",component:()=>import("@/views/Seller/goods_attrs/index")}, // 商品列表
        {path:"/Seller/goods_attrs/form/:id?",name:"seller_goods_attrs_form",component:()=>import("@/views/Seller/goods_attrs/form")}, // 商品编辑

        
    ]},
];