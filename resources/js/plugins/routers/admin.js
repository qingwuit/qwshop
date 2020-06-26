export default [
    // 登录
    {path: '/Admin/login',name: 'admin_login',component: () => import('@/views/Admin/login')},

    // 后台界面
    {path:"/Admin/index",name:"admin_index",component:()=>import("@/views/Admin/index"),children:[

        {path:"/Admin/index",name:"admin_default",component:()=>import("@/views/Admin/default")}, // 打开默认页面

        // 用户管理
        {path:"/Admin/users",name:"admin_users",component:()=>import("@/views/Admin/users/index")}, 

        // 菜单管理
        {path:"/Admin/menus",name:"admin_menus",component:()=>import("@/views/Admin/menus/index")}, 
        {path:"/Admin/menus/form/:id?",name:"admin_menus_form",component:()=>import("@/views/Admin/menus/form")}, 

        // 接口分组
        {path:"/Admin/permission_groups",name:"admin_permission_groups",component:()=>import("@/views/Admin/permission_groups/index")}, 
        {path:"/Admin/permission_groups/form/:id?",name:"admin_permission_groups_form",component:()=>import("@/views/Admin/permission_groups/form")}, 
    
        // 接口管理
        {path:"/Admin/permissions",name:"admin_permissions",component:()=>import("@/views/Admin/permissions/index")}, 
        {path:"/Admin/permissions/form/:id?",name:"admin_permissions_form",component:()=>import("@/views/Admin/permissions/form")},
        
        // 角色管理
        {path:"/Admin/roles",name:"admin_roles",component:()=>import("@/views/Admin/roles/index")}, 
        {path:"/Admin/roles/form/:id?",name:"admin_roles_form",component:()=>import("@/views/Admin/roles/form")},
    ]},
];