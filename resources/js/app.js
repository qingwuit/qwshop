window._ = require('lodash');
import {createApp} from 'vue'
import store from '@/stores'
import ElementPlus from 'element-plus'
import i18n from '@/locales/index'
import router from '@/plugins/router'
import R from '@/plugins/http'
import App from '@/views/App.vue'
import '@/plugins/css/style.css'
import qInput from '@/components/common/input'

const ElementMessage = R.loadLocaleMessages()
const localeElementPlus = require(`@/locales/element/${ElementMessage[R.getLocalesName()]?R.getLocalesName():'zh-cn'}`)
let elementInit = { size: 'default', zIndex: 3000, locale: localeElementPlus.default }

router.afterEach(() => {
    window.scrollTo(0,0);
});

// 挂载初始化
const app = createApp(App)
app.component('q-input',qInput)
app.use(i18n)
.use(store)
.use(router)
.use(ElementPlus,elementInit)
app.config.globalProperties.R = R
app.mount('#app')