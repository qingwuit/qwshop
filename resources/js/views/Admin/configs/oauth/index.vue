<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            OAuth配置
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-tabs :default-active-key="0" tab-position="left">
                <a-tab-pane :key="0" tab="微信PC">
                    <weixinweb-config />
                </a-tab-pane>
                <a-tab-pane :key="1" tab="微信">
                    <weixin-config />
                </a-tab-pane>
            </a-tabs>
            
            
        </div>
    </div>
</template>

<script>
import weixinwebConfig from "./weixinweb.vue"
import weixinConfig from "./weixin.vue"
export default {
    components: {weixinwebConfig,weixinConfig},
};
</script>