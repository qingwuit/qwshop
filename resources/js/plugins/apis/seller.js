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

     // 规格属性
    'sellerGoodsAttrs' : baseUrl + 'Seller/goods_attrs', 

    // 商品管理
    'sellerGoods' : baseUrl + 'Seller/goods', 
    'sellerGoodsUpload' : baseUrl + 'Seller/goods/upload/images', 
    
};
