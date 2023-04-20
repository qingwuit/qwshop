import {routeUriType} from '@/plugins/config'
import store from '@/stores'
import R from '@/plugins/http'
import router from '../../../plugins/router'
import { ElMessage } from "element-plus"
import i18n from '@/locales/index'
// initial state
const state = {
    userData:[],
    loginData:[] // {isLogin:false,isLoad:false,tokenName:'',loginName:''}
}

// getters
const getters = {}

// actions
const actions = {
    async updateLogin({state},loginData = []){
    },
    async loginAfter({state},{access_token='',refresh_token='',routeUriIndex=2,path='/Admin/login',isTo=false}){
        
    },
    // 获取用户信息
    async getUser({state},routeUriIndex=2){
        let userName = 'user'
        if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') userName = state.loginData[routeUriIndex].tokenName+'_user'
        return JSON.parse(localStorage.getItem(userName))
    },
    // 获取用户信息存储
    async setUser(){
        // await loginAfter();
    },
    async setLoginData(context,data){
        state.loginData = data
        return state.loginData
    },
    async setUserData({state},data){
        state.userData = data
        return state.userData
    },
    async setLoginDataVal(context,{key='isLogin',value=false,index=2}){
        if(state.loginData[index]) state.loginData[index][key] = value
        return state.loginData[index]
    },
    async getLoginData(){
        return state.loginData
    },
    async getUserData(){
        return state.userData
    },
    // 从服务端获取
    async editUserSer({state},params){
        let routeUriIndex = store.state.load.routeUriIndex
        let postPath = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].loginName!='')?state.loginData[routeUriIndex].loginName:'/'
        let provider = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName=='admin')?state.loginData[routeUriIndex].tokenName:'user'
        const user = await R.post(postPath+'auth/edit',{provider:provider+'s',...params})
        return user
        
    },

    // 从服务端获取
    async getUserSer({state}){
        let userName = 'user'
        let routeUriIndex = store.state.load.routeUriIndex
        if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') userName = state.loginData[routeUriIndex].tokenName+'_user'
        let postPath = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].loginName!='')?state.loginData[routeUriIndex].loginName:'/'
        let provider = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName=='admin')?state.loginData[routeUriIndex].tokenName:'user'
        const user = await R.post(postPath+'auth/info',{provider:provider+'s'})
        localStorage.setItem(userName,JSON.stringify(user) )
        return user
    },
  
}

// mutations
const mutations = {
    async loginAfter(state,{access_token='',refresh_token='',routeUriIndex=2,path='/Admin/login',isTo=false}){
        if(R.isEmpty(access_token)) return // 如果token为空则直接返回首页
        let tokenName = 'token'
        let userName = 'user'
        let loginData = []
        if(!state.loginData[routeUriIndex]){
            const setRouteData = await store.dispatch('load/setRouteIndex',path)
            loginData = setRouteData.loginData
            routeUriIndex = setRouteData.routeUriIndex
        }else{
            loginData = state.loginData
        }
        
        // console.log(loginData)
        // loginData[routeUriIndex].isLogin = true
        // state.userData[routeUriIndex] = {}
        if(loginData[routeUriIndex] && loginData[routeUriIndex].tokenName !== '') tokenName = loginData[routeUriIndex].tokenName+'_token'
        if(loginData[routeUriIndex] && loginData[routeUriIndex].tokenName !== '') userName = loginData[routeUriIndex].tokenName+'_user'
        if(loginData[routeUriIndex]) loginData[routeUriIndex].isLogin = true
        localStorage.setItem(tokenName,access_token)
        
        // 用户信息添加到
        let postPath = (loginData[routeUriIndex] && loginData[routeUriIndex].loginName!='')?loginData[routeUriIndex].loginName:'/'
        let provider = (loginData[routeUriIndex] && loginData[routeUriIndex].tokenName=='admin')?loginData[routeUriIndex].tokenName:'user'
        const user = await R.post(postPath+'auth/info',{provider:provider+'s'})
        // 如果是用户端 且belong大于0则不允许登录
        if(userName == 'user' && user.belong_id > 0){
            ElMessage.error(i18n.global.t('msg.subNotAllowBuy'))
            loginData[routeUriIndex].isLogin = false
            return localStorage.removeItem(tokenName)
        }
        localStorage.setItem(userName,JSON.stringify(user) )
        state.userData[routeUriIndex] = user
        await store.dispatch('login/setLoginDataVal',{key:'isLogin',value:true,index:routeUriIndex})
        if(isTo) router.push(user.defaultUrl||'/')
        
    },

    async setUser(state,user){
        let routeUriIndex = store.state.load.routeUriIndex
        let userName = 'user'
        if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') userName = state.loginData[routeUriIndex].tokenName+'_user'
        localStorage.setItem(userName,JSON.stringify(user) )
    },

    async getUser(state){
        let routeUriIndex = store.state.load.routeUriIndex
        let userName = 'user'
        if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') userName = state.loginData[routeUriIndex].tokenName+'_user'
        return JSON.parse(localStorage.getItem(userName )) 
    },

    // 退出
    async logout(state){
        let routeUriIndex = store.state.load.routeUriIndex
        let provider = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName=='admin')?state.loginData[routeUriIndex].tokenName:'user'
        R.post('/logout',{provider:provider+'s'}).then(item=>{
            
        }).finally(()=>{
            // 跳转
            let routeUriIndex = store.state.load.routeUriIndex
            let userName = 'user'
            let tokenName = 'token'
            if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') tokenName = state.loginData[routeUriIndex].tokenName+'_token'
            if(state.loginData[routeUriIndex] && state.loginData[routeUriIndex].tokenName !== '') userName = state.loginData[routeUriIndex].tokenName+'_user'
            if(state.loginData[routeUriIndex]) state.loginData[routeUriIndex].isLogin = false
            localStorage.removeItem(tokenName)
            localStorage.removeItem(userName)
            let postPath = (state.loginData[routeUriIndex] && state.loginData[routeUriIndex].loginName!='')?state.loginData[routeUriIndex].loginName:'/'
            router.push(postPath+'login')
        })
    }
}

export default {
namespaced: true,
    state,
    getters,
    actions,
    mutations
}