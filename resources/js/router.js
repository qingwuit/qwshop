import Vue from 'vue'
import Router from 'vue-router'
// import Home from './views/Home.vue'

Vue.use(Router)

export default new Router({
  mode:'history',
  routes: [
      // 登录
      {path: '/Admin/login',name: 'login',component: () => import('./views/Admin/login.vue')},

      // 后台界面
      {path:"/Admin/index",name:"admin_index",component:()=>import("./views/Admin/index"),children:[

        {path:"/Admin/index",name:"admin_default",component:()=>import("./views/Admin/default")}, // 打开默认页面

        // 钩子 Hooks
        {path:"/Admin/hooks/index",name:"hooks_index",component:()=>import("./views/Admin/hooks/index")},
        {path:"/Admin/hooks/form",name:"hooks_form",component:()=>import("./views/Admin/hooks/form")},

        // 菜单 Menus
        {path:"/Admin/menus/index",name:"menus_index",component:()=>import("./views/Admin/menus/index")},
        {path:"/Admin/menus/form",name:"menus_form",component:()=>import("./views/Admin/menus/form")},

        // 角色 Roles
        {path:"/Admin/roles/index",name:"roles_index",component:()=>import("./views/Admin/roles/index")},
        {path:"/Admin/roles/form",name:"roles_form",component:()=>import("./views/Admin/roles/form")},

        // 用户 Users
        {path:"/Admin/users/index",name:"users_index",component:()=>import("./views/Admin/users/index")},
        {path:"/Admin/users/form",name:"users_form",component:()=>import("./views/Admin/users/form")},

        // 配置页面 Config
        {path:"/Admin/config/web_config",name:"web_config",component:()=>import("./views/Admin/config/web_config")},
        {path:"/Admin/config/upload_config",name:"upload_config",component:()=>import("./views/Admin/config/upload_config")},
        {path:"/Admin/config/map_config",name:"map_config",component:()=>import("./views/Admin/config/map_config")},
        {path:"/Admin/config/pay_config",name:"pay_config",component:()=>import("./views/Admin/config/pay_config")},
        {path:"/Admin/config/alisms_config",name:"alisms_config",component:()=>import("./views/Admin/config/alisms_config")},
        {path:"/Admin/config/wechat_public_config",name:"wechat_public_config",component:()=>import("./views/Admin/config/wechat_public_config")},
        {path:"/Admin/config/freight_config",name:"admin_freight_config",component:()=>import("./views/Admin/config/freight_config")},
        {path:"/Admin/config/oauth_config",name:"admin_oauth_config",component:()=>import("./views/Admin/config/oauth_config")}, // 第三方登录 PC wechat
        {path:"/Admin/config/distribution",name:"admin_distribution_config",component:()=>import("./views/Admin/config/distribution")}, // 分销配置
        {path:"/Admin/config/task_time",name:"admin_task_time_config",component:()=>import("./views/Admin/config/task_time")}, // 任务执行  定时任务控制

        // 短信列表
        {path:"/Admin/config/alisms_list",name:"alisms_list",component:()=>import("./views/Admin/config/alisms_list")},

        // 商品分类
        {path:"/Admin/goods_class/index",name:"goods_class_index",component:()=>import("./views/Admin/goods_class/index")},
        {path:"/Admin/goods_class/form",name:"goods_class_form",component:()=>import("./views/Admin/goods_class/form")},

        // 品牌列表
        {path:"/Admin/goods_brand/index",name:"goods_brand_index",component:()=>import("./views/Admin/goods_brand/index")},
        {path:"/Admin/goods_brand/form",name:"goods_brand_form",component:()=>import("./views/Admin/goods_brand/form")},

        // 地区管理
        {path:"/Admin/area/index",name:"area_index",component:()=>import("./views/Admin/area/index")},
        {path:"/Admin/area/form",name:"area_form",component:()=>import("./views/Admin/area/form")},

        // 商品管理
        {path:"/Admin/goods/index",name:"admin_goods_index",component:()=>import("./views/Admin/goods/index")},
        {path:"/Admin/goods/goods_verify",name:"admin_goods_verify",component:()=>import("./views/Admin/goods/goods_verify")}, // 待审核页面

        // 积分商品中心
        {path:"/Admin/integral/index",name:"admin_integral_index",component:()=>import("./views/Admin/integral/index")},
        {path:"/Admin/integral/add",name:"admin_integral_add",component:()=>import("./views/Admin/integral/add")}, // 添加积分商品
        {path:"/Admin/integral/edit/:id",name:"admin_integral_edit",component:()=>import("./views/Admin/integral/edit")}, // 编辑积分商品
    
        // 积分商城分类
        {path:"/Admin/integral_class/index",name:"admin_integral_class_index",component:()=>import("./views/Admin/integral_class/index")},
        {path:"/Admin/integral_class/form",name:"admin_integral_class_form",component:()=>import("./views/Admin/integral_class/form")},

        // 积分商城 订单
        {path:"/Admin/integral_order/index",name:"admin_integral_order_index",component:()=>import("./views/Admin/integral_order/index")},
        {path:"/Admin/integral_order/info/:id",name:"admin_integral_order_info",component:()=>import("./views/Admin/integral_order/info")},

        // 订单管理
        {path:"/Admin/order/index",name:"admin_order_index",component:()=>import("./views/Admin/order/index")},
        {path:"/Admin/order/info/:id",name:"admin_order_info",component:()=>import("./views/Admin/order/info")},

        // 售后处理
        {path:"/Admin/refund/index",name:"admin_refund_index",component:()=>import("./views/Admin/refund/index")},

        // 广告位
        {path:"/Admin/adv_position/index",name:"adv_position_index",component:()=>import("./views/Admin/adv_position/index")},
        {path:"/Admin/adv_position/form",name:"adv_position_form",component:()=>import("./views/Admin/adv_position/form")},

        // 广告
        {path:"/Admin/adv/index",name:"adv_index",component:()=>import("./views/Admin/adv/index")},
        {path:"/Admin/adv/form",name:"adv_form",component:()=>import("./views/Admin/adv/form")},

        // 秒杀
        {path:"/Admin/seckill/index",name:"seckill_index",component:()=>import("./views/Admin/seckill/index")},
        {path:"/Admin/seckill/form",name:"seckill_form",component:()=>import("./views/Admin/seckill/form")},
        {path:"/Admin/seckill/seckill_goods/:sid",name:"admin_seckill_goods_form",component:()=>import("./views/Admin/seckill/seckill_goods")},

        // 站点协议
        {path:"/Admin/agreement/index",name:"agreement_index",component:()=>import("./views/Admin/agreement/index")},
        {path:"/Admin/agreement/form",name:"agreement_form",component:()=>import("./views/Admin/agreement/form")},

        // 店铺 store
        {path:"/Admin/store/index",name:"store_index",component:()=>import("./views/Admin/store/index")},
        {path:"/Admin/store/join",name:"store_join_index",component:()=>import("./views/Admin/store/join")},
        {path:"/Admin/store/error",name:"store_error_index",component:()=>import("./views/Admin/store/error")},
        {path:"/Admin/store/form",name:"store_form",component:()=>import("./views/Admin/store/form")},

        {path:"/Admin/cash/index",name:"admin_cash_index",component:()=>import("./views/Admin/cash/index")},

      ]},


      // 商家登录
      {path: '/Seller/login',name: 'seller_login',component: () => import('./views/Seller/login.vue')},

      // 后台界面
      {path:"/Seller/index",name:"seller_index",component:()=>import("./views/Seller/index"),children:[

        {path:"/Seller/index",name:"seller_default",component:()=>import("./views/Seller/default")}, // 打开默认页面

        // 商品中心
        {path:"/Seller/goods/index",name:"seller_goods_index",component:()=>import("./views/Seller/goods/index")},
        {path:"/Seller/goods/chose_class",name:"seller_goods_chose_class",component:()=>import("./views/Seller/goods/chose_class")}, // 添加商品先选择分类
        {path:"/Seller/goods/add/:id",name:"seller_goods_add",component:()=>import("./views/Seller/goods/add")}, // 添加商品
        {path:"/Seller/goods/edit/:id",name:"seller_goods_edit",component:()=>import("./views/Seller/goods/edit")}, // 编辑商品

        // 规格属性
        {path:"/Seller/attr_spec/index",name:"attr_spec_index",component:()=>import("./views/Seller/attr_spec/index")},
        {path:"/Seller/attr_spec/form",name:"attr_spec_form",component:()=>import("./views/Seller/attr_spec/form")},

        // 商家自定义分类
        {path:"/Seller/store_goods_class/index",name:"store_goods_class_index",component:()=>import("./views/Seller/store_goods_class/index")},
        {path:"/Seller/store_goods_class/form",name:"store_goods_class_form",component:()=>import("./views/Seller/store_goods_class/form")},

        // 物流 快递模版
        {path:"/Seller/freight_template/index",name:"freight_template_index",component:()=>import("./views/Seller/freight_template/index")},
        {path:"/Seller/freight_template/form",name:"freight_template_form",component:()=>import("./views/Seller/freight_template/form")},

        // 店铺设置
        {path:"/Seller/store/setting",name:"store_setting",component:()=>import("./views/Seller/store/setting")},
        {path:"/Seller/free_freight/index",name:"free_freight",component:()=>import("./views/Seller/store/free_freight")}, // 免邮包邮设置
        {path:"/Seller/store/after_sale_content",name:"seller_after_sale_content",component:()=>import("./views/Seller/store/after_sale_content")}, // 免邮包邮设置

        // 订单管理
        {path:"/Seller/order/index",name:"seller_order_index",component:()=>import("./views/Seller/order/index")},
        {path:"/Seller/order/info/:id",name:"seller_order_info",component:()=>import("./views/Seller/order/info")},
        {path:"/Seller/refund/index",name:"seller_refund_index",component:()=>import("./views/Seller/refund/index")}, // 售后订单


        // 拼团订单
        {path:"/Seller/groupbuy_order/index",name:"seller_groupbuy_order_index",component:()=>import("./views/Seller/groupbuy_order/index")},
        {path:"/Seller/groupbuy_order/groupbuy_user/:gb_id",name:"seller_groupbuy_user_index",component:()=>import("./views/Seller/groupbuy_order/groupbuy_user")},

        // 秒杀
        {path:"/Seller/seckill/index",name:"seller_seckill_index",component:()=>import("./views/Seller/seckill/index")},
        {path:"/Seller/seckill/form",name:"seller_seckill_form",component:()=>import("./views/Seller/seckill/form")},
        {path:"/Seller/seckill/seckill_goods/:sid",name:"seller_seckill_goods_form",component:()=>import("./views/Seller/seckill/seckill_goods")},

      ]},


      // PC端界面
      {path: '/',name: 'home',component: () => import('./views/Home/index.vue')},

      {path: '/user/login',name: 'user_login',component: () => import('./views/Home/login.vue')},
      {path: '/user/register',name: 'user_register',component: () => import('./views/Home/register.vue')},
      {path: '/user/forget_password',name: 'user_forget_password',component: () => import('./views/Home/forget_password.vue')}, // 忘记密码


      // 入驻
      {path: '/store/join',name: 'store_join',component: () => import('./views/Home/store/join.vue')},
      {path: '/store/join_1',name: 'store_join_1',component: () => import('./views/Home/store/join_1.vue')},
      {path: '/store/join_2',name: 'store_join_2',component: () => import('./views/Home/store/join_2.vue')},
      {path: '/store/join_3',name: 'store_join_3',component: () => import('./views/Home/store/join_3.vue')}, // 审核通过
      {path: '/store/join_4',name: 'store_join_4',component: () => import('./views/Home/store/join_4.vue')}, // 审核中

      // 店铺首页
      {path: '/store/:id',name: 'home_store_index',component: () => import('./views/Home/store/index.vue')},
      {path: '/store/:id/class/:class_id',name: 'home_store_goods_list',component: () => import('./views/Home/store/store_goods_list.vue')},

      // 产品列表
      {path: '/goods/params/:info',name: 'goods_index',component: () => import('./views/Home/goods/index.vue')},

      // 秒杀列表
      {path: '/goods/seckill',name: 'goods_seckill',component: () => import('./views/Home/goods/seckill.vue')},

      // 产品详情
      {path: '/goods/info/:goods_id',name: 'home_goods_info',component: () => import('./views/Home/goods/info.vue')},

      // 用户中心
      {path: '/user/index',name: 'user_index',component: () => import('./views/Home/user/index.vue'),children:[
        {path: '/user/index',name: 'user_default',component: () => import('./views/Home/user/default.vue')},
        {path: '/user/address',name: 'user_address',component: () => import('./views/Home/user/address.vue')},
        {path: '/user/order',name: 'user_order',component: () => import('./views/Home/user/order.vue')},
        {path: '/user/user_info',name: 'home_user_info',component: () => import('./views/Home/user/user_info.vue')},
        {path: '/user/fav',name: 'home_fav',component: () => import('./views/Home/user/fav.vue')}, // 收藏关注
        {path: '/user/fav_store',name: 'home_fav_store',component: () => import('./views/Home/user/fav_store.vue')}, // 收藏关注
        {path: '/user/get_money_log/money',name: 'home_get_money_log',component: () => import('./views/Home/user/log.vue')}, // 资金变更日志
        {path: '/user/get_money_log/freeze_money',name: 'home_get_money_log1',component: () => import('./views/Home/user/log_1.vue')}, // 资金变更日志
        {path: '/user/get_money_log/integral',name: 'home_get_money_log2',component: () => import('./views/Home/user/log_2.vue')}, // 资金变更日志
        {path: '/user/safe',name: 'home_user_safe',component: () => import('./views/Home/user/safe.vue')}, // 账号安全

        {path: '/user/safe/password',name: 'home_user_safe_password',component: () => import('./views/Home/user//safe/password.vue')}, // 密码修改
        {path: '/user/safe/pay_password',name: 'home_user_safe_pay_password',component: () => import('./views/Home/user//safe/pay_password.vue')}, // 支付密码修改

        {path: '/user/user_bind',name: 'home_user_bind',component: () => import('./views/Home/user/user_bind.vue')}, // 账号绑定
        {path: '/user/safe/card',name: 'home_user_card',component: () => import('./views/Home/user/safe/card.vue')}, // 身份认证

        // 评论列表
        {path: '/user/comment/add/:order_id',name: 'home_comment_add',component: () => import('./views/Home/user/comment/add.vue')}, // 订单添加评论
        {path: '/user/comment/index',name: 'home_comment_index',component: () => import('./views/Home/user/comment/index.vue')}, // 订单添加评论

        // 分销信息
        {path: '/user/inviter/inviter_info',name: 'home_inviter_info',component: () => import('./views/Home/user/inviter/inviter_info.vue')}, 
        {path: '/user/inviter/inviter_member',name: 'home_inviter_member',component: () => import('./views/Home/user/inviter/inviter_member.vue')}, 
        {path: '/user/inviter/inviter_money',name: 'home_inviter_money',component: () => import('./views/Home/user/inviter/inviter_money.vue')}, 

        // 积分订单
        {path: '/user/integral_order',name: 'integral_order',component: () => import('./views/Home/user/integral_order.vue')},

        // 提现列表页面
        {path: '/user/cash/index',name: 'home_cash_index',component: () => import('./views/Home/user/cash/index.vue')}, 
      ]},


      // 购物车
      {path: '/cart/index',name: 'cart_index',component: () => import('./views/Home/cart/index.vue')},

      // 生成订单页面  或者选择物流地址
      {path: '/order/create_order/:type/:info',name: 'create_order_index',component: () => import('./views/Home/order/create_order.vue')},  
      {path: '/order/chose_pay/:order_no/:type/:info',name: 'home_chose_pay',component: () => import('./views/Home/order/chose_pay.vue')},  
      {path: '/order/pay_success',name: 'home_pay_success',component: () => import('./views/Home/order/pay_success.vue')},  

      // 积分商城
      {path: '/integral/index',name: 'home_integral_index',component: () => import('./views/Home/integral/index.vue')},  
      {path: '/integral/goods/index/:info',name: 'home_integral_goods_index',component: () => import('./views/Home/integral/goods/index.vue')},  
      {path: '/integral/goods/info/:id',name: 'home_integral_goods_info',component: () => import('./views/Home/integral/goods/info.vue')},  

      // 积分商城订单
      {path: '/integral/order/create_order/:info',name: 'home_integral_order_create_order',component: () => import('./views/Home/integral/order/create_order.vue')},  
      {path: '/integral/order/pay_success',name: 'home_integral_order_pay_success',component: () => import('./views/Home/integral/order/pay_success.vue')},   // 积分兑换成功

      // 拼团商品列表页面
      {path: '/groupbuy/list/:info',name: 'home_groupbuy_list',component: () => import('./views/Home/goods/groupbuy.vue')}, 
      
      
      

      
    // {
    //   path: '/',
    //   name: 'home',
    //   component: Home
    // },
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (about.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import(/* webpackChunkName: "about" */ './views/About.vue')
    // }
  ]
})
