<template>
    <div class="qingwu">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div><banner height="350px" :list="banner_list"></banner></div>
        <div class="store_join width_center_1200">
            <div class="store_join_left">
                <h4>入驻申请</h4>
                <p>未填写入驻申请资料时进行入驻资料填写</p>
                <div class="join_btn" @click="$router.push('/store/join_1')">我要入驻</div>
            </div>
            <div class="store_join_left">
                <h4>入驻进度</h4>
                <p>店铺还未开通时了解店铺开通的最新状况</p>
                <div class="join_btn" @click="$router.push('/store/join_1')">查看入驻进度</div>
            </div>
        </div>
        <div class="join_over width_center_1200">
            <div class="join_over_title"><el-divider>入驻流程</el-divider><p>ADVANCE REGISTRATION PROCESS</p></div>
            <div class="join_bzt">
                <el-steps :active="0" simple>
                    <el-step title="提交入驻资料" icon="el-icon-edit"></el-step>
                    <el-step title="商家等待审核" icon="el-icon-unlock"></el-step>
                    <el-step title="完善店铺信息" icon="el-icon-tickets"></el-step>
                    <el-step title="店铺上线" icon="el-icon-goods"></el-step>
                </el-steps>
            </div>
        </div>
        <div class="join_over width_center_1200">
            <!-- <div class="join_over_title"><el-divider>热招项目</el-divider><p>BUSINESS CATEGORY</p></div> -->
            <div class="join_bzt">
                <ul>
                    <li style="background:#fff">家用电器</li>
                    <li style="background:#fff">手机、数码、通信</li>
                    <li style="background:#fff">家居、家具、家装、厨具</li>
                    <li style="background:#fff">男装、女装、内衣</li>
                    <li style="background:#fff">鞋靴、箱包、钟表、奢侈品</li>
                    <li style="background:#fff">个人化妆、清洁用品</li>
                    <li style="background:#fff">增值服务 & 其他</li>
                    <li style="background:#fff">增值服务 & 其他</li>
                </ul>
            </div>
        </div>
        <shop-foot></shop-foot>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import banner from "@/components/home/public/banner.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        banner,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          banner_list:[{adv_image:'https://dsmall.csdeshang.com/uploads/admin/Storejion/store_joinin_1.jpg'}],
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_join_index_adv(){
            this.$get(this.$api.homeGetJoinIndexAdv).then(res=>{
                this.banner_list = res.data.adv;
            });
        },
    },
    created() {
        this.get_join_index_adv();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.store_join{
    margin-top:40px;
    margin-bottom: 40px;
    
}
.store_join:after{
    display:block;
    clear:both;
    content:'';
}
.store_join_left{
    float: left;
    width: 50%;
    box-sizing: border-box;
    text-align: center;
    padding-top:40px;
    border-right: 1px solid #efefef;
    padding-bottom: 30px;
    h4{
        line-height: 40px;
        font-size: 22px;
        font-weight: normal;
    }
    p{
        color:#999;
        line-height: 40px;
    }
    .join_btn{
        color:#fff;
        width: 200px;
        line-height: 40px;
        margin: 30px auto 0 auto;
        background:#f70;
    }
}
.store_join_left:last-child{
    border-right: none;
}
.join_over_title{
    padding-top:40px;
    width: 300px;
    text-align: center;
    margin:0 auto;
    .el-divider__text{
        font-size: 22px;
    }
    p{
        font-size: 12px;
        color:#999;
    }
}
.join_bzt{
    margin-top:50px;
    ul li{
        float: left;
        width: 285px;
        margin-right: 20px;
        height: 50px;
        margin-bottom: 30px;
        line-height: 50px;
        color:#666;
        border-radius: 3px;
        text-align: center;
        border:1px solid #efefef;
        box-sizing: border-box;
        // box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    }
    ul li:nth-child(4n){
        margin-right: 0;
    }
}
</style>