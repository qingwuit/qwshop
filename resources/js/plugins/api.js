// export const baseUrl="https://app.qingwuit.com/api/";
export const baseUrl="http://api.qingwuit.com/api/";
// export const baseUrl="http://127.0.0.1:8000/api/";
export const api = {
    "checkUserLogin" : baseUrl + "Admin/check_user_login", // 验证是否已经登录
    "login" : baseUrl + "Admin/login", // 登录
    "logout" : baseUrl + "Admin/logout", // 退出账号 注销
    
    'getUserList' : baseUrl + "Admin/users/index",

    // 获取统计数据
    'adminGetStatistics' : baseUrl + "Admin/index/get_statistics",

    // 获取后台权限栏目
    'getPermissionMenus' : baseUrl + "Admin/users/get_permission_menus",
    'getBreadNav' : baseUrl + "Admin/menus/get_bread_nav",

    // 钩子API
    'getHooksList' : baseUrl + "Admin/hooks/index",
    'addHook' : baseUrl + "Admin/hooks/add",
    'editHook' : baseUrl + "Admin/hooks/edit/",
    'delHook' : baseUrl + "Admin/hooks/del",

    // 菜单API
    'getMenusList' : baseUrl + "Admin/menus/index",
    'addMenus' : baseUrl + "Admin/menus/add",
    'editMenus' : baseUrl + "Admin/menus/edit/",
    'delMenus' : baseUrl + "Admin/menus/del",

    // 角色API
    'getRolesList' : baseUrl + "Admin/roles/index",
    'addRoles' : baseUrl + "Admin/roles/add",
    'editRoles' : baseUrl + "Admin/roles/edit/",
    'delRoles' : baseUrl + "Admin/roles/del",

    // 用户API
    'getUsersList' : baseUrl + "Admin/users/index",
    'addUsers' : baseUrl + "Admin/users/add",
    'editUsers' : baseUrl + "Admin/users/edit/",
    'delUsers' : baseUrl + "Admin/users/del",
    'getUserInfo' : baseUrl + "Admin/users/get_user_info", // 获取用户信息
    'adminChangeMoney' : baseUrl + "Admin/users/change_money", // 获取用户信息

    // 配置中心
    'webConfig' : baseUrl + "Admin/config/web_config", // 网址配置
    'uploadConfig' : baseUrl + "Admin/config/upload_config", // 上传配置
    'mapConfig' : baseUrl + "Admin/config/map_config", // 地图配置
    'wxPayH5Config' : baseUrl + "Admin/config/wxpay_h5_config", // 微信支付H5
    'wxPayAppConfig' : baseUrl + "Admin/config/wxpay_app_config", // 微信支付app
    'wxPayJsApiConfig' : baseUrl + "Admin/config/wxpay_jsapi_config", // 微信支付jsapi
    'wxPayMiNiConfig' : baseUrl + "Admin/config/wxpay_mini_config", // 微信支付小程序mini
    'aliPayH5Config' : baseUrl + "Admin/config/alipay_h5_config", // 支付宝支付H5
    'aliPayAppConfig' : baseUrl + "Admin/config/alipay_app_config", // 支付宝支付APP
    'aliPayPcConfig' : baseUrl + "Admin/config/alipay_pc_config", // 支付宝支付PC
    'aliSmsConfig' : baseUrl + "Admin/config/alisms_config", // 阿里云sms
    'wechatPublicConfig' : baseUrl + "Admin/config/wechat_public_config", // 公众号微信
    'freightKeyConfig' : baseUrl + "Admin/config/freight_config", // 物流配置
    'oauthConfig' : baseUrl + "Admin/config/oauth_config", // 微信第三方登录Oauth配置 pc
    'distributionConfig' : baseUrl + "Admin/config/distribution_config", // 分销配置
    'TaskTimeConfig' : baseUrl + "Admin/config/task_time_config", // 分销配置

    // 短信列表
    'getSmsLogList' : baseUrl + "Admin/sms_log/index", 
    'delSmsLog' : baseUrl + "Admin/sms_log/del",


    'logoUpload' : baseUrl + "Admin/config/logo_upload", // 网址logo上传

    // 商品分类
    'getGoodsClassList' : baseUrl + "Admin/goods_class/index", 
    'addGoodsClass' : baseUrl + "Admin/goods_class/add",
    'editGoodsClass' : baseUrl + "Admin/goods_class/edit/",
    'delGoodsClass' : baseUrl + "Admin/goods_class/del",
    'goodsClassUpload' : baseUrl + "Admin/goods_class/goods_class_upload", // 分类图片上传

    // 商品品牌
    'getGoodsBrandList' : baseUrl + "Admin/goods_brand/index", 
    'addGoodsBrand' : baseUrl + "Admin/goods_brand/add",
    'editGoodsBrand' : baseUrl + "Admin/goods_brand/edit/",
    'delGoodsBrand' : baseUrl + "Admin/goods_brand/del",
    'goodsBrandUpload' : baseUrl + "Admin/goods_brand/goods_brand_upload", // 品牌图片上传

    // 地区管理
    'getAreaList' : baseUrl + "Admin/area/index", 
    'addArea' : baseUrl + "Admin/area/add",
    'editArea' : baseUrl + "Admin/area/edit/",
    'delArea' : baseUrl + "Admin/area/del",
    'getAreaChildren' : baseUrl + "Admin/area/get_area_children",
    'adminGetAreaList' : baseUrl + "Admin/area/get_area_list",

    // 清空缓存
    'cacheFlush' : baseUrl + "Admin/auto/cache_flush",

    // 商品管理
    'getAdminGoodsList' : baseUrl + "Admin/goods/index", 
    'adminGoodsStatus' : baseUrl + "Admin/goods/goods_status",
    'adminGoodsIndex' : baseUrl + "Admin/goods/goods_index",
    'goodsVerify' : baseUrl + "Admin/goods/goods_verify",
    'goodsVerifyChange' : baseUrl + "Admin/goods/goods_verify_change",

    // 订单管理
    'getAdminOrderList' : baseUrl + "Admin/order/index", 
    'getAdminOrderInfo' : baseUrl + "Admin/order/info", 

    // 广告位
    'getAdvPositionList' : baseUrl + "Admin/adv_position/index", 
    'addAdvPosition' : baseUrl + "Admin/adv_position/add",
    'editAdvPosition' : baseUrl + "Admin/adv_position/edit/",
    'delAdvPosition' : baseUrl + "Admin/adv_position/del",

    // 广告
    'getAdvList' : baseUrl + "Admin/adv/index", 
    'addAdv' : baseUrl + "Admin/adv/add",
    'editAdv' : baseUrl + "Admin/adv/edit/",
    'delAdv' : baseUrl + "Admin/adv/del",
    'advUpload' : baseUrl + "Admin/adv/adv_upload", // 品牌图片上传

    // 秒杀
    'getSeckillList' : baseUrl + "Admin/seckill/index", 
    'addSeckill' : baseUrl + "Admin/seckill/add",
    'editSeckill' : baseUrl + "Admin/seckill/edit/",
    'delSeckill' : baseUrl + "Admin/seckill/del",
    'getAddSeckillGoods' : baseUrl + "Admin/seckill/get_add_seckill_goods", // 获取秒杀商品
    'changeSeckillStatus' : baseUrl + "Admin/seckill/change_status", // 修改审核状态
    'delSeckillGoods' : baseUrl + "Admin/seckill/del_seckill_goods", // 删除

    // 站点协议
    'getAgreementList' : baseUrl + "Admin/agreement/index", 
    'addAgreement' : baseUrl + "Admin/agreement/add",
    'editAgreement' : baseUrl + "Admin/agreement/edit/",
    'delAgreement' : baseUrl + "Admin/agreement/del",

    // 店铺列表
    'getStoreList' : baseUrl + "Admin/store/index", 
    'delStore' : baseUrl + "Admin/store/del",
    'StorePass' : baseUrl + "Admin/store/store_pass",

    // 积分商城
    'getIntegralList' : baseUrl + "Admin/integral/index", 
    'addIntegral' : baseUrl + "Admin/integral/add", 
    'editIntegral' : baseUrl + "Admin/integral/edit", 
    'delIntegral' : baseUrl + "Admin/integral/del", 
    'goodsStatusIntegral' : baseUrl + "Admin/integral/goods_status", 
    'goodsHotIntegral' : baseUrl + "Admin/integral/goods_hot", 
    'integralUpload' : baseUrl + "Admin/integral/integral_upload", 

    // 积分商城分类
    'adminGetIntegralClassList' : baseUrl + "Admin/integral_class/index", 
    'adminAddIntegralClass' : baseUrl + "Admin/integral_class/add",
    'adminEditIntegralClass' : baseUrl + "Admin/integral_class/edit/",
    'adminDelIntegralClass' : baseUrl + "Admin/integral_class/del",

    // 积分订单
    'adminGetIntegralOrderList' : baseUrl + "Admin/integral_order/index", 
    'adminGetIntegralOrderInfo' : baseUrl + "Admin/integral_order/info", 
    'adminIntegralOrderSendDelivery' : baseUrl + "Admin/integral_order/send_delivery", 

    // 资金提现
    'adminGetCashList' : baseUrl + "Admin/cash/index", 
    'adminDelCash' : baseUrl + "Admin/cash/del", 
    'adminCashChangeStatus' : baseUrl + "Admin/cash/change_status", // 修改打款状态



    /**
     * @deprecated 商家端 视图
     * @author hg <www.qingwuit.com>
     */
    
    "sellerLogin" : baseUrl + "Seller/login", // 登录

    // 获取统计数据
    'sellerGetStatistics' : baseUrl + "Seller/index/get_statistics",

    // 规格属性
    'getAttrSpecList' : baseUrl + "Seller/attr_spec/index", 
    'addAttrSpec' : baseUrl + "Seller/attr_spec/add",
    'editAttrSpec' : baseUrl + "Seller/attr_spec/edit/",
    'delAttrSpec' : baseUrl + "Seller/attr_spec/del",

    // 商家自定义分类
    'getStoreGoodsClassList' : baseUrl + "Seller/store_goods_class/index", 
    'addStoreGoodsClass' : baseUrl + "Seller/store_goods_class/add",
    'editStoreGoodsClass' : baseUrl + "Seller/store_goods_class/edit/",
    'delStoreGoodsClass' : baseUrl + "Seller/store_goods_class/del",

    // 商品
    'goodsUpload' : baseUrl +"Seller/goods/goods_upload",
    'getGoodsList' : baseUrl + "Seller/goods/index", 
    'addGoods' : baseUrl + "Seller/goods/add",
    'editGoods' : baseUrl + "Seller/goods/edit/",
    'delGoods' : baseUrl + "Seller/goods/del",
    'goodsStatus' : baseUrl + "Seller/goods/goods_status",

    // 订单管理
    'getSellerOrderList' : baseUrl + "Seller/order/index", 
    'getSellerOrderInfo' : baseUrl + "Seller/order/info", 
    'getSellerSendDelivery' : baseUrl + "Seller/order/send_delivery", 
    'getSellerRefund' : baseUrl + "Seller/order/refund",  // 同意申请售后
    'getSellerRefundMoney' : baseUrl + "Seller/order/refund_money",  // 同意退钱

    // 团购订单
    'getSellerGroupbuyOrderList' : baseUrl + "Seller/groupbuy/index", 
    'getSellerGroupbuyUserList' : baseUrl + "Seller/groupbuy/get_groupbuy_user", 

    // 运费模版
    'getFreightTemplateList' : baseUrl + "Seller/freight_template/index", 
    'addFreightTemplate' : baseUrl + "Seller/freight_template/add",
    'editFreightTemplate' : baseUrl + "Seller/freight_template/edit/",
    'delFreightTemplate' : baseUrl + "Seller/freight_template/del",
    
    // 选择商品分类
    'GetStoreClass' : baseUrl + "Seller/store/get_store_class",

    'storeInfoSetting' : baseUrl + "Seller/store/setting",
    'storeFreeFreightSetting' : baseUrl + "Seller/free_freight/setting",  // 设置免多少包邮
    'storeAfterSaleContent' : baseUrl + "Seller/store/after_sale_content",  // 设置商品详情页面售后服务
    'sellerGetAreaList' : baseUrl + "Seller/area/get_area_list",
    'storeLogoUpload' : baseUrl + "Seller/store/logo_upload",

    // 秒杀
    'sellerGetSeckillList' : baseUrl + "Seller/seckill/index", 
    'sellerAddSeckill' : baseUrl + "Seller/seckill/add",
    'sellerEditSeckill' : baseUrl + "Seller/seckill/edit/",
    'sellerDelSeckill' : baseUrl + "Seller/seckill/del",
    'sellerAddSeckillGoods' : baseUrl + "Seller/seckill/add_seckill_goods", // 添加秒杀商品
    'sellerGetAddSeckillGoods' : baseUrl + "Seller/seckill/get_add_seckill_goods", // 获取秒杀商品
    'sellerDelSeckillGoods' : baseUrl + "Admin/seckill/del_seckill_goods", // 删除

    // 富文本上传
    'autoUpload' : baseUrl +"Seller/auto/auto_upload",


    /**
     * @deprecated PC端 视图
     * @author hg <www.qingwuit.com>
     */

    // 用户登录注册
    "homeLogin" : baseUrl + "user/login", // 登录
    "homeLogout" : baseUrl + "user/logout", // 注销账号
    "homeGetOauthConfig" : baseUrl + "user/get_oauth_config", // 获取OAUTH
    "homeRegister" : baseUrl + "user/register", // 注册
    "homeForgetPassword" : baseUrl + "user/forget_password", // 忘记密码
    "homeSendSms" : baseUrl + "user/send_sms", // 发送短信
    "homeSendEmail" : baseUrl + "user/send_email", // 发送邮件

    "homeGetUserInfo" : baseUrl + "user/get_user_info",// 获取用户信息
    "homeEditUserInfo" : baseUrl + "user/edit_user_info",// 修改用户信息
    "homeCheckPayPassword" : baseUrl + "user/check_pay_password",// 验证支付密码
    "homeGetFavList" : baseUrl + "user/get_fav_list",// 修改收藏关注列表
    "homeDelFav" : baseUrl + "user/del_fav",// 删除收藏关注
    "homeGetMoneyLog" : baseUrl + "user/get_money_log",// 获取用户资金变更日志
    "homeAvatar" : baseUrl + "user/avatar",// 用户头像上传

    // 邀请信息
    "homeGetInviterInfo" : baseUrl + "user/get_inviter_info",// 用户头像上传

    // 认证信息
    "homeGetUserCheckInfo" : baseUrl + "user/get_user_check_info",// 获取用户认证信息
    "homeEditUserCheckInfo" : baseUrl + "user/edit_user_check_info",// 修改用户认证信息
    "homeUserCard" : baseUrl + "user/user_card",// 身份证上传

    // 商城首页接口
    "homeGetSubNavInfo" : baseUrl + "index/get_subnav_info", // 获取首页左侧导航的信息
    "homeGetIndexInfo" : baseUrl + "index/get_index_info", // 获取首页信息
    "homeGetFootInfo" : baseUrl + "index/get_foot_info", // 获取公共底部信息

    // 秒杀接口
    "homeGetSeckillList" : baseUrl + "seckill/get_seckill_list", // 获取秒杀

    // 商家入驻
    "homeStoreJoin" : baseUrl + "user/join", 
    "homeGetJoinIndexAdv" : baseUrl + "index/get_join_index_adv", // // 获取加入我们幻灯片
    "homeIsStore" : baseUrl + "store/is_store", // // 获取加入我们幻灯片

    // 商品详情
    "homeGetGoodsInfo" : baseUrl + "goods/get_goods_info",
    "homeSearchGoods" : baseUrl + "goods/search_goods",
    "homeGetBrandList" : baseUrl + "goods/get_brand_list",
    'homeGetCommentListByGoods' : baseUrl + "goods/get_comment_list_by_goods",  // 获取评论列表根据商品ID

    // 收藏接口
    "homeIsFav" : baseUrl + "fav/is_fav",
    "homeEditFav" : baseUrl + "fav/edit_fav",

    // 商家首页
    "homeGetStoreInfo" : baseUrl + "store/get_store_info", // 获取商家信息
    "homeGetStoreIndexInfo" : baseUrl + "store/get_store_index_info", // 获取商家首页信息
    "homeGetStoreGoodsClass" : baseUrl + "store/get_store_class", // 获取商家分类信息
    "homeGetStoreClassGoods" : baseUrl + "store/get_store_class_goods", // 获取商家分类信息和商品列表

    // 积分商城
    "homeGetIntegralIndexInfo" : baseUrl + "integral/get_index_info", // 
    "homeGetIntegralGoodsInfo" : baseUrl + "integral/goods/info/", // 获取积分商品详情
    "homeSearchIntegralGoods" : baseUrl + "integral/goods/search_integral_goods", // 获取积分条件列表
    "homeGetIntegralGoodsClass" : baseUrl + "integral/goods/get_integral_goods_class", // 获取积分商品分类

    // 积分商城创建订单
    "homeIntegralCreateOrder" : baseUrl + "integral/order/create_order", // 获取积分商品分类
    "homeGetIntegralUserOrder" : baseUrl + "user/integral/get_user_order", // 获取积分订单
    "homeIntegralChangeOrderStatus" : baseUrl + "user/integral/change_order_status", // 确认收货

    // 拼团 团购
    "homeGetGroupbuyBanner" : baseUrl + "groupbuy/get_groupbuy_banner", // 团购幻灯片

    // 商品销售排行
    "homeGetSaleList" : baseUrl + "goods/get_sale_list",

    // 购物车
    "homeAddCart" : baseUrl + "cart/add_cart",
    "homeChangeCart" : baseUrl + "cart/change_cart",
    "homeDelCart" : baseUrl + "cart/del_cart",
    "homeGetCartList" : baseUrl + "cart/get_cart_list",
    "homeGetCartCount" : baseUrl + "cart/get_cart_count",

    // 用户中心
    "homeGetUserDefault" : baseUrl + "user/get_user_default", // 默认页面
    "homeGetUserOrder" : baseUrl + "user/get_user_order", // 默认页面

    // 用户收货地址
    'getAddressList' : baseUrl + "address/index", 
    'addAddress' : baseUrl + "address/add",
    'editAddress' : baseUrl + "address/edit/",
    'delAddress' : baseUrl + "address/del",
    'setDefaultAddress' : baseUrl + "address/set_default",

    // 获取物流信息
    'homeGetDeliveryList' : baseUrl + "order/get_user_delivery",

    // 订单评论
    'homeGetOrderInfoByOrderId' : baseUrl + "order/get_order_info_by_order_id",  // 根据订单ID获取订单信息
    'homeGetCommentList' : baseUrl + "order/get_comment_list",  // 根据订单ID获取订单信息
    'homeAddComment' : baseUrl + "order/add_comment",  // 添加评论
    'homeCommentImage' : baseUrl + "comment/comment_image",  // 图片上传 评论

    // 修改订单状态
    'homeChangeOrderStatus' : baseUrl + "order/change_order_status",
    'homeOrderRefund' : baseUrl + "order/refund", // 申请售后
    'homeOrderRefundDelivery' : baseUrl + "order/refund_delivery_no", // 售后发送快递单号

    // 预生成订单数据
    'homeGetBeforOrder' : baseUrl + "order/get_befor_order",

    // 生成订单
    'homeCreateOrder' : baseUrl + "order/create_order",
    'homeGetOrderInfoByOrderNo' : baseUrl + "order/get_order_info_by_order_no",  // 根据订单号获取订单信息
    'homePayOrder' : baseUrl + "order/pay",  // 支付订单
    'homeCheckWxpayNative' : baseUrl + "order/check_wxpay_native",  // 查下订单支付情况

    // 取消订单
    'homeCloseOrder' : baseUrl + "order/close_order",

    // 提现 cash
    'homeGetCashLog' : baseUrl + "cash/get_cash_log",
    'homeAddCashLog' : baseUrl + "cash/add_cash",

    // 无压缩裁剪上传
    'homeAutoUpload' : baseUrl +"auto/auto_upload",

    /**
     * 公共接口
     * @author hg <www.qingwuit.com>
     */

    // 获取协议数据(根据ename)
    "homeGetAgreementInfo" : baseUrl + "api/get_agreement_info",

    // 获取省市区信息
    "homeGetAreaList" : baseUrl + "api/get_area_list",

    // 获取PC商品分类信息
    "homeGetGoodsClassList" : baseUrl + "api/get_goods_class_list",

    // 获取PC 幻灯片或者广告
    "homeGetBanner" : baseUrl + "api/get_banner",


    /**
     * @deprecated 聊天接口
     * @author hg <www.qingwuit.com>
     */

    "chatAddFriend" : baseUrl + "chat/add_friend", // 添加为好友
    "chatGetChatFriend" : baseUrl + "chat/get_chat_friend", // 获取聊天好友列表
    "chatGetChatMsg" : baseUrl + "chat/get_chat_msg", // 获取聊天信息
    "chatChatEvent" : baseUrl + "chat/chat_event", // 接收前端信息接口
    "chatReadMsg" : baseUrl + "chat/read_msg", // 清空未读信息
    "chatUpload" : baseUrl + "chat/image", // 图片上次
    
}