import common from './common';
const baseUrl = common.baseUrl;
export default 
{
     /**
     * 后台接口
     * <www.qingwuit.com>
     */
    "login" : baseUrl + "Admin/login", // 登录

    'adminMenus' : baseUrl + 'Admin/menus', // 后台菜单
    'adminRoles' : baseUrl + 'Admin/roles', // 角色管理
    'adminPermissionGroups' : baseUrl + 'Admin/permission_groups', // 后台接口分组
    'adminPermissions' : baseUrl + 'Admin/permissions', // 后台接口分组



    // 缓存处理
    'adminMenusClearCache' : baseUrl + 'Admin/menus/cache/clear', // 清空菜单缓存
};
