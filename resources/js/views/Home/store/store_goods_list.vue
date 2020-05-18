<template>
    <div class="store_goods_list">
        <div class="shop_top"><shop-top ></shop-top></div>
        <div><banner :list="banner_list" :height="'300'"></banner></div>
        <div class="store_body">
            <store-class-product></store-class-product>
        </div>
        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/store/head.vue"
import StoreClassProduct from "@/components/home/store/store_class_product.vue"
import banner from "@/components/home/public/banner.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        StoreClassProduct,
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