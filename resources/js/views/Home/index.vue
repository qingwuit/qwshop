<template>
    <div class="home">
        <div class="shop_head"><shop-head /></div>
        <Banner />
        <banner-bottom-adv :index_adv="banner_bottom_adv" />
        <shop-foot />
    </div>
</template>

<script>
import ShopHead from '@/components/home/public/head'
import Banner from '@/components/home/public/banner'
import BannerBottomAdv from "@/components/home/public/banner_bottom_adv"
import ShopFoot from "@/components/home/public/shop_foot"
import VueLazyload from 'vue-lazyload' // 图片懒加载
Vue.use(VueLazyload)
export default {
    components: {ShopHead,Banner,BannerBottomAdv,ShopFoot},
    props: {},
    data() {
      return {
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_index(){
            this.$get(this.$api.homeIndex).then(res=>{
                this.list = res.data;
            })
        }
    },
    created() {
        this.get_index();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>