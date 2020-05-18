/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

import Vue from 'vue'
import router from './router'
import App from './App.vue'

import './plugins/vueamap.js' //  vue-amap 高德地图
import './plugins/element.js' //  elemnt组件
import {post,get,put,toJson,isEmpty} from './plugins/http.js' // 请求方式中间件
import {api} from './plugins/api.js'  // API 链接
import {formatDate,formatGoods} from './plugins/function.js' // 公共方法
import './public/fonts/iconfont.css' // 阿里图标
import './public/style.css' // 公共CSS

import 'babel-polyfill' // 兼容IE

//定义全局变量
Vue.prototype.$api=api;
Vue.prototype.$post=post;
Vue.prototype.$get=get;
Vue.prototype.$put=put;
Vue.prototype.$toJson=toJson;
Vue.prototype.$isEmpty=isEmpty;
Vue.prototype.$formatGoods=formatGoods;

// 时间格式化
Vue.filter('formatDate', function (time) {
    var date = new Date(time*1000);
    return formatDate(date, 'yyyy-MM-dd hh:mm');
});

Vue.filter('formatDateAuto', function (time,str) {
    var date = new Date(time*1000);
    return formatDate(date, str);
});

Vue.config.productionTip = false

// 跳转后返回顶部
router.afterEach(() => {
    window.scrollTo(0,0);
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
    components: { App },
});
