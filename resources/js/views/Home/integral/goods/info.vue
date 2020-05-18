<template>
    <div class="goods_info">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true" :cart_change="cart_change"></shop-top></div>

        <div class="goods_info_top_mbx  width_center_1200">
            
            <el-breadcrumb  separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">商城首页</el-breadcrumb-item>
                <el-breadcrumb-item v-for="(v,k) in goods_info.class_list" :key="k">{{v.name}}</el-breadcrumb-item>
                <el-breadcrumb-item>{{goods_info.goods_name}}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="goods_info_top width_center_1200">
            <div class="goods_info_top_left" >
                <div class="goods_image_item">
                    <pic-zoom :url="goods_images_thumb[chose_img_pos]" :highUrl="goods_images[chose_img_pos]"></pic-zoom>
                </div>
                <div class="pic_zoom_list">
                    <div class="pic_zoom_list_left" @click="pre_img()">
                        <i class="el-icon-arrow-left"></i>
                    </div>
                    <ul>
                        <li v-for="(v,k) in goods_info.goods_images_thumb_200" :key="k" @click="click_silde_img(k)" :class="chose_img_pos==k?'border_color':''"><img :src="v" alt=""></li>
                    </ul>
                    <div class="pic_zoom_list_right" @click="next_img()">
                        <i class="el-icon-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="goods_info_top_right">
                <div class="goods_info_title">{{goods_info.goods_name||'加载中...'}}
                </div>
                <div class="goods_info_group">
                    <div class="goods_info_price"><span>兑换积分：</span>{{goods_info.goods_price||'0.00'}} <font style="font-size:14px;">积分</font></div>
                    <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||0.00}}</div></div>
                    <div class="goods_info_sale_num">累计兑换<font color="#ca151e">{{goods_info.goods_sale_num}}</font></div>
                </div>
                <div class="goods_info_num">
                    <div class="goods_info_num_title">兑换数量：</div>
                    <div class="goods_info_num_jian" @click="change_buy_num(false)"><i class="el-icon-minus"></i></div>
                    <div class="goods_info_num_input"><input v-model="buy_num" type="text" value="1"></div>
                    <div class="goods_info_num_jia" @click="change_buy_num(true)"><i class="el-icon-plus"></i></div>
                    <div class="goods_info_num_stock">&nbsp;&nbsp;{{goods_info.goods_num}} 库存</div>
                    <div class="clear"></div>
                </div>
                <div class="goods_info_btn">
                    <div class="goods_info_buy" @click="buy()"><i class="icon iconfont">&#xe675;</i>立即兑换</div>
                </div>

            </div>
            <div class="clear"></div>
        </div>

        <div class="goods_info_text width_center_1200">
            <div class="goods_info_text_right">
                <el-tabs v-model="activeName" >
                    <el-tab-pane label="积分商品详情" name="first"><div class="after_sale_content" v-html="goods_info.goods_content"></div></el-tab-pane>
                </el-tabs>
            </div>
        </div>

        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
// import VuePhotoZoomPro from 'vue-photo-zoom-pro'
import PicZoom from '@/components/home/public/vue-piczoom.vue' // 放大镜组件
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        PicZoom, // 放大镜
        ShopTop,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          activeName:'first',
          goods_id:0,   // 商品ID
          goods_info:{
              goods_images_thumb_200:['/pc/loading_pic_200.png']
          },
          store_info:{},
          buy_num:1, // 购买数量
          chose_img_pos:0,
          goods_images_thumb:['/pc/loading_pic_400.png'],
          goods_images:[],
          cart_change:0,
          is_fav:false,
          save_history:true, // 是否需要存储
          isSkeleton:false, // false 骨架显示
          comment_image:[],
 
      };
    },
    watch: {
        // 监听选择购买数量
        buy_num:function(e){
            if(e>this.goods_info.goods_num || e<=0){
                if(this.goods_info.goods_num != 0){
                    this.$message.error('请认真填写购买数量');
                    this.buy_num = this.goods_info.goods_num;
                }
            }
        }
    },
    computed: {},
    methods: {

        // 点击缩略图幻灯片图片
        click_silde_img:function(e){
            this.chose_img_pos = e;
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
        // 购买数量修改
        change_buy_num:function(type){
            if(type){
                if(this.buy_num+1>this.goods_info.goods_num){
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

      
        // 立即购买
        buy:function(){
            let str = this.goods_info.id+'|'+this.buy_num;
            this.$router.push("/integral/order/create_order/"+str);
        },
 
        // 切换图片
        push_comment_images:function(e){
            this.comment_image = [];
            e.forEach(res=>{
                this.comment_image.push(res);
            });
        },
        // 查下评论状态
        change_comment_type:function(e){
            this.comment_type = e;
            this.get_comment_list();
        },
        // 获取商品信息
        get_goods_info:function(){
            this.$get(this.$api.homeGetIntegralGoodsInfo+this.goods_id).then(res=>{
                this.goods_info = res.data;
                this.goods_images_thumb = res.data.goods_images_thumb;
                this.goods_images = res.data.goods_images;
                this.buy_num = 1; // 初始化购买数量
            });
        },

    },
    created() {
        this.goods_id = this.$route.params.id;
        if(this.$isEmpty(this.goods_id)){
            return this.$message.error('请检查商品是否存在');
        }
        this.get_goods_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

.after_sale_content{
    font-size: 14px;
}

.goods_info_text{
    margin-top: 30px;
    .goods_info_text_left{
        width: 234px;
        float: left;
        margin-right: 20px;
    }
    .goods_info_text_right{
        margin-top: 20px;
        width: 1200px;
        box-sizing: border-box;
        min-height: 300px;
        padding:20px;
        float: left;
        border:1px solid #efefef;
    }
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
            line-height: 25px;
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
    .goods_info_add_cart,.goods_info_buy{
        line-height: 40px;
        float: left;
        margin-right: 20px;
        background: #ff5c14;
        border-radius: 3px;
        color:#fff;
        padding: 0 15px;
        i{
            margin-right: 6px;
        }
    }
    .goods_info_buy{
        background: #ca151e;
        i{
            font-size: 12px;
        }
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
    .goods_info_group{
        position: relative;
        box-sizing: border-box;
        padding: 20px;
        // padding-bottom: 90px;
        height: 180px;
        background: url("../../../../public/pc/summary-bg.jpg");
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
    .goods_info_phone_read{
        position:absolute;
        right: 24px;
        font-size: 12px;
        color:#999;
        top: 140px;
        i{
            margin-left: 10px;
            color:#666;
        }
    }
    

}
.goods_info_top_mbx{
    margin:30px auto;
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