import Vue from 'vue'
import Vuex from 'vuex'

// 商家
import sellerLogin from '@/store/modules/seller/login'

// PC端
import homeLogin from '@/store/modules/home/login' // 登录
import homeStore from '@/store/modules/home/store' // 商家
import homeCommon from '@/store/modules/home/common' // 公共信息

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'
export default new Vuex.Store({
    modules: {
        sellerLogin,

        homeLogin,
        homeStore,
        homeCommon,
    },
    strict: false,
})