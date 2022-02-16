<template>
    <div class="default_main">
        <banner-view :banner="[{image:require('@/assets/Home/default_banner.jpg').default}]" />
        <banner-adv :index_adv="[{image:require('@/assets/Home/banner_b1.jpg').default},{image:require('@/assets/Home/banner_b2.jpg').default},{image:require('@/assets/Home/banner_b3.jpg').default}]" />

        <div class="index_bg">
            <div class="goods_class_list w1200" v-for="(v,k) in data.goods" :key="k">
                <adv-view v-if="v.goods && v.goods.length>0" :adv="{image:require('@/assets/Home/adv.jpg').default}" />
                <div v-if="v.goods && v.goods.length>0">
                    <div class="title">{{v.name||'加载中...'}}<span><router-link to="/s/eyJrZXl3b3JkcyI6IiJ9">查看更多</router-link></span></div>
                    <div class="index_adv_goods_left"><el-image lazy fit="cover" :src="(data.classLeftAdv[k] && data.classLeftAdv[k].image_url)?data.classLeftAdv[k].image_url:require('@/assets/Home/pc_class_btadv.jpg').default" :alt="v.name" /></div>
                    <div class="index_class_goods_right">
                        <ul>
                            <li v-for="(vo,key) in v.goods" :key="key" @click="toGoods(vo.id)">
                                <div class="product_act_in">
                                    <dl>
                                        <dt><el-image class="el_image" lazy fit="cover" :src="vo.goods_master_image||''" :alt="vo.goods_name" /></dt>
                                        <dd class="product_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                        <dd class="product_subtitle">{{vo.goods_subname}}</dd>
                                        <dd class="product_price">{{$t('btn.money')}}{{vo.goods_price}}<span>{{vo.goods_market_price}}元</span></dd>
                                    </dl>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import bannerView from "@/components/home/banner"
import bannerAdv from "@/components/home/banner_adv"
import advView from "@/components/home/adv"
import {_push} from "@/plugins/config"
import {reactive,onMounted,getCurrentInstance} from "vue"
export default {
    components:{bannerView,bannerAdv,advView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const data = reactive({
            goods:[],
            classLeftAdv:[],
        })
        onMounted( async ()=>{
            const resp = await proxy.R.get('/home')
            data.goods = resp.goods
        })
        const toGoods = (e)=>{
            _push('/goods/'+e)
        }
        return {data,toGoods}
    }
}
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
            .el_image{
                width: 140px;
                height: 140px;
            }
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