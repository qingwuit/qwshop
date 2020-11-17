<template>
    <div class="goods_info_temp">
        <div class="mbx w1200">
            <a-breadcrumb>
                <a-breadcrumb-item><a href="/">首页</a></a-breadcrumb-item>
                <a-breadcrumb-item><a href="/integral/index">积分商城</a></a-breadcrumb-item>
                <a-breadcrumb-item>{{goods_info.goods_name}}</a-breadcrumb-item>
            </a-breadcrumb>
        </div>

        <div class="goods_info_top w1200">
            <div class="goods_info_top_left" >
                <div class="goods_image_item">
                    <pic-zoom :url="goods_info.goods_images_thumb_400[chose_img_pos]" :highUrl="goods_info.goods_images[chose_img_pos]"></pic-zoom>
                </div>
                <div class="pic_zoom_list">
                    <div class="pic_zoom_list_left" @click="pre_img()">
                        <a-icon type="left" />
                    </div>
                    <ul>
                        <li v-for="(v,k) in goods_info.goods_images_thumb_150" :key="k" @click="click_silde_img(k)" :class="chose_img_pos==k?'border_color':''"><img :src="v" alt=""></li>
                    </ul>
                    <div class="pic_zoom_list_right" @click="next_img()">
                        <a-icon type="right" />
                    </div>
                </div>
            </div>
            <div class="goods_info_top_right" >
                <div class="goods_info_title">{{goods_info.goods_name||'加载中...'}}
                    <p>{{goods_info.goods_subname||'加载中...'}}</p>
                </div>
                <div class="goods_info_group">
                    <div class="goods_info_price"><span>兑换积分：</span>{{goods_info.goods_price||'0.00'}} <font style="font-size:12px">积分</font></div>
                    <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||'0.00'}}</div></div>
                    <div class="goods_info_sale_num">累计兑换<font color="#ca151e">{{goods_info.goods_sale}}</font></div>
                </div>
               
                
                <div class="goods_info_num">
                    <div class="goods_info_num_title">购买数量：</div>
                    <div class="goods_info_num_jian" @click="change_buy_num(false)"><a-icon type="minus" /></div>
                    <div class="goods_info_num_input"><input v-model="buy_num" type="text" value="1"></div>
                    <div class="goods_info_num_jia" @click="change_buy_num(true)"><a-icon type="plus" /></div>
                    <div class="goods_info_num_stock">&nbsp;&nbsp;{{goods_info.goods_stock}} 库存</div>
                    <div class="clear"></div>
                </div>
                <div class="goods_info_btn">
                    <div class="goods_info_buy" @click="buy()"><a-font type="iconchanpin1" />立即兑换</div>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <div class="goods_info_content w1200">
            
            <div class="right_item">
                 <a-tabs default-active-key="1" >
                    <a-tab-pane key="1" tab="商品详情" force-render>
                        <div v-html="goods_info.goods_content||''"></div>
                    </a-tab-pane>
                </a-tabs>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
import PicZoom from '@/components/home/goods/vue-piczoom.vue' // 放大镜组件 
export default {
    components: {PicZoom},
    props: {},
    data() {
      return {
          goods_info:{
              goods_images_thumb_400:[],
              goods_images_thumb_150:[],
              goods_images:[],
          },
          buy_num:1, // 购买数量
          goods_id:0,
          chose_img_pos:0,
      };
    },
    watch: {
        // 监听选择购买数量
        buy_num:function(e){
            if(e>this.goods_info.goods_stock || e<=0){
                if(this.goods_info.goods_stock != 0){
                    this.$message.error('请认真填写购买数量');
                    this.buy_num = this.goods_info.goods_stock;
                }
            }
        },
    },
    computed: {},
    methods: {
        get_goods_info(){
            this.$get(this.$api.homeIntegral+'/goods/'+this.goods_id).then(res=>{
                if(res.code == 200){
                    this.goods_info = res.data;
                    this.store_info = res.data.store_info;
                    this.rate_info = this.store_info.rate;
                    this.is_fav(res.data.id); // 获取收藏情况
                    // 存储游览记录
                    if(this.save_history){
                        this.save_history_fun(this.goods_info);
                    }
                }else{
                    this.$message.error(res.msg);
                    // this.$router.go(-1);
                }
            })
        },
   
        // 立即购买
        buy:function(){
            this.$router.push("/integral/order/"+this.goods_info.id+"/"+this.buy_num);
        },
        pre_img:function(){
            if(this.chose_img_pos<=0){
                this.chose_img_pos = this.goods_info.goods_images.length-1;
            }else{
                this.chose_img_pos -= 1;
            }
        },
        next_img:function(){
            if(this.chose_img_pos>=this.goods_info.goods_images.length-1){
                this.chose_img_pos = 0;
            }else{
                this.chose_img_pos += 1;
            }
        },
        // 点击缩略图幻灯片图片
        click_silde_img:function(e){
            this.chose_img_pos = e;
        },
        // 购买数量修改
        change_buy_num:function(type){
            if(type){
                if(this.buy_num+1>this.goods_info.goods_stock){
                    return this.$message.error('库存不足');
                }
                this.buy_num += 1;
            }else{
                if(this.buy_num<=1){
                    return this.$message.error('最低购买数量为 1');
                }
                this.buy_num -= 1
            }
        },
        
        
    },
    created() {
        this.goods_id = this.$route.params.id;
        this.get_goods_info();
    },
    mounted() {},
    beforeRouteUpdate (to, from, next) {
        console.log(to,from);
        if(from.params.id != to.params.id){
            this.goods_id = to.params.id;
            this.get_goods_info();
        }
        next();
        // react to route changes...
        // don't forget to call next()
    }
};
</script>
<style lang="scss" scoped>
.goods_info_content{
    .right_item{
        border:1px solid #efefef;
        padding:20px;
        box-sizing: border-box;
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
            border:1px solid #efefef;
            width: 25px;
            height: 25px;
            // line-height: 25px;
            text-align: center;
            float: left;
            margin-right: 10px;
            color:#666;
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
    .goods_info_buy{
        background: #ca151e;
        i{
            font-size: 16px;
            font-weight: bold;
        }
    }

    .goods_info_group{
        position: relative;
        box-sizing: border-box;
        padding: 20px;
        // padding-bottom: 90px;
        height: 180px;
        background: url("../../../asset/pc/summary-bg.jpg");
    }
    
    .goods_info_title{
        position: relative;
        font-size: 18px;
        margin-bottom: 15px;
        p{
            color:#999;
            line-height: 35px;
            font-size: 14px;
        }
    }

    .goods_info_price{
        color:#ca151e;
        font-size: 28px;
        line-height: 28px;
        span{
            line-height: 28px;
            color:#999;
            font-size: 14px;
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
        font{
            margin-left: 10px;
            margin-right: 10px;
        }
    }
    .goods_info_buy{
        line-height: 40px;
        float: left;
        margin-right: 20px;
        background: #ca151e;
        border-radius: 3px;
        color:#fff;
        padding: 0 15px;
        i{
            margin-right: 6px;
            font-size: 16px;
            font-weight: bold;
        }
    }
    .goods_info_active{
        margin-top: 20px;
        span{
            color:#999;
        }
        font{
            background: #ff6590;
            color:#fff;
            line-height: 34px;
            padding: 4px 8px;
            margin-right: 10px;
            border-radius: 3px;
        }
        font.noy{
            background: #67c23a;
        }
        font.noz{
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

</style>