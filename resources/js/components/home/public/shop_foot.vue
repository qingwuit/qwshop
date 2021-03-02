<template>
    <div class="shop_foot width_center_1200">
        <!-- <shop-index-adv :adv="adv"></shop-index-adv> -->
        <ul>
            <li><router-link to="">500强企业 品质保证</router-link></li>
            <li><router-link to="">7天退货 15天换货</router-link></li>
            <li><router-link to="">99元起免运费</router-link></li>
            <li><router-link to="">448家维修网点 全国联保</router-link></li>
        </ul>
        <div class="foot_copyright">
            <p>448家维修网点 全国联保 隐私政策 服务协议 Copyright © 2012-2016 VMALL.COM 版权所有 保留一切权利</p>
            <p>公安备案 苏公网安备 32011402010009号 | {{common.common.icp}} | 增值电信业务经营许可证：苏B2-20130048 | 网络文化经营许可证：苏网文[2015] 1599-026号</p>
        </div>
        <a-back-top />
    </div>
</template>

<script>
// import ShopIndexAdv from "@/components/home/public/shop_index_adv.vue"
import {mapState} from 'vuex'
export default {
    components: {
        // ShopIndexAdv,
    },
    props: {},
    data() {
      return {
          adv:{},
          info:{},
      };
    },
    watch: {},
    computed: {
        ...mapState('homeCommon',['common'])
    },
    methods: {
        
    },
    created() {
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.shop_foot{
    padding-top: 10px;
    text-align: center;
    ul{
        margin:40px auto 0 auto;
        width: 664px;
        border-top: 1px solid #efefef;
        padding-top: 40px;
    }
    ul li{
        float: left;
        margin-right: 40px;
        margin-bottom: 60px;
        font-size: 14px;
        a:hover{
            color:#ca151e;
        }
    }
    ul li:last-child{
        margin-right: 0px;
    }
    .foot_copyright{
        clear: both;
        font-size: 12px;
        line-height: 35px;
        padding-bottom: 20px;
    }
}
</style>