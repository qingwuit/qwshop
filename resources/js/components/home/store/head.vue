<template>
    <div class="shop_head">

        <head-top></head-top>

        <!-- logo and search -->
        <div class="center_top width_center_1200">
            <div class="shop_logo float_left">
                <router-link to="/"><img width="180px" height="55px" src="/pc/logo.png" alt="青梧商城"></router-link>
            </div>
            <div class="shop_top_seach float_left">
                <ul>
                    <li><input class="search_input" v-model="keywords" type="text" placeholder="手机 笔记本电脑 衣服"></li>
                    <li><button class="search_button" type="button" @click="search()"><i class="icon iconfont">&#xeba0;</i></button></li>
                    <li>
                        <div class="index_my_car">
                            <span><router-link to="/">我的商城<i class="icon iconfont">&#xe654;</i></router-link></span>
                            <span><router-link to="/cart/index">我的购物车<i class="icon iconfont">&#xe602;</i></router-link><div class="shop_car_dot">{{cart_count}}</div></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- nav -->
        <div class="shop_top_nav">
            <div class="width_center_1200">
                <div class="shop_top_nav_left">
                    <router-link :to="'/store/'+$route.params.id+'/class/0'">全部商品</router-link>
                </div>
                <div class="shop_top_nav_right">
                    <ul>
                        <li>
                            <router-link :to='"/store/"+$route.params.id'>首页</router-link>
                        </li>
                        <li v-for="(v,k) in store_goods_class" :key="k">
                            <router-link :to='"/store/"+$route.params.id+"/class/"+v.id'>{{v.name}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <chat :sellerId="parseInt(this.$route.params.id)" :miniItem="true"></chat>
    </div>
</template>

<script>
import HeadTop from "@/components/home/public/head_top.vue"
import chat from "@/components/chat/index.vue";
export default {
    components: {
        HeadTop,
        chat,
    },
    props: {
        subnav_show:{
            type:Boolean,
            default:true,
        },
        change_color:{
            type:Boolean,
            default:false,
        }
    },
    data() {
      return {
          subnav:true,
          keywords:'',
          cart_count:0,
          store_goods_class:[],
      };
    },
    watch: {
        subnav_show:function(val){
            this.subnav = val;
        }
    },
    computed: {},
    methods: {
        search:function(){
            let params = 'keywords_'+this.keywords;
            this.$router.push('/goods/params/'+params);
        },
        get_cart_count:function(){
            if(this.$isEmpty(localStorage.getItem('token'))){
                return;
            }
            this.$get(this.$api.homeGetCartCount).then(res=>{
                this.cart_count = res.data;
            });
        },
        get_store_goods_class:function(){
            this.$post(this.$api.homeGetStoreGoodsClass,{user_id:this.$route.params.id}).then(res=>{
                this.store_goods_class = res.data;
            });
        }
    },
    created() {
        this.subnav = this.subnav_show;
        this.get_cart_count();
        this.get_store_goods_class();
    },
    mounted() {
        
    }
};
</script>
<style lang="scss" scoped>
.shop_head{
    z-index: 666;
    position: fixed;
    background: #fff;
    left:0;
    right: 0;
}

.center_top:after{
    display: block;
    clear:both;
    content:'';
}

.shop_logo{
    margin-top: 40px;
    margin-bottom: 40px;
}
.shop_top_nav{
    height: 40px;
    background:#333;
    font-size: 14px;
    line-height: 40px;
    color:#fff;
    .shop_top_nav_left{
        float: left;
        width: 240px;
        background:#ca151e;
        box-sizing: border-box;
        padding-left: 15px;
        position: relative;
        a{
            color:#fff;
        }
    }
    .shop_top_nav_right{
        padding-left: 20px;
        float: left;
        ul li{
            float: left;
        }
        ul li a{
            line-height: 40px;
            padding:0 30px;
            color:#fff;
            display: block;
        }
        ul li a:hover{
            background: #000;
        }
    }
}
.shop_top_seach{
    margin-top: 44px;
    margin-left: 200px;
    ul li{
        float: left;
    }
    .search_input{
        width: 434px;
        height: 38px;
        padding: 10px;
        outline: 0;
        border: 1px solid #efefef;
        border-right: none;
        float: left;
        box-sizing: border-box;
        font-size: 12px;
    }
    .search_button{
        border: 1px solid #efefef;
        float: left;
        width: 38px;
        height: 38px;
        background: #fff;
        outline: 0;
        text-align: center;
    }
    .search_button:hover{
        color:#fff;
        background:#333;
    }
    .index_my_car{
        height: 38px;
        border: 1px solid #efefef;
        margin-left: 30px;
        box-sizing: border-box;
        font-size: 12px;
        line-height: 38px;
        padding: 0 20px;
        span:hover{
            color:#ca151e;
        }
        span{
            margin-right: 20px;
            position: relative;
            i{
                font-size: 13px;
                margin-left: 6px;
            }
            a:hover{
                color:#ca151e;
            }
            .shop_car_dot {
                position: absolute;
                top: -24px;
                line-height: 16px;
                background: #ca151e;
                padding: 0 4px;
                color: #fff;
                border-radius: 4px;
                right: -10px;
            }
        }
        span:last-child{
            margin-right: 0;
            i{
                font-size: 14px;
            }
        }
        
    }
}
</style>