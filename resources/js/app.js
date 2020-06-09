// require('./bootstrap');
// window.Vue = require('vue');
// window.VueRouter = require('vue-router');

import Vue from 'vue'
import router from '@/plugins/router'
import Antd from 'ant-design-vue'
import App from '@/views/App'
import {api} from '@/plugins/api'
import '@/plugins/function'

import 'babel-polyfill' // 兼容IE

Vue.prototype.$api=api;

Vue.config.productionTip = false;
Vue.use(Antd);

Vue.use(VueRouter)
const app = new Vue({
    el: '#app',
    components: { App },
    router,
});