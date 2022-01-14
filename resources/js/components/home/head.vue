<template>
    <div class="head">
        <!-- logo and search -->
        <div class="center_top center1200" v-show="data.centerTop">
            <div class="shop_logo float_left">
                <router-link to="/"><img width="242px" height="74px" :title="data.common.index_name||''" :src="data.common.logo||require('@/assets/Home/logo.png').default" /></router-link>
            </div>
            <div class="shop_top_seach float_left">
                <ul>
                    <li><input class="search_input" v-model="data.keywords" type="text" placeholder="手机 笔记本电脑 衣服"></li>
                    <li><button class="search_button" type="button" @click="search" > <el-icon><Search /></el-icon> </button></li>
                    <li>
                        <div class="index_my_car">
                            <span >我的商城<i class="fa fa-home" /></span>
                            <span><router-link to="/carts">我的购物车<el-icon><i class="fa fa-shopping-cart" /></el-icon></router-link><div class="shop_car_dot">{{data.cart||0}}</div></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- nav -->
        <div class="shop_top_nav">
            <div class="center1200">
                <div class="shop_top_nav_left" @mouseover="classClick(true)" @mouseleave="classClick(false)">
                    全部商品
                    <transition name="el-zoom-in-top"><leftBar :changeColor="!data.changeColor" v-show="data.subnav?(data.subnav && data.subnavBool):(data.subnavBool && data.subnavBool2)"></leftBar></transition>
                </div>
                <div class="shop_top_nav_right">
                    <ul>
                        <li>
                            <router-link to="/">首页</router-link>
                        </li>
                        <li>
                            <router-link to="/stores">店铺街</router-link>
                        </li>
                        <li>
                            <router-link to="/seckills">秒杀</router-link>
                        </li>
                        <li>
                            <router-link to="/collectives/eyJrZXl3b3JkcyI6IiJ9">拼团</router-link>
                        </li>
                        <li>
                            <router-link to="/integral">积分商城</router-link>
                        </li>
                        <li>
                            <router-link to="/user/article/帮助中心">帮助中心</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {reactive,computed,onMounted,watch,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
import { Search } from '@element-plus/icons'
import {useRouter,useRoute} from 'vue-router'
import leftBar from "@/components/home/leftbar"
export default {
    components:{Search,leftBar},
    props:{
        topHide:{
            type:Boolean,
            default:false
        }
    },
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const store = useStore()
        const router = useRouter()
        const route = useRoute()
        const data = reactive({
            subnav:computed(()=>store.state.init.isHome),
            subnavBool:true,
            subnavBool2:false,
            cartNum:0,
            centerTop:true,
            keywords:'',
            changeColor:computed(()=>store.state.init.isHome),
            common : computed(()=>store.state.init.common.common),
            cart:computed(()=>store.state.init.common.cart),
        })

        if(props.topHide){
            data.centerTop = false
        }else{
            // 监听滚动条
            window.addEventListener('scroll', ()=>{
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                if(scrollTop>200){
                    data.centerTop = false
                }else{
                    data.centerTop = true
                }
                if(data.subnav && scrollTop>20){
                    data.subnavBool=false
                }else{
                    data.subnavBool=true
                }
                
            }, true);
        }

        const classClick = (e)=>{
            if(!data.subnav) data.subnavBool2=e
            if(!data.centerTop) data.subnavBool=e
        }

        const search = ()=>{
            // 搜索
            let params = {};
            params.keywords = encodeURIComponent(data.keywords);
            router.push('/s/'+window.btoa(JSON.stringify(params)))
        }

        onMounted(()=>{
            if(!proxy.R.isEmpty(route.params.params)  && route.path.indexOf('/s/') > -1){
                data.keywords = decodeURIComponent(JSON.parse(window.atob(route.params.params)).keywords)
                if(data.keywords == 'undefined') data.keywords = ''
            }
        })

        watch(()=>route.params.params,(e)=>{
            if(!proxy.R.isEmpty(e) && route.path.indexOf('/s/') > -1){
                data.keywords = decodeURIComponent(JSON.parse(window.atob(e)).keywords)
                if(data.keywords == 'undefined') data.keywords = ''
            }
        })
        

        return {
            data,
            classClick,
            search,
        }
    }
}
</script>

<style lang="scss" scoped>

.head{
    z-index: 666;
    position: fixed;
    background: #fff;
    left:0;
    right: 0;
    top:30px;
}
.center_top{
    transform: 0.5s;
}
.center_top:after{
    display: block;
    clear:both;
    content:'';
}

.shop_logo{
    margin-top: 40px;
    margin-bottom: 40px;
    img{
        width: 242px;
        height: 74px;
    }
}
.shop_top_nav{
    height: 40px;
    background:#333;
    font-size: 14px;
    line-height: 40px;
    color:#fff;
    .shop_top_nav_left{
        cursor: pointer;
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
    margin-top: 60px;
    margin-left: 180px;
    ul li{
        float: left;
    }
    .search_input{
        width: 434px;
        height: 38px;
        padding: 10px;
        outline: 0;
        border: 1px solid #f1f1f1;
        border-right: none;
        float: left;
        box-sizing: border-box;
        font-size: 12px;
    }
    .search_button{
        border: 1px solid #f1f1f1;
        float: left;
        width: 38px;
        height: 38px;
        background: #fff;
        outline: 0;
        text-align: center;
        cursor: pointer;
    }
    .search_button:hover{
        color:#fff;
        background:#333;
    }
    .index_my_car{
        height: 38px;
        border: 1px solid #f1f1f1;
        margin-left: 30px;
        box-sizing: border-box;
        font-size: 12px;
        line-height: 38px;
        padding: 0 20px;
        span:hover{
            color:#ca151e;
        }
        span{
            cursor: pointer;
            margin-right: 20px;
            position: relative;
            i{
                font-size: 14px;
                margin-left: 3px;
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