<template>
    <div class="home">
        <div class="shop_head"><shop-head /></div>
        <transition name="slide-fade">
            <router-view></router-view>
        </transition>
        <shop-foot />
    </div>
</template>

<script>
import ShopHead from '@/components/home/public/head'
import BannerBottomAdv from "@/components/home/public/banner_bottom_adv"
import ShopFoot from "@/components/home/public/shop_foot"
export default {
    components: {ShopHead,BannerBottomAdv,ShopFoot},
    props: {},
    data() {
      return {
      };
    },
    watch: {

    },
    computed: {
    },
    methods: {
        
    },
    created() {
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>