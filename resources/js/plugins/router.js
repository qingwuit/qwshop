import {createRouter,createWebHistory} from 'vue-router'
import {routeUriType,exclude,_import} from '@/plugins/config'
import store from '@/stores'
import baseView from '@/components/common/base' // 后台基础框架
import {baseRoute} from '@/plugins/routes/index'

// 自定义路由
const routes = baseRoute

// const routeNowPath = createWebHistory().state.current??'';

// 接口得到的路由信息
export function createRouteTmp(permissionRouteTmp = [],index=2){
    let tmp = []
    let openView = [] // 非子栏目
    permissionRouteTmp.map(item=>{
        if(item.view  != '' || item.apis != ''){
            // is_open = 2 则是跳转外链
            if(item.is_open == 0) tmp.push({name:item.apis,path:item.apis,component:_import(item.view),meta:{title:item.name}})
            if(item.is_open == 1) openView.push({name:item.apis,path:item.apis,component:_import(item.view),meta:{title:item.name}})
        }else{
            if(item.children && item.children.length>0) openView = openView.concat(createRouteTmp(item.children))
        }
    })
    const tmpRoute = {
        // path: '/Admin/index',
        // redirect: { name: '/Admin/xx' },
        component: baseView,
        children: [
            ...tmp,
        ]
    }
    openView.push(tmpRoute)
    return openView
}

const router = createRouter({
    history: createWebHistory(),
    routes,
    sensitive: true
})

// 路由加之前先获取数据
router.beforeEach( async  (to, from, next) => {
    NProgress.start()
    try {

        // 判断是否在首页
        let isHome = false
        if(to.fullPath == '/') isHome = true
        await store.dispatch('init/setHome',isHome)

        // 路由类型
        let loginData = []
        let userData = []
        let routeUriIndex = 2
        const setRouteData = await store.dispatch('load/setRouteIndex',to.path)
        loginData = setRouteData.loginData
        routeUriIndex = setRouteData.routeUriIndex

        // 最后一个是用户的
        loginData.push({isLogin:false,isLoad:false,tokenName:'',loginName:''})
        userData.push({})
        
        // 轮询查询是否存在Token 存在则表示已经登录
        loginData.map((item,key)=>{
            let allTokenName = item.tokenName+'_token'
            let allTokenUser = item.tokenName+'_user'
            if(item.tokenName == ''){
                allTokenName = 'token'
                allTokenUser = 'user'
            }
                
            if(localStorage.getItem(allTokenName) === undefined || localStorage.getItem(allTokenName) === null){
                loginData[key].isLogin = false
                userData[key] = {}
            }else{
                loginData[key].isLogin = true
                try{
                    userData[key] = JSON.parse(localStorage.getItem(allTokenUser))
                }catch(error){
                    console.log(error)
                }
                
            }
        })

        // 获取已存状态
        let storeLoginData = await store.dispatch('login/getLoginData')
        let storeUserData = await store.dispatch('login/getUserData')
        if(storeLoginData.length<=0) storeLoginData = await store.dispatch('login/setLoginData',loginData)
        if(storeUserData.length<=0) storeUserData = await store.dispatch('login/setUserData',userData)

        // 无需菜单接口和用户接口的页面
        if(_.indexOf(exclude,to.path) != -1 || (routeUriType.filter((item)=>to.path.indexOf(item) > -1)).length==0){
            NProgress.done()
            return next()
        }
        
        if(!loginData[routeUriIndex].isLogin){
            NProgress.done()
            router.addRoute({path: "/:catchAll(.*)",name: '404',component: _import('Error/404')})
            return next({path:routeUriType[routeUriIndex]+'login'})
        }
        
        // 开始加载菜单和用户状态
        let isNext = false
        await Promise.all(storeLoginData.map(async (item,key)=>{
            // 菜单
            if(!item.isLoad && routeUriIndex == key){
                const menuData = await store.dispatch('load/loadMenus', routeUriIndex)
                const routeResp = createRouteTmp(menuData)
                routeResp.map(item=>{
                    router.addRoute(item)
                })
                isNext = true
            }
        }))
        await store.commit('load/setNowRoutePoint', to.fullPath)
        // console.log(router.getRoutes())
        NProgress.done()
        if(isNext){
            router.addRoute({path: "/:catchAll(.*)",name: '404',component: _import('Error/404')})
            return next({ ...to, replace: true })
            return router.push(to.fullPath) //next({ ...to, replace: true })
        }
        next()
        
    } catch (error) {
        console.error(error)
        NProgress.done()
        next(false)
    }
})

router.afterEach(() => {
    // 结束进度条
    NProgress.done()
})

export default router
