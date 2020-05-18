<template>
    <div class="shop_head">

        <head-top></head-top>

        <!-- logo and search -->
        <div class="center_top width_center_1200" v-show="center_top">
            <div class="shop_logo float_left">
                <router-link to="/"><img width="180px" height="55px" :src="require('@/public/pc/logo.png')" alt="青梧商城"></router-link>
            </div>
            <div class="shop_top_seach float_left">
                <ul>
                    <li><input class="search_input" v-model="keywords" type="text" placeholder="手机 笔记本电脑 衣服"></li>
                    <li><button class="search_button" type="button" @click="search()"><i class="icon iconfont">&#xeba0;</i></button></li>
                    <li>
                        <div class="index_my_car">
                            <span @click="to_my_store()">我的商城<i class="icon iconfont">&#xe654;</i></span>
                            <span><router-link to="/cart/index">我的购物车<i class="icon iconfont">&#xe602;</i></router-link><div class="shop_car_dot">{{cart_count}}</div></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- nav -->
        <div class="shop_top_nav">
            <div class="width_center_1200">
                <div class="shop_top_nav_left" @mouseover="subnav_show?subnav:subnav=true" @mouseleave="subnav_show?subnav:subnav=false">
                    全部商品
                    <transition name="el-zoom-in-top"><leftBar :change_color="change_color" v-show="subnav"></leftBar></transition>
                </div>
                <div class="shop_top_nav_right">
                    <ul>
                        <li>
                            <router-link to="/">首页</router-link>
                        </li>
                        <li>
                            <router-link to="/goods/seckill">秒杀</router-link>
                        </li>
                        <li>
                            <router-link to="/groupbuy/list/keywords.">拼团</router-link>
                        </li>
                        <li>
                            <router-link to="/integral/index">积分商城</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import leftBar from "@/components/home/public/leftbar.vue"
import HeadTop from "@/components/home/public/head_top.vue"
export default {
    components: {
        leftBar,
        HeadTop,
    },
    props: {
        subnav_show:{
            type:Boolean,
            default:true,
        },
        change_color:{
            type:Boolean,
            default:false,
        },
        cart_change:{
            type:Number,
        }
    },
    data() {
      return {
          subnav:true,
          keywords:'',
          cart_count:0,
          center_top:true,
      };
    },
    watch: {
        subnav_show:function(val){
            this.subnav = val;
        },
        cart_change:function(){
            this.get_cart_count();
        }
    },
    computed: {},
    methods: {
        search:function(){
            if(this.$route.name == 'goods_index'){
                return this.$emit('search_goods',{keywords:this.keywords});
            }
            let params = 'keywords.'+this.keywords;
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
        to_my_store:function(){
            if(this.$isEmpty(localStorage.getItem('token'))){
                this.$message.error('请先登录！');
                return this.$router.push('/user/login');
            }
            this.$get(this.$api.homeIsStore).then(res=>{
                if(res.code == 200){
                    return this.$router.push('/store/'+res.data);
                }else{
                    this.$message.error(res.msg);
                    return this.$router.push('/store/join');
                }
            });
        },
        get_keywords:function(){
            let params = this.$route.params.info;
            if(this.$isEmpty(params)){
                return;
            }
            let paramsArr = params.split('|');
            paramsArr.forEach(res=>{
                let paramsInfo = [];
                paramsInfo = res.split('.');
                if(paramsInfo[0] == 'keywords'){  // 搜索关键词
                    if(paramsInfo.length==2){
                        this.keywords = paramsInfo[1];
                    }else{
                        this.keywords = '';
                    }
                }
            });
        }
    },
    created() {
        this.subnav = this.subnav_show;
        this.get_cart_count();
        this.get_keywords();

        // 监听滚动条
        window.addEventListener('scroll', ()=>{
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            if(scrollTop>210){
                this.center_top = false;
            }else{
                this.center_top = true;
            }
        }, true);
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