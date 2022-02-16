<template>
    <div class="banner_bottom_adv center1200">
        <div class="shop_left_on">
            <ul>
                <li><router-link to="/user/safe">
                <img :src="require('@/assets/Home/001.png').default" alt="">
                <p>实名认证</p>
                </router-link></li>
                <li><router-link to="/integral">
                <img :src="require('@/assets/Home/002.png').default" alt="">
                <p>积分商城</p>
                </router-link></li>
                <li><router-link to="/collectives/eyJrZXl3b3JkcyI6IiJ9">
                <img :src="require('@/assets/Home/003.png').default" alt="">
                <p>优惠团购</p>
                </router-link></li>
                <li><router-link to="/seckills">
                <img :src="require('@/assets/Home/004.png').default" alt="">
                <p>秒杀活动</p>
                </router-link></li>
                <li><router-link to="/user">
                <img :src="require('@/assets/Home/005.png').default" alt="">
                <p>公告</p>
                </router-link></li>
                <li><router-link to="/store/join">
                <img :src="require('@/assets/Home/006.png').default" alt="">
                <p>入驻商家</p>
                </router-link></li>
            </ul>
        </div>
        <div class="shop_right_on">
            <ul>
                <li v-for="(v,k) in index_adv" :key="k"><router-link :to="v.url||'#'"><el-image lazy :src="v.image" style="width:312px;height:160px" :alt="v.name" fit="cover" /></router-link></li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        // 传过来的数据
        index_adv:{
            type:Array,
            default:()=>{
                return []
            },
        }
    },
};
</script>
<style lang="scss" scoped>
.banner_bottom_adv{
    margin-bottom: 22px;
    margin-top:22px;
    .shop_left_on{
        float: left;
        width: 240px;
    }
    .shop_left_on ul li {
        width: 78px;
        height: 80px;
        text-align: center;
        background: #f2f2f2;
        box-sizing: border-box;
        padding-top: 10px;
        float: left;
        font-size: 12px;
        line-height: 24px;
    }
    .shop_left_on ul li:first-child, .shop_left_on ul li:nth-child(2) {
        border-right: 1px solid #dedede;
        border-bottom: 1px solid #dedede;
    }
    .shop_left_on ul li:nth-child(3) {
        border-bottom: 1px solid #dedede;
    }
    .shop_left_on ul li:nth-child(4), .shop_left_on ul li:nth-child(5) {
        border-right: 1px solid #dedede;
    }
    .shop_left_on ul li:hover{
        background: #e1e1e1;
    }
    .shop_right_on ul li {
        float: left;
        margin-right: 10px;
        width: 312px;
        height: 160px;
        -webkit-transition: all .2s linear;
        transition: all .2s linear;
    }
    .shop_right_on ul li:nth-child(3) {
        margin-right: 0;
    }
    .shop_right_on ul li:hover{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    }
}
</style>