import Vue from 'vue'
import Vuex from 'vuex'


import sellerLogin from '@/store/modules/seller/login'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'
export default new Vuex.Store({
    modules: {
        sellerLogin,
    },
    strict: false,
})