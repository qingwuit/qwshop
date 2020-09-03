<template>
    <div class="home">
        <div class="shop_head3"><shop-head /></div>

        <transition name="slide-fade">
            <router-view></router-view>
        </transition>

        <!-- 底部 -->
        <shop-foot />
    </div>
</template>

<script>
import ShopHead from '@/components/home/public/head'
import ShopFoot from "@/components/home/public/shop_foot"
export default {
    components: {ShopHead,ShopFoot},
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