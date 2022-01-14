import { createStore } from 'vuex'

// 初始化
import init from '@/stores/modules/common'
import load from '@/stores/modules/common/load'
import login from '@/stores/modules/common/login'

// 当前环境
const debug = process.env.NODE_ENV !== 'production'

// 实例化Vuex
export default new createStore({
    modules: {
        init,
        load,
        login
    },
    strict: false
})