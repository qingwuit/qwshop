import R from '@/plugins/http'
// initial state
const state = {
    common:{
        classes:[],
        brands:[],
        common:{},
        cart:0,
        ip:'',
        load:false,
    },
    isHome:false,
}

// getters
const getters = {}

// actions
const actions = {

    // 加载网站配置信息
    async loadCommon({state}){
        if(state.load) return
        state.common = await R.get('/common')
        state.common.load = true
        const ip = localStorage.getItem('ip')
        if(!ip) localStorage.setItem('ip',state.common.ip)
    },

    // 加载购物车
    async loadCart({state}){
        state.common.cart = await R.get('/cart_count')
    },

    // 设置是否在首页
    async setHome({state},data){
        state.isHome = data
    }
    
}

// mutations
const mutations = {

}

export default {
namespaced: true,
    state,
    getters,
    actions,
    mutations
}