<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><a-icon type="shopping-cart" />我的购物车</div>
                <div class="item check"><a-icon type="car" />物流地址</div>
                <div class="item check"><a-icon type="account-book" />选择支付</div>
                <div class="item check"><a-icon type="check-circle" />支付成功</div>
            </div>
        </div>

        <a-result
        status="success"
        title="订单支付成功！"
        sub-title="如需查看更多订单信息，请前往个人中心查看."
    >
        <template #extra>
        <router-link to="/user">前往个人中心</router-link>
        </template>
    </a-result>
    </div>
</template>

<script>
export default {
    components: {},
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
.step_bar{
    margin:40px 0;
}
a{
    border:1px solid #ddd;
    padding:6px 15px;
    border-radius: 3px;
}
a:hover{
    border-color:#ca151e;
}
</style>