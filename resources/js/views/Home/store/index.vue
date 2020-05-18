<template>
    <div class="qingwu">
        <div class="shop_top"><shop-top ></shop-top></div>
        <div><banner :list="banner_list" height="300px"></banner></div>
        <div class="store_body">
            <store-index-product></store-index-product>
        </div>
        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/store/head.vue"
import StoreIndexProduct from "@/components/home/store/store_index_product.vue"
import banner from "@/components/home/public/banner.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        StoreIndexProduct,
        banner,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          banner_list:[{adv_image:'/pc/default_store_adv.jpg'}],
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
.store_body{
    padding: 50px 0;
    background: #fff;
}
</style>