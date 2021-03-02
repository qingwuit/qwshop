<template>
    <div class="home">
        <div class="shop_head3"><shop-head :subnav_show="subnav_show" /></div>
        <banner :list="list.banner" />
        <banner-bottom-adv :index_adv="list.banner_bottom_adv" />

        

        <!-- 分类商品列表 -->
        <div class="index_bg">
            <!-- 秒杀 -->
            <div class="seckill width_center_1200" v-if="seckill_list.length>0">
                <div class="seckill_title">商城秒杀<span><router-link to="/seckill">查看更多</router-link></span> </div>
                <div class="seckill_left">
                    <div class="seckill_end_time">
                        {{seckills}} 场
                        <p class="seckill_icon"><a-font type="iconziyuan"></a-font></p>
                        <p class="font_skill">距离结束还有</p>
                        <ul>
                            <li>{{timeFormat[0]||'00'}}</li>
                            <li>{{timeFormat[1]||'00'}}</li>
                            <li>{{timeFormat[2]||'00'}}</li>
                        </ul>
                    </div>
                </div>
                <div class="seckill_right">
                        <ul>
                            <li v-for="(v,k) in seckill_list" :key="k" @click="toGoods(v.id)">
                                <dl>
                                    <dt><img style="width: 180px; height: 180px" v-lazy="v.goods_master_image" :alt="v.goods_name" /></dt>
                                    <dd class="product_title" :title="v.goods_name">{{v.goods_name}}</dd>
                                    <dd class="product_subtitle">{{v.goods_subname}}</dd>
                                    <dd class="product_price">￥{{v.goods_price}}<span>{{v.goods_market_price}}</span></dd>
                                </dl>
                            </li>
                            
                        </ul>
                </div>
            </div>

            <div class="goods_class_list w1200" v-for="(v,k) in list.goods" :key="k">
                <shop-index-adv v-if="v.goods.length>0" :adv="(list.class_top_adv[k] && list.class_top_adv[k].image_url)?list.class_left_adv[k]:{adv_image:require('@/asset/pc/adv.jpg'),adv_link:'',adv_title:'加载中...'}" />
                <div v-if="v.goods.length>0">
                    <!-- <div class="adv_width_1200"><img v-lazy="v.image_url" :alt="v.name"></div>   -->
                    <div class="title">{{v.name||'加载中...'}}<span><router-link to="/s/eyJrZXl3b3JkcyI6IiJ9">查看更多</router-link></span></div>
                    <div class="index_adv_goods_left"><img v-lazy="(list.class_left_adv[k] && list.class_left_adv[k].image_url)?list.class_left_adv[k].image_url:require('@/asset/pc/pc_class_btadv.jpg')" :alt="v.name"></div>
                    <div class="index_class_goods_right">
                        <ul>
                            <li v-for="(vo,key) in v.goods" :key="key" @click="toGoods(vo.id)">
                                <div class="product_act_in">
                                    <dl>
                                        <dt><img v-lazy="vo.goods_master_image||''" :alt="vo.goods_name" /></dt>
                                        <dd class="product_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                        <dd class="product_subtitle">{{vo.goods_subname}}</dd>
                                        <dd class="product_price">￥{{vo.goods_price}}<span>{{vo.goods_market_price}}元</span></dd>
                                    </dl>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        

        <!-- 底部 -->
        <shop-foot />
    </div>
</template>

<script>
import Chat from "@/components/chat/index"
import ShopHead from '@/components/home/public/head'
import Banner from '@/components/home/public/banner'
import BannerBottomAdv from "@/components/home/public/banner_bottom_adv"
import ShopFoot from "@/components/home/public/shop_foot"
import ShopIndexAdv from "@/components/home/public/shop_index_adv"
// import VueLazyload from 'vue-lazyload' // 图片懒加载
// Vue.use(VueLazyload)
export default {
    components: {ShopHead,Banner,BannerBottomAdv,ShopFoot,ShopIndexAdv,Chat},
    props: {},
    data() {
      return {
          list:[],
          seckills:'00:00',
          seckill_list:[],
          timeFormat:[],
          subnav_show:true,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_index(){
            this.$get(this.$api.homeIndex).then(res=>{
                this.seckills  = moment().format('H')+':00';
                this.list = res.data;
                if(this.list.seckill_list.length>0){
                    this.timing();
                    this.seckill_list = this.list.seckill_list;
                }
            })
        },
         // 定时器
        timing(){
            let endTime = moment().add(1,'hours').format('YYYY-MM-DD HH')+':00:00';
            let obj = setInterval(()=>{
                let timeVal = moment(endTime).format('X') - moment().format('X');
                // 时间戳转换
                var d = Math.floor(timeVal / (24 * 3600));
                var h = Math.floor((timeVal - 24 * 3600 * d) / 3600);
                var m = Math.floor((timeVal - 24 * 3600 * d - h * 3600) / 60);
                var s = Math.floor((timeVal - 24 * 3600 * d - h * 3600 - m * 60));
                // console.log(d + '天' + hh + '时' + mm + '分' + ss + '秒'); // 打印出转换后的时间
                //  当时分秒小于10的时候补0
                var hh = h < 10 ? '0' + h : h;
                var mm = m < 10 ? '0' + m : m;
                var ss = s < 10 ? '0' + s : s;
                // this.seckills.format_time =  d + '天' + hh + '时' + mm + '分' + ss + '秒';
                this.timeFormat = [hh,mm,ss];
                if(moment(endTime).valueOf()<moment().valueOf()){
                    clearInterval(obj);
                    this.$router.go(0);
                }
            },1000)
            
        },
        toGoods(id){
            return this.$router.push('/goods/'+id);
        },
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
        cursor: pointer;
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
.seckill{
    .seckill_left{
        width: 234px;
        height: 340px;
        box-sizing: border-box;
        background: #f1eded;
        border-top: 1px solid #ca151e;
        float: left;
        // box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .seckill_end_time{
            padding-top:45px;
            text-align: center;
            color:#ca151e;
            font-size: 24px;
            p{
                padding-top: 60px;
            }
            p.font_skill{
                font-size: 12px;
                color:#666;
                padding-top: 30px;
                padding-bottom: 30px;
            }
            i{
                font-size: 40px;
            }
            ul{
                margin-left: 45px;
            }
            ul li{
                cursor: pointer;
                float: left;
                width: 40px;
                height: 40px;
                background: #333;
                color:#fff;
                margin-right: 10px;
                text-align: center;
                line-height: 40px;
            }
        }
        
    }
    .seckill_left:after{
        display: block;
        content:'';
        clear:both;
    }
    .seckill_title{
        font-size: 22px;
        margin-bottom: 20px;
        span{
            float:right;
            font-size: 14px;
            padding-right: 15px;
        }
        a:hover{
            color:#ca151e;
        }
    }
    .seckill_right{
        float: left;
        ul li{
            width: 220px;
            height: 340px;
            background: #fff;
            margin-left: 20px;
            float: left;
            box-sizing: border-box;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
            dl dt{
                width: 180px;
                height: 180px;
                margin:0 auto;
                margin-top: 20px;
            }
            dl dt img{
                width: 180px;
                height: 180px;
            }
            dl dd{
                width: 220px;
                overflow: hidden;
                text-align: center;
                width: 190px;
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
        ul li:hover{
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
            margin-top:-3px;
        }
        ul li:nth-child(1){
            border-top:1px solid #00c0a5;
        }
        ul li:nth-child(2){
            border-top:1px solid #ffac13;
        }
        ul li:nth-child(3){
            border-top:1px solid #83c44e;
        }
        ul li:nth-child(4){
            border-top:1px solid #2196f3;
        }
    }
}
</style>