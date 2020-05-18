<template>
    <div class="create_order">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>

        <div class="join_over width_center_1200">
            <div class="join_bzt">
                <el-steps :active="3" finish-status="process" simple >
                    <el-step title="我的购物车" icon="el-icon-shopping-cart-full"></el-step>
                    <el-step title="物流地址" icon="el-icon-map-location"></el-step>
                    <el-step title="选择支付" icon="el-icon-mouse"></el-step>
                    <el-step title="支付成功" icon="el-icon-check"></el-step>
                </el-steps>
            </div>
        </div>

        <div class="pay_success_icon width_center_1200">
            <i class="icon iconfont">&#xe630;</i>
            <p>支付成功</p>
            <p class="last_info">前往用户中心 <router-link to="/user/order">查看</router-link></p>
        </div>

        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    props: {},
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {
        
    },
    created() {

    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

.join_bzt{
    margin-top:40px;
}
.pay_success_icon{
    margin:180px auto;
    text-align: center;
    line-height: 60px;
    i{
        font-size: 60px;
        color:#67c23a;
    }
    p.last_info{
        line-height: 20px;
        color:#666;
        font-size: 14px;
        a{
            color:#666;
        }
        a:hover{
            color:#ca151e;
        }
    }
}

</style>