<template>
    <div class="goods_info_temp">
        <div class="mbx w1200">
            <a-breadcrumb>
                <a-breadcrumb-item><a href="/">首页</a></a-breadcrumb-item>
                <a-breadcrumb-item v-for="(v,k) in goods_info.goods_class" :key="k"><a href="#">{{v.name}}</a></a-breadcrumb-item>
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
                    <div :class="is_fav?'goods_info_sc red_color':'goods_info_sc'" @click="goods_fav()">{{is_fav?'已收藏':'收藏'}}<a-icon type="like" /></div>
                </div>
                <div class="goods_info_group">
                    <div class="goods_info_price"><span>商城价：</span>￥{{goods_info.goods_price||'0.00'}}</div>
                    <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||'0.00'}}</div></div>
                    <!-- <div class="goods_info_active"><span>优惠：</span><font class="noy" v-if="goods_info.goods_freight<=0 && goods_info.freight_id<=0">包邮</font><font v-else-if="store_info.free_freight>0">满{{store_info.free_freight}}包邮</font><font class="noz" v-else>暂无优惠</font></div> -->
                    <div class="goods_info_sale_num">累计销量<font color="#ca151e">{{goods_info.goods_sale}}</font></div>
                    <div class="goods_info_phone_read">手机查看<a-icon style="font-size:16px" type="qrcode" /></div>
                </div>
                <!-- 参加活动 -->
                <!-- <div class="goods_info_active">
                    <div class="goods_skill" v-show="goods_info.is_seckill == 1">
                        <span><i class="icon iconfont">&#xe60c;</i></span>
                        <span>参加秒杀活动</span>
                        <span class="span_time">距离结束 {{seckill_hour}} 时 {{seckill_min}} 分 {{seckill_secs}} 秒</span>
                    </div>
                    <div class="goods_skill tuan_active" v-show="goods_info.is_groupbuy==1">
                        <span><i class="icon iconfont">&#xe8cc;</i></span>
                        <span>参加团购活动</span>
                        <span class="span_time">团购价：￥ {{goods_info.groupbuy_price}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 还差 {{goods_info.groupbuy_use}} / {{goods_info.groupbuy_num}} 人</span>
                    </div>
                </div> -->
                <div class="goods_info_spec" v-show="!this.$isEmpty(goods_info.skuList)">
                    <div class="spec_list" v-for="(v,k) in goods_info.attrList" :key="k">
                        <span>{{v.name}}：</span>
                        <ul>
                            <li :class="($isEmpty(val.is_chose) || val.is_chose==false)?'':'red'" v-for="(val,key) in v.specs" :key="key" @click="attr_click(k,key)" >{{val.name}}</li>
                        </ul>
                    </div>
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
                    <div v-show="goods_info.is_groupbuy==1" class="goods_info_add_groupbuy" @click="group_buy()"><i class="icon iconfont">&#xe601;</i>选择团购</div>
                    <div class="goods_info_buy" @click="buy()"><a-font type="iconchanpin1" />立即购买</div>
                    <div class="goods_info_add_cart" @click="add_cart()"><a-font type="icongouwuche1" />加入购物车</div>
                </div>

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
          chose_spec:[],
          sku_id:0,
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
            this.$get(this.$api.homeGoods+'/'+this.goods_id).then(res=>{
                if(res.code == 200){
                    this.goods_info = res.data;
                    // 存储游览记录
                    if(this.save_history){
                        this.save_history_fun(this.goods_info);
                    }
                }else{
                    this.$message.error(res.msg);
                    this.$router.go(-1);
                }
            })
        },
        // 立即购买
        buy:function(){
            let params = {
                goods_id:this.goods_info.id, // 商品ID
                sku_id:this.sku_id, // SKUid 没有则为0
                buy_num:this.buy_num, // 购买数量
                ifcart:0, // 是否购物车
            };
            let str = window.btoa(JSON.stringify(params)); 
            this.$router.push("/order/create_order/"+str);
        },
        // 加入购物车
        add_cart:function(){
            let params = {
                goods_id:this.goods_info.id, // 商品ID
                sku_id:this.sku_id, // SKUid 没有则为0
                buy_num:this.buy_num, // 购买数量
            };
            this.$post(this.$api.homeCarts,params).then(res=>{
                this.$returnInfo(res);
            })
            // this.$get(this.$api.homeCarts).then(res=>{
            //     this.$returnInfo(res);
            // })
        },

        // 属性被选择点击
        attr_click:function(e,index){
            this.goods_info.attrList[e]['specs'].forEach((res,key)=>{
                res.is_chose = false;
                if(key == index){
                    res.is_chose = true;
                }
            });
            this.buy_num = 1;

            // 循环获取选择的规格属性
            let chose_spec = [];
            this.goods_info.attrList.forEach(res=>{
                res['specs'].forEach(listRes=>{
                    if(!this.$isEmpty(listRes.is_chose) && listRes.is_chose == true){
                        chose_spec.push(listRes.id);
                    }
                });
            });
            
            this.chose_spec = chose_spec;
            
            // 如果选择数量和规格数量一致则表示选择完所有的规格属性
            if(this.chose_spec.length == this.goods_info.attrList.length){
                this.get_spec_attr_data();
            }

            this.$forceUpdate();
        },
        // 根据选择的规格属性去获取数据库存在的规格数据
        get_spec_attr_data:function(){
            this.goods_info.skuList.forEach(res=>{
                let a = 0;
                this.chose_spec.forEach(specRes=>{
                    res.spec_id.forEach(itm=>{
                        if(itm == specRes){
                             a += 1;
                        }
                    })
                });
                if(a == this.goods_info.attrList.length){
                    this.chose_spec_info = res;
                    this.sku_id = res.id;
                    return this.change_goods_info(res);
                }
            });
        },
        change_goods_info:function(res){
            this.goods_info.goods_price = res.goods_price;
            this.goods_info.goods_market_price = res.goods_market_price;
            this.goods_info.goods_stock = res.goods_stock;
        },
        // 存储数据记录
        save_history_fun:function(goods_info){
            this.save_history=false;
            let goods_json = localStorage.getItem('shop_goods_historys');
            let goods_list = [];

            if(!this.$isEmpty(goods_json)){
                goods_list = JSON.parse(goods_json);
            }
            
            let have_his = false;
            if(goods_list.length>0){
                goods_list.forEach(res=>{
                    if(goods_info.id == res.id){
                        res.goods_name = goods_info.goods_name;
                        res.image = goods_info.goods_master_image;
                        res.id = goods_info.id;
                        res.goods_price = goods_info.goods_price;
                        have_his = true;
                    }
                })
            }
            // console.log(goods_list)
            
            if(!have_his){
                goods_list.push({id:goods_info.id,goods_name:goods_info.goods_name,goods_price:goods_info.goods_price,image:goods_info.goods_master_image});
            }

            if(goods_list.length>4){
                goods_list.splice(0,1);
            }

            let json_str = JSON.stringify(goods_list);
            localStorage.setItem('shop_goods_historys',json_str);

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
            font-size: 16px;
            font-weight: bold;
        }
    }
    .goods_info_buy{
        background: #ca151e;
        i{
            font-size: 16px;
            font-weight: bold;
        }
    }

    .goods_info_add_groupbuy{
        line-height: 40px;
        float: left;
        margin-right: 20px;
        background: #67c23a;
        border-radius: 3px;
        color:#fff;
        padding: 0 15px;
        i{
            margin-right: 6px;
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
        background: url("../../../asset/pc/summary-bg.jpg");
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
        .goods_skill{
            margin-bottom: 10px;
            background: #fef0f0;
            border:1px solid  #fde2e2;
            font-size: 14px;
            span{
                color:#f56c6c;
                line-height: 40px;
                i{
                    font-size: 18px;
                    line-height: 40px;
                    margin-right: 20px;
                    margin-left: 20px;
                    float: left;
                }
            }
            span.span_time{
                float: right;
                margin-right: 30px;
            }
        }
        .tuan_active{
            background: #f0f9eb;
            border: 1px solid #e1f3d8;
            span{
                color:#67c23a;
            }
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
    .spec_list{
        margin-bottom: 10px;
        span{float: left;width: 84px;}
        ul{
            float: left;
            li{
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
</style>