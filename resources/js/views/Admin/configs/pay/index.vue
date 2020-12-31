<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            支付配置
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-tabs :default-active-key="0" tab-position="left">
                
                <a-tab-pane :key="0" tab="微信H5(wap)">
                    <wechat-h5 />
                </a-tab-pane>
                <a-tab-pane :key="1" tab="微信公众号">
                    <wechat-public />
                </a-tab-pane>
                <a-tab-pane :key="2" tab="微信小程序">
                    <wechat-mini />
                </a-tab-pane>
                <a-tab-pane :key="3" tab="微信App">
                    <wechat-app />
                </a-tab-pane>
                <a-tab-pane :key="4" tab="微信扫码(PC)">
                    <wechat-scan />
                </a-tab-pane>
                <a-tab-pane :key="5" tab="支付宝H5(wap)">
                    <ali-h5 />
                </a-tab-pane>
                <a-tab-pane :key="6" tab="支付宝App">
                    <ali-app />
                </a-tab-pane>
                <a-tab-pane :key="7" tab="支付宝扫码(PC)">
                    <ali-scan />
                </a-tab-pane>
                <a-tab-pane :key="8" tab="支付宝小程序">
                    <ali-mini />
                </a-tab-pane>
            </a-tabs>
            
            
        </div>
    </div>
</template>

<script>
import wechatH5 from "./wechat/wechat_h5"
import wechatApp from "./wechat/wechat_app"
import wechatMini from "./wechat/wechat_mini"
import wechatPublic from "./wechat/wechat_public"
import wechatScan from "./wechat/wechat_scan"
import aliApp from "./ali/ali_app"
import aliH5 from "./ali/ali_h5"
import aliMini from "./ali/ali_mini"
import aliScan from "./ali/ali_scan"
export default {
    components: {wechatH5,wechatApp,wechatMini,wechatPublic,wechatScan,aliApp,aliH5,aliMini,aliScan},
};
</script>
<style lang="scss" scoped>

</style>