import {loginBaseData,routeUriType} from '@/plugins/config'
import R from '@/plugins/http'
import store from '@/stores'
import {createRouteTmp} from '@/plugins/router'
// initial state
const state = {
   menuData:[],
   routeUriIndex:2,
   nowRoutePoint:[],
   nowRoutePointIds:[],
   moduleIndex:0,
   routeMenuName:''
}

// getters
const getters = {}

// actions
const actions = {
    // 加载菜单
    async loadMenus(context,routeUriIndex = 2){
        let loginData = loginBaseData()
        const Url = loginData[routeUriIndex].loginName+'load_menu'
        const menuData = await R.get(Url,{type:'getChildren'}) || []
        await Promise.all(loginData.map(async (item,key)=>{
            if(!state.menuData[key]){
                state.menuData[key] = []
            }else{
                await store.dispatch('login/setLoginDataVal', {key:'isLoad',value:true,index:key})
            }
            if(key == routeUriIndex){
                state.menuData[key] = menuData
                await store.dispatch('login/setLoginDataVal', {key:'isLoad',value:true,index:key})
            }
        }))
        
        return menuData
    },

    // 设置路由
    async loadRoute({state}){
        let findUrl = false
        let routeUrl = ''
        const getRouteUrl = (data)=>{
            if(findUrl) return
            data.map(item=>{
                if(findUrl) return
                if(item.view  != '' && item.apis != ''){
                    // 如果不是外链则返回
                    if(item.apis.indexOf('http://') == -1 && item.apis.indexOf('https://') == -1){
                        routeUrl = item.apis
                        findUrl = true
                    }
                }else{
                    if(item.children && item.children.length>0) getRouteUrl(item.children)
                }
            })
        }
        const menuData = await store.dispatch('load/loadMenus', state.routeUriIndex)
        getRouteUrl(menuData)
        return routeUrl
    },

    // 获取当前的菜单
    async getMenus(context,routeUriIndex = 2){
        if(routeUriIndex === -1) return state.menuData[state.routeUriIndex] || []
        return state.menuData[routeUriIndex] || []
    },

    // 设置当前的菜单
    async setMenus(context,data=[],routeUriIndex = 2){
        if(routeUriIndex === -1) return state.menuData[state.routeUriIndex] = data
        return state.menuData[routeUriIndex] = data
    },

    // 设置当前路由类型
    async setRouteIndex(context,path='/Admins/'){
        let loginData = []
        let userData = []
        routeUriType.map((item,key)=>{
            let tokenName = ''
            tokenName = (routeUriType[key].replaceAll('/','')).toLowerCase()
            loginData.push({isLogin:false,isLoad:false,tokenName:tokenName,loginName:routeUriType[key]})
            userData.push({})
            
            if(path.indexOf(item) != -1){
                state.routeUriIndex = key
            }
        })

        return {loginData:loginData,routeUriIndex:state.routeUriIndex,userData:userData}
    },

    // 获取用户信息
    async getUser(context){
        return await store.dispatch('login/getUser', state.routeUriIndex)
    }

   

}

// mutations
const mutations = {
    // 设置路由定位
    async setNowRoutePoint(state,path){
        let itemMenu = []
        const menusDatas = state.menuData[state.routeUriIndex]
        // let itemSubMenu = null
        const menusLoop = (menuDatas,deep=0)=>{
            let itemSubMenu = false
            menuDatas.map((item,key)=>{
                if(item.apis == path && deep!=0){
                    itemMenu.push({key:key,item:item})
                    itemSubMenu = true
                }
                if(menuDatas[key] && menuDatas[key].children && menuDatas[key].children.length>0){
                    if(menusLoop(menuDatas[key].children,1)){
                        itemSubMenu = true
                        itemMenu.push({key:key,item:item})
                    }
                    
                }
            })
            return itemSubMenu
        }
        menusLoop(menusDatas)
        state.nowRoutePoint = itemMenu
        state.nowRoutePointIds = []
        itemMenu.map(item=>{
            state.nowRoutePointIds.push(item.item.id+'')
        })
        if(!itemMenu[itemMenu.length-1]) return 0
        state.moduleIndex = itemMenu[itemMenu.length-1].key
        state.routeMenuName = itemMenu[0].item.name
        return itemMenu[itemMenu.length-1].key||0
    },

    // 设置模块的点位
    async setRoutePointIndex(state,index){
        state.moduleIndex = index
    },

    // 设置当前的菜单
    async setMenus(state,data=[],routeUriIndex = 2){
        if(routeUriIndex === -1) return state.menuData[state.routeUriIndex] = data
        return state.menuData[routeUriIndex] = data
    },

    // 设置当前路由类型
    async setRouteIndex(state,path='/Admins/'){
        let loginData = []
        routeUriType.map((item,key)=>{
            let tokenName = ''
            tokenName = (routeUriType[key].replaceAll('/','')).toLowerCase()
            loginData.push({isLogin:false,isLoad:false,tokenName:tokenName,loginName:routeUriType[key]})
            
            if(path.indexOf(item) != -1){
                state.routeUriIndex = key
            }
        })
        
        return {loginData:loginData,routeUriIndex:state.routeUriIndex}
    },
}

export default {
namespaced: true,
    state,
    getters,
    actions,
    mutations
}