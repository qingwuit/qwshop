<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>支付配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-tabs tab-position="left" style="min-height: 200px;">
                    <el-tab-pane label="微信H5"><wxpayH5></wxpayH5></el-tab-pane>
                    <el-tab-pane label="微信APP"><wxpayApp></wxpayApp></el-tab-pane>
                    <el-tab-pane label="微信JSPAPI"><wxpayJsApi></wxpayJsApi></el-tab-pane>
                    <el-tab-pane label="微信小程序"><wxpayMini></wxpayMini></el-tab-pane>
                    <el-tab-pane label="支付宝H5"><alipayH5></alipayH5></el-tab-pane>
                    <el-tab-pane label="支付宝APP"><alipayApp></alipayApp></el-tab-pane>
                    <el-tab-pane label="支付宝PC"><alipayPc></alipayPc></el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </div>
</template>

<script>
import wxpayH5 from '@/components/admin/pay/wxpay_h5'
import wxpayApp from '@/components/admin/pay/wxpay_app'
import wxpayJsApi from '@/components/admin/pay/wxpay_jsapi'
import wxpayMini from '@/components/admin/pay/wxpay_mini'
import alipayH5 from '@/components/admin/pay/alipay_h5'
import alipayPc from '@/components/admin/pay/alipay_pc'
import alipayApp from '@/components/admin/pay/alipay_app'
export default {
    components: {
        wxpayH5,
        wxpayApp,
        wxpayJsApi,
        wxpayMini,
        alipayH5,
        alipayPc,
        alipayApp,
    },
    props: {},
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {},
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>