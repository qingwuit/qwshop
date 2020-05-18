import Vue from 'vue'
import VueAMap from 'vue-amap'


// 不知道什么原因，必须要清空localStorage 才不报错
const amapKeys = Object.keys(localStorage).filter(key => key.match(/^_AMap_/))

amapKeys.forEach(key => {
  localStorage.removeItem(key)
})

Vue.use(VueAMap)

// 初始化vue-amap
VueAMap.initAMapApiLoader({
// 高德的key
key: '79f3a628c906e1fc7384a6f19d478ae3',
// 插件集合
plugin: ['AMap.Autocomplete',  'AMap.Scale', 'AMap.ToolBar', 'AMap.MapType','AMap.Geolocation'],
v: '1.4.4'
});