<template>
    <div class="qingwu">
        <div class="shop_top"><shop-top :subnav_show="subnav_show"></shop-top></div>
        <div><banner :list="banner_list"></banner></div>
        <banner-bottom-adv :index_adv="banner_bottom_adv"></banner-bottom-adv>
        <div class="shop_content">
            <seckill :list="seckill_goods_list" :info="seckill_info"></seckill>
            <div v-for="(v,k) in goods_list" :key="k">
                <shop-index-adv :adv="goods_list_bottom_adv[k]"></shop-index-adv>
                <shop-product-block :class_info="v.class_info"  :goods_list="v.list" :adv="goods_list_left_adv[k]"></shop-product-block>
            </div>
            <shop-index-adv></shop-index-adv>
            <shop-product-block></shop-product-block>
            <!-- <shop-index-adv></shop-index-adv>
            <shop-product-block></shop-product-block> -->
        </div>
        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import banner from "@/components/home/public/banner.vue"
import BannerBottomAdv from "@/components/home/public/banner_bottom_adv.vue"
import seckill from "@/components/home/public/seckill.vue"
import ShopIndexAdv from "@/components/home/public/shop_index_adv.vue"
import ShopProductBlock from "@/components/home/public/shop_product_block.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        banner,
        BannerBottomAdv,
        seckill,
        ShopIndexAdv,
        ShopProductBlock,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          banner_list:[],  // 幻灯片
          banner_bottom_adv:[], // 三联广告图片
          subnav_show:true,
          goods_list:[],
          goods_list_left_adv:[],
          goods_list_bottom_adv:[],
          seckill_goods_list:[],
          seckill_info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 获取首页信息
        get_index_info:function(){
            this.$get(this.$api.homeGetIndexInfo).then(res=>{
                this.banner_list = res.data.banner.adv;
                this.banner_bottom_adv = res.data.banner_bottom_adv.adv;
                this.goods_list = res.data.goods_list;
                this.goods_list_left_adv = res.data.goods_list_left_adv.adv;
                this.goods_list_bottom_adv = res.data.goods_list_bottom_adv.adv;
                this.seckill_goods_list = res.data.seckill_goods;
                this.seckill_info = res.data.seckill_info;
            });
        },
    },
    created() {
        this.get_index_info();
    },
    mounted() {
        // 监听滚动条
        window.addEventListener('scroll', ()=>{
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            if(scrollTop>20){
                this.subnav_show = false;
            }else{
                this.subnav_show = true;
            }
        }, true);
    }
};
</script>
<style lang="scss" scoped>
.shop_content{
    background: #f5f5f5;
    padding:20px 0 60px 0;
    margin-top:40px;
}


</style>