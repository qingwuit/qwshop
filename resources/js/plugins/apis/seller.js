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

    // 品牌管理
    'sellerGoodsBrands' : baseUrl + 'Seller/goods_brands', 

    // 订单管理
    'sellerOrders' : baseUrl + 'Seller/orders',

    // 物流公司
    'sellerExpresses' : baseUrl + 'Seller/expresses',

    // 订单评论
    'sellerOrderComments' : baseUrl + 'Seller/order_comments',

};
