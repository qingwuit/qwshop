import common from './common';
const baseUrl = common.baseUrl;
export default 
{
     /**
     * 商家后台接口
     * <www.qingwuit.com>
     */

    // 登录验证
    "sellerLogin" : baseUrl + "Seller/login", // 登录
    "sellerLogout" : baseUrl + "Seller/logout", // 登出
    "sellerCheckLogin" : baseUrl + "Seller/check_login", // 检测是否登录

    // 商家菜单
    'sellerMenus' : baseUrl + 'Seller/menus', 

     // 规格属性
    'sellerGoodsAttrs' : baseUrl + 'Seller/goods_attrs', 

    // 商品管理
    'sellerGoods' : baseUrl + 'Seller/goods', 
    'sellerStoreGoodsClasses' : baseUrl + 'Seller/store_goods_classes',  // 获取店铺有权的栏目信息
    'sellerGoodsUpload' : baseUrl + 'Seller/goods/upload/images', 

    // 编辑器上传
    'sellerEditor' : baseUrl + 'Seller/editor/upload',

    // 品牌管理
    'sellerGoodsBrands' : baseUrl + 'Seller/goods_brands', 

    // 订单管理
    'sellerOrders' : baseUrl + 'Seller/orders',

    // 订单售后
    'sellerRefunds' : baseUrl + 'Seller/refunds',

    // 物流公司
    'sellerExpresses' : baseUrl + 'Seller/expresses',

    // 订单评论
    'sellerOrderComments' : baseUrl + 'Seller/order_comments',

    // 配置中心处理
    'sellerConfigs' : baseUrl + 'Seller/configs',

    // 全国省市区获取
    'sellerAreas' : baseUrl + 'Seller/areas',

    // 运费配置
    'sellerFreights' : baseUrl + 'Seller/freights',

    // 分销活动
    'sellerDistributions' : baseUrl + 'Seller/distributions',

    // 分销日志
    'sellerDistributionLogs' : baseUrl + 'Seller/distribution_logs',

    // 优惠券
    'sellerCoupons' : baseUrl + 'Seller/coupons',
    'sellerCouponLogs' : baseUrl + 'Seller/coupon_logs', // 优惠券日志

    // 满减
    'sellerFullReductions' : baseUrl + 'Seller/full_reductions',

    // 秒杀
    'sellerSeckills' : baseUrl + 'Seller/seckills',

    // 拼团
    'sellerCollectives' : baseUrl + 'Seller/collectives',
    'sellerCollectiveLogs' : baseUrl + 'Seller/collective_logs',

    // 结算日志
    'sellerOrderSettlements' : baseUrl + 'Seller/order_settlements',

    // 店铺资金日志
    'sellerMoneyLogs' : baseUrl + 'Seller/money_logs',

    // 资金提现
    'sellerCashes' : baseUrl + 'Seller/cashes',

    // 数据统计
    'sellerStatistics' : baseUrl + 'Seller/statistics', 

};
