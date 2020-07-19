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

     // 规格属性
    'homeAgreement' : baseUrl + 'agreement', 
    'homeStoreVerify' : baseUrl + 'store/store_verify', 

    
};
