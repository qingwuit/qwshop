<template>
    <div class="home">
        <div class="shop_head3"><shop-head :subnav_show="subnav_show" /></div>
        <Banner :list="list.banner" />
        <banner-bottom-adv :index_adv="list.banner_bottom_adv" />

        <!-- 分类商品列表 -->
        <div class="index_bg">
            <div class="goods_class_list w1200" v-for="(v,k) in list.goods" :key="k">
                <div v-if="v.goods.length>0">
                    <!-- <div class="adv_width_1200"><img v-lazy="v.image_url" :alt="v.name"></div>   -->
                    <div class="title">{{v.name||'加载中...'}}<span><router-link to="#">查看更多</router-link></span></div>
                    <div class="index_adv_goods_left"><img v-lazy="v.image_url||''" :alt="v.name"></div>
                    <div class="index_class_goods_right">
                        <ul>
                            <li><router-link :to="'/goods/'+vo.id" v-for="(vo,key) in v.goods" :key="key">
                                <div class="product_act_in">
                                    <dl>
                                        <dt><img v-lazy="vo.goods_master_image||''" :alt="vo.goods_name" /></dt>
                                        <dd class="product_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                        <dd class="product_subtitle">{{vo.goods_subname}}</dd>
                                        <dd class="product_price">￥{{vo.goods_price}}<span>{{vo.goods_market_price}}元</span></dd>
                                    </dl>
                                </div>
                            </router-link></li>
                        </ul>
                    </div>
                </div>
`                
            </div>`
        </div>
        

        <!-- 底部 -->
        <shop-foot />
    </div>
</template>

<script>
import ShopHead from '@/components/home/public/head'
import Banner from '@/components/home/public/banner'
import BannerBottomAdv from "@/components/home/public/banner_bottom_adv"
import ShopFoot from "@/components/home/public/shop_foot"
// import VueLazyload from 'vue-lazyload' // 图片懒加载
// Vue.use(VueLazyload)
export default {
    components: {ShopHead,Banner,BannerBottomAdv,ShopFoot},
    props: {},
    data() {
      return {
          list:[],
          subnav_show:true,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_index(){
            this.$get(this.$api.homeIndex).then(res=>{
                this.list = res.data;
            })
        }
    },
    created() {
        this.get_index();
    },
    mounted() {
        // 监听滚动条
        window.addEventListener('scroll', ()=>{
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            if(scrollTop>20){
                this.subnav_show = false;
            }else{
                this.subnav_show = true;
            }
        }, true);
    }
};
</script>
<style lang="scss" scoped>
.index_bg{
    background: #f5f5f5;
    padding: 20px 0 60px 0;
    margin-top: 40px;
}
.index_class_goods_right{
    width: 966px;
    float: left;
    ul li{
        width: 220px;
        height: 300px;
        margin-bottom: 14px;
        margin-left: 20px;
        box-sizing: border-box;
        float: left;
        .product_act_in{
            width: 220px;
            height: 300px;
            background: #fff;
            box-sizing: border-box;
            transition: all 0.2s linear;
        }
        dl{
            padding-top: 20px;
        }
        dl dt{
            width: 140px;
            height: 140px;
            margin:0 auto;
        }
        dl dt img{
            width: 140px;
            height: 140px;
        }
        dl dd{
            width: 190px;
            overflow: hidden;
            text-align: center;
            margin:0 auto;
        }
        dl dd.product_title{
            font-size: 14px;
            margin-top: 25px;
            height: 30px;
            line-height: 30px;
        }
        dl dd.product_subtitle{
            margin-top: 0px;
            font-size: 12px;
            color:#b0b0b0;
            line-height: 14px;
        }
        dl dd.product_price{
            margin-top: 10px;
            font-size: 16px;
            color:#ca151e;
            line-height: 34px;
            span{
                font-size: 14px;
                color:#b0b0b0;
                margin-left: 8px;
                text-decoration: line-through;
            }
        }
    }
    ul li:hover .product_act_in{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        margin-top:-3px;
    }
}

.index_adv_goods_left{
    width: 234px;
    height: 614px;
    float: left;
    transition: all 0.2s linear;
    background: #fff;
}
.index_adv_goods_left:hover{
     box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.goods_class_list:after{
    clear: both;
    display: block;
    content:'';
}
.goods_class_list{
    margin-bottom: 30px;
    .title{
        font-size: 22px;
        margin-bottom: 20px;
        span{
            font-size: 14px;
            float: right;
        }
    }
}
</style>