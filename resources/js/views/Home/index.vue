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
export default {
    components: {ShopHead,Banner,BannerBottomAdv,ShopFoot},
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