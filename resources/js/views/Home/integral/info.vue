<template>
    <div class="goods_info_temp">
        <div class="mbx w1200">
            <el-breadcrumb>
                <el-breadcrumb-item><a href="/">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item>{{data.goods_info.goods_name}}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="goods_info_top w1200">
            <div class="goods_info_top_left" >
                <div class="goods_image_item">
                    <pic-zoom :url="data.goods_info.goods_images_thumb_400 && data.goods_info.goods_images_thumb_400[data.chose_img_pos]" :highUrl="data.goods_info.goods_images_thumb_400 && data.goods_info.goods_images[data.chose_img_pos]"></pic-zoom>
                </div>
                <div class="pic_zoom_list">
                    <div class="pic_zoom_list_left" @click="pre_img()">
                        <el-icon><ArrowLeftBold /></el-icon>
                    </div>
                    <ul>
                        <li v-for="(v,k) in data.goods_info.goods_images_thumb_150" :key="k" @click="click_silde_img(k)" :class="data.chose_img_pos==k?'border_color':''"><img :src="v" alt=""></li>
                    </ul>
                    <div class="pic_zoom_list_right" @click="next_img()">
                        <el-icon><ArrowRightBold /></el-icon>
                    </div>
                </div>
            </div>
            <div class="goods_info_top_right" >
                <div class="goods_info_title">{{data.goods_info.goods_name||'加载中...'}}
                    <p>{{data.goods_info.goods_subname||'加载中...'}}</p>
                </div>
                <div class="goods_info_group">
                    <div class="goods_info_price"><span>积分：</span>{{$t('btn.money')}}{{data.goods_info.goods_price||'0.00'}}</div>
                    <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">{{$t('btn.money')}}{{data.goods_info.goods_market_price||'0.00'}}</div></div>
            
                    <!-- <div class="goods_info_active"><span>优惠：</span><font class="noy" v-if="goods_info.goods_freight<=0 && goods_info.freight_id<=0">包邮</font><font v-else-if="store_info.free_freight>0">满{{store_info.free_freight}}包邮</font><font class="noz" v-else>暂无优惠</font></div> -->
                    <div class="goods_info_sale_num">累计销量<em color="#ca151e">{{data.goods_info.goods_sale||0}}</em></div>
                    <div class="goods_info_phone_read">手机查看<i style="font-size:16px" class="fa fa-qrcode"></i></div>
                </div>
               
                
                <div class="goods_info_num">
                    <div class="goods_info_num_title">购买数量：</div>
                    <div class="goods_info_num_jian" @click="change_buy_num(false)">-</div>
                    <div class="goods_info_num_input"><input v-model="data.buy_num" type="number"  /></div>
                    <div class="goods_info_num_jia" @click="change_buy_num(true)">+</div>
                    <div class="goods_info_num_stock">&nbsp;&nbsp;{{data.goods_info.goods_stock}} 库存</div>
                    <div class="clear"></div>
                </div>
                <div class="goods_info_btn">
                    <div class="goods_info_buy" @click="buy()"><img :src="require('@/assets/Home/buy.png').default" />  立即兑换</div>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <div class="goods_info_content w1200">
            <div class="right_item">
                 <el-tabs model-value="1" @tab-click="tabClick">
                    <el-tab-pane name="1" label="商品详情" >
                        <div v-html="editorHtml(data.goods_info.goods_content)||''"></div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="clear"></div>
        </div>

        <!-- 聊天 -->
        <!-- <chat v-if="chat" :store_id="store_info.id" /> -->

    </div>
</template>

<script>
// import Chat from "@/components/chat/index"
import {reactive,watch,onMounted,getCurrentInstance} from "vue"
import {useRoute,useRouter} from 'vue-router'
import {ArrowLeftBold,ArrowRightBold,Star,Timer,UserFilled,SemiSelect,Plus} from '@element-plus/icons'
import PicZoom from '@/components/home/vue-piczoom.vue' // 放大镜组件 
import {editorHandle} from '@/plugins/config'
export default {
    components: {PicZoom,ArrowLeftBold,ArrowRightBold,Star,Timer,UserFilled,SemiSelect,Plus}, // Chat
    setup(props) {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const router = useRouter()
        const data = reactive({
            goods_info:[],
            params:{
              page:1,
              per_page:30,
              total:0,
              is_type:0,
            },
            buy_num:1, // 购买数量
            goods_id:0,
            chose_img_pos:0,
        })

        const loadData = async ()=>{
            const resp = await proxy.R.get('/integral/goods/'+data.goods_id)
            if(!resp.code){
                data.goods_info = resp
             
            }else{
                proxy.$message.error(res.msg)
                router.go(-1);
            }
               
        }

        // 图片翻页
        const pre_img = ()=>{
            if(data.chose_img_pos<=0){
                data.chose_img_pos = data.goods_info.goods_images.length-1;
            }else{
                data.chose_img_pos -= 1;
            }
        }
        const next_img = ()=>{
            if(data.chose_img_pos>=data.goods_info.goods_images.length-1){
                data.chose_img_pos = 0;
            }else{
                data.chose_img_pos += 1;
            }
        }
        const click_silde_img = (e)=>{
            data.chose_img_pos = e;
        }
 
      
        // 修改购买数量
        const change_buy_num = (type)=>{
            if(type){
                if(data.buy_num+1>data.goods_info.goods_stock){
                    return proxy.$message.error(proxy.$t('home.goods.stockErr'));
                }
                data.buy_num += 1;
            }else{
                if(data.buy_num<=1){
                    return proxy.$message.error(proxy.$t('home.goods.minimumErr'));
                }
                data.buy_num -= 1
            }
        }
        // 购买
        const buy = ()=>{
            router.push("/integral/order/"+data.goods_info.id+"/"+data.buy_num)

        }
       
        // editor
        const editorHtml = (e)=>{
            return editorHandle(e)
        }

        onMounted( () => {
            data.goods_id = route.params.id
            loadData()
        })

        watch(()=>data.buy_num,(e)=>{
            if(e>data.goods_info.goods_stock || e<=0){
                if(data.goods_info.goods_stock != 0){
                    proxy.$message.error(proxy.$t('home.goods.buyNumErr'));
                    data.buy_num = data.goods_info.goods_stock;
                }
            }
        })

        watch(()=>route.params.id,(e)=>{
            if(!e) return
            data.goods_id = e
            loadData()
        })

        return {
            data,
            pre_img,next_img,click_silde_img,editorHtml,
            change_buy_num,buy,
        }
    },
  
}
</script>
<style lang="scss" scoped>
.goods_info_content{
    .right_item{
        border:1px solid #efefef;
        padding:20px;
        box-sizing: border-box;
        min-height: 600px;
        width: 100%;
        float: left;
        .user_content_blcok_line{
            clear: both;
            height: 1px;
            background: #efefef;
            margin: 15px 0;
        }
    }

    margin-top: 60px;
}
.goods_info_top_right{
    float: left;
    width: 770px;
    font-size: 14px;
    
    .goods_info_num{
        padding: 10px;
        margin-top: 10px;
        .goods_info_num_title{
            color:#666;
            float: left;
            line-height: 25px;
            margin-right: 15px;
        }
        .goods_info_num_jian,.goods_info_num_jia{
            cursor: pointer;
            border:1px solid #efefef;
            width: 25px;
            height: 25px;
            line-height: 24px;
            text-align: center;
            float: left;
            margin-right: 10px;
            color:#666;
            &:hover{
                background: #333;
                color:#fff;
                border:1px solid #333;
            }
        }
        .goods_info_num_stock{
            line-height: 25px;
            color:#999;
        }
        .goods_info_num_input{
            float: left;
        }
        .goods_info_num_input input{
            height: 27px;
            border:1px solid #efefef;
            outline: none;
            width: 50px;
            margin-right: 10px;
            box-sizing: border-box;
            padding: 0 8px;
            color:#666;
        }
    }
    .goods_info_btn{
        clear: both;
        margin-top: 20px;
    }
    .goods_info_add_cart,.goods_info_buy{
        cursor: pointer;
        line-height: 40px;
        float: left;
        margin-right: 20px;
        background: #ff5c14;
        border-radius: 3px;
        color:#fff;
        padding: 0 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        img{
            width: 20px;
            margin-right: 5px;
        }
    }
    .goods_info_buy{
        cursor: pointer;
        background: #ca151e;
        i{
            font-size: 16px;
        }
    }
    
    .goods_info_title{
        position: relative;
        font-size: 18px;
        margin-bottom: 15px;
        padding-right: 100px;
        p{
            color:#999;
            line-height: 35px;
            font-size: 14px;
        }
    }
    .goods_info_group{
        position: relative;
        box-sizing: border-box;
        padding: 20px;
        // padding-bottom: 90px;
        height: 170px;
        background: url("../../../assets/Home/summary-bg.jpg");
    }
    .goods_info_price{
        color:#ca151e;
        font-size: 28px;
        line-height: 28px;
        font-weight: bold;
        span{
            line-height: 28px;
            color:#999;
            font-size: 14px;
            font-weight: normal;
        }
    }
    .goods_info_market_price{
        margin-top: 10px;
        span{
            color:#999;
        }
        .overx_goods_info{
            text-decoration: line-through;
            display: inline-block;
        }
    }
    .goods_info_sale_num{
        position:absolute;
        font-size: 12px;
        right: 16px;
        color:#333;
        top: 30px;
        em{
            margin-left: 10px;
            margin-right: 10px;
        }
    }
    .goods_info_phone_read{
        position:absolute;
        right: 24px;
        font-size: 12px;
        color:#999;
        top: 120px;
        i{
            margin-left: 10px;
            color:#666;
        }
    }
    
    .goods_info_active{
        margin-top: 20px;
        span{
            color:#999;
        }
        em{
            background: #ff6590;
            color:#fff;
            line-height: 34px;
            padding: 4px 8px;
            margin-right: 10px;
            border-radius: 3px;
        }
        em.noy{
            background: #67c23a;
        }
        em.noz{
            background: #999;
        }
       
    }
}
.goods_info_top_left{
    width: 402px;
    border: 1px solid #efefef;
    margin-right: 28px;
    float: left;
    box-sizing: border-box;
    .goods_image_item{
        width: 400px;
        height: 400px;
        display: block;
        border-bottom: 1px solid #efefef;
    }
    .pic_zoom_list{
        ul li{
            float: left;
            margin:10px 10px 10px 0; 
            border:1px solid #efefef;
            img{
                width: 60px;
                height: 60px;
            }
        }
        ul li:hover{
            border-color:#ca151e;
        }
        ul li:last-child{
            margin-right: 0;
        }
        ul li.border_color{
            border-color:#ca151e;
        }
        .pic_zoom_list_left{
            font-size: 24px;
            float: left;
            line-height: 80px;
            color:#666;
            margin-right: 10px;
            margin-left: 10px;
            :hover{
                color:#ca151e;
            }
        }
        .pic_zoom_list_right{
            font-size: 24px;
            float: right;
            line-height: 80px;
            color:#666;
            margin-right: 10px;
            margin-left: 10px;
            :hover{
                color:#ca151e;
            }
        }
    }
}
.goods_info_sc{
    background: #f6f6f6;
    border-radius: 6px;
    font-size: 14px;
    line-height: 25px;
    color:#666;
    position: absolute;
    top:0;
    right: 8px;
    padding:0 10px;
    cursor: pointer;
    i{
        margin-left: 6px;
        font-size: 12px;
    }
}
.goods_info_sc.red_color{
    color:#fff;
    background: #ca151e;
}
.goods_info_sc:hover{
    color:#fff;
    background: #ca151e;
}
.goods_info_spec{
    margin-top: 20px;
    padding-left:10px; 
    line-height: 20px;
    color:#666;
    position: relative;
    z-index: 200;
    .spec_list{
        margin-bottom: 10px;
        span{float: left;width: 84px;}
        ul{
            float: left;
            li{
                cursor: pointer;
                float: left;
                color:#666;
                border:1px solid #e1e1e1;
                border-radius: 2px;
                font-size: 12px;
                padding: 0 8px;
                margin-right: 10px;
            }
            li.red{
                border:1px solid #ca151e;
                color:#ca151e;
            }
            li:hover{
                border:1px solid #ca151e;
                color:#ca151e;
            }
        }
    }
    :after{
        content:'';
        display: block;
        clear:both;
    }
}
.coupons_block{
    margin-top: 20px;
    width: 550px;
    ul li{
        border:1px dashed #999;
        display: inline-block;
        padding: 5px;
        margin-right: 10px;
        cursor: pointer;
        .price{
            font-size: 30px;
            font-weight: bold;
            float: left;
            margin-right: 10px;
            line-height: 30px;
        }
        .block{
            float: left;
            font-size: 12px;
            color:#b5621b;
            line-height: 14px;
        }
        &:after{
            clear: both;
            display: block;
            content:'';
        }
        &:nth-child(n+4){
            display: none;
        }
    }
}
.goods_info_full_reduction{
    background: #e6e6e6;
    line-height: 25px;
    font-size: 12px;
    .title{
        width: 84px;
        color:#b5621b;
        padding-left: 10px;
        // margin-right: 45px;
    }
    .act{
        color:#b5621b;
    }
}
.tuan_list{
    margin-bottom: 10px;
    height: 32px;
    
    .tuan_item{
        &:after{
            content:'';
            display: block;
            clear: both;
        }
        img{
            width: 30px;
            height: 30px;
            border:1px solid #efefef;
            border-radius: 50%;
            float: left;
            margin-right: 15px;
        }
        .nickname{
            color:#b5621b;
            float: left;
            display: block;
            width: 180px;
            line-height: 32px;
        }
        .orders_count{
            color:#b5621b;
            float: right;
            display: block;
            line-height: 32px;
            margin-right: 15px;
        }
        .btn{
            cursor: pointer;
            display: block;
            background: #67c23a;
            color:#fff;
            border-radius: 3px;
            float: right;
            line-height: 32px;
            padding:0 10px;
        }
    }
}
.goods_info_temp{
    .mbx{margin-top:30px;margin-bottom: 30px;}
}
.rate_class{
    font-size: 14px;
    // float: left;
    // margin-top: 15px;
    &.other{
        // margin-top: 6px;
    }
}
.comment_list_block{
    ul li{
        padding-top: 20px;
        border-bottom: 1px solid #efefef;
        padding-bottom: 30px;
        
    }
    ul li:after{
        clear: both;
        display: block;
        content:'';
    }
    .comment_avatar{
        width: 40px;
        height: 40px;
        float: left;
        border-radius: 50%;
        background: #efefef;
        margin-right: 15px;
    }
    .comment_nickname{
        font-size: 14px;
        color:#666;
        font-weight: bold;
    }
    .comment_star{
        height: 30px;
        font-size: 14px;
        color:#666;
        line-height: 30px;
        em{
            float: left;
            margin-right: 10px;
        }
        .store_star_in{
            float: left;
            margin-top: 5px;
        }
    }
    .comment_content{
        margin-top: 10px;
        border-top: 1px dashed #efefef;;
        padding-top: 10px;
        margin-left: 55px;
        font-size: 14px;
        color:#666;
    }
    .comment_images{
        margin-left: 55px;
        margin-top: 20px;
        .comment_image{
            height: 90px;
            width: 90px;
            border:1px solid #efefef;
            margin-right: 20px;
            float: left;
            .el_image{
                width: 100%;
                height: 100%;
            }
        }
    }
}
</style>