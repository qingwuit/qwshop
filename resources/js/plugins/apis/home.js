import common from './common';
const baseUrl = common.baseUrl;
export default 
{
     /**
     * 青梧商城PC接口
     * <www.qingwuit.com>
     */

    // 登录验证
    "homeLogin" : baseUrl + "login", // 登录
    "homeLogout" : baseUrl + "logout", // 登出
    "homeCheckLogin" : baseUrl + "check_login", // 检测是否登录

     
    'homeAgreement' : baseUrl + 'agreement',  // 站点协议
    'homeGoodsClasses' : baseUrl + 'goods_classes',  // 商品分类获取

    // 全国省市区
    'homeAreas' : baseUrl + 'areas',  

    // 商家入驻
    'homeStoreVerify' : baseUrl + 'store/store_verify',  // 商家审核状态
    'homeStoreJoin' : baseUrl + 'store/store_join',  // 商家审核状态

    
};
