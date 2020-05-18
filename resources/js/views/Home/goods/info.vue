<template>
    <div class="goods_info">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true" :cart_change="cart_change"></shop-top></div>

        <div class="goods_info_top_mbx  width_center_1200">
            
            <el-breadcrumb separator="/">
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
            <div class="goods_info_top_right" >
                <div class="goods_info_title">{{goods_info.goods_name||'加载中...'}}
                    <p>{{goods_info.goods_subname||'加载中...'}}</p>
                    <div :class="is_fav?'goods_info_sc red_color':'goods_info_sc'" @click="goods_fav()">{{is_fav?'已收藏':'收藏'}}<i class="icon iconfont">&#xe6cf;</i></div>
                </div>
                <div class="goods_info_group">
                    <div class="goods_info_price"><span>商城价：</span>￥{{goods_info.goods_price||'0.00'}}</div>
                    <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||'0.00'}}</div></div>
                    <div class="goods_info_active"><span>优惠：</span><font class="noy" v-if="goods_info.goods_freight<=0 && goods_info.freight_id<=0">包邮</font><font v-else-if="store_info.free_freight>0">满{{store_info.free_freight}}包邮</font><font class="noz" v-else>暂无优惠</font></div>
                    <div class="goods_info_sale_num">累计销量<font color="#ca151e">{{goods_info.goods_sale_num}}</font></div>
                    <div class="goods_info_phone_read">手机查看<i class="icon iconfont">&#xeb9e;</i></div>
                </div>
                <!-- 参加活动 -->
                <div class="goods_info_active">
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
                </div>
                <div class="goods_info_spec" v-show="!this.$isEmpty(goods_info.chose_attr)">
                    <div class="spec_list" v-for="(v,k) in goods_info.spec_list" :key="k">
                        <span>{{v.name}}：</span>
                        <ul>
                            <li :class="($isEmpty(val.is_chose) || val.is_chose==false)?'':'red'" v-for="(val,key) in v.list" :key="key" @click="attr_click(k,key)" >{{val.name}}</li>
                        </ul>
                    </div>
                </div>
                <div class="goods_info_num">
                    <div class="goods_info_num_title">购买数量：</div>
                    <div class="goods_info_num_jian" @click="change_buy_num(false)"><i class="el-icon-minus"></i></div>
                    <div class="goods_info_num_input"><input v-model="buy_num" type="text" value="1"></div>
                    <div class="goods_info_num_jia" @click="change_buy_num(true)"><i class="el-icon-plus"></i></div>
                    <div class="goods_info_num_stock">&nbsp;&nbsp;{{goods_info.goods_num}} 库存</div>
                    <div class="clear"></div>
                </div>
                <div class="goods_info_btn">
                    <div v-show="goods_info.is_groupbuy==1" class="goods_info_add_groupbuy" @click="group_buy()"><i class="icon iconfont">&#xe601;</i>选择团购</div>
                    <div class="goods_info_buy" @click="buy()"><i class="icon iconfont">&#xe675;</i>立即购买</div>
                    <div class="goods_info_add_cart" @click="add_cart()"><i class="icon iconfont">&#xe602;</i>加入购物车</div>
                </div>

            </div>
            <div class="clear"></div>
        </div>

        <div class="goods_info_text width_center_1200">
            <div class="goods_info_text_left">
                <left-store-info-goods :store_info="goods_info.store_info"></left-store-info-goods>
                <left-sale-list :user_id="goods_info.user_id"></left-sale-list>
            </div>
            <div class="goods_info_text_right">
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="商品详情" name="first"><div class="after_sale_content" v-html="goods_info.goods_content"></div></el-tab-pane>
                    <el-tab-pane :label="'用户评价 ('+goods_info.comment_num+')'" name="second">
                        <!-- 评论 -->
                        <div class="comment_list_top">
                            <div class="left_bfb">
                                {{bfb}}<font style="font-size:20px">%</font>
                                <span>好评率</span>
                            </div>
                            <div class="right_comment_list">
                                <ul>
                                    <li @click="change_comment_type(0)" :class="comment_type==0?'red':''">全部 ({{this.comment_num.all}})</li>
                                    <li @click="change_comment_type(1)" :class="comment_type==1?'red':''">好评 ({{this.comment_num.good}})</li>
                                    <li @click="change_comment_type(2)" :class="comment_type==2?'red':''">中评 ({{this.comment_num.secondary}})</li>
                                    <li @click="change_comment_type(3)" :class="comment_type==3?'red':''">差评 ({{this.comment_num.bad}})</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="user_content_blcok_line"></div>

                        <!-- 评论列表 -->
                        <div class="goods_info_comment_list" v-if="total_data>0">
                            <ul>
                                <li v-for="(v,k) in comment_list" :key="k">
                                    <div class="comment_avatar"><el-image :src="v.avatar" lazy></el-image></div>
                                    <div class="comment_nickname">{{v.nickname}}</div>
                                    <div class="comment_star"><font>评价得分：</font><div class="store_star_in"><el-rate disabled :value="parseFloat(v.score)"></el-rate></div><div>{{v.score}}分</div></div>
                                    <div class="comment_content">评价内容：<font color="#999">{{v.content}}</font></div>
                                    <div class="comment_images" v-if="v.comment_images.length>0">
                                        <div class="comment_image" v-for="(vo,key) in v.comment_images" :key="key"><el-image @click="push_comment_images(v.comment_images)" :preview-src-list="comment_image"  :src="vo" lazy></el-image></div>
                                    </div>
                                </li>
                            </ul>
                            <div class="home_fy_block" style="margin-top:40px;" >
                                <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                            </div>
                        </div>
                        <div class="empty_order" v-else>
                            <dl>
                                <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                                <dd>主人，该商品没有任何评价哟~</dd>
                                <dd class="btn"><router-link to="/user/order">前往评论</router-link></dd>
                            </dl>
                        </div>

                    </el-tab-pane>
                    <el-tab-pane label="售后服务" name="third"><div class="after_sale_content" v-html="store_info['after_sale_content']"></div></el-tab-pane>
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
import leftSaleList from "@/components/home/public/left_sale_list" // 左侧销售排行
import leftStoreInfoGoods from "@/components/home/store/left_store_info_goods"
export default {
    components: {
        PicZoom, // 放大镜
        ShopTop,
        ShopFoot,
        leftSaleList,// 左侧销售排行
        leftStoreInfoGoods,
    },
    props: {},
    data() {
      return {
          activeName:'first',
          goods_id:0,   // 商品ID
          goods_info:{
              comment_num:0,
              goods_images_thumb_200:['/pc/loading_pic_200.png']
          },
          store_info:{},
          buy_num:1, // 购买数量
          chose_spec:[], // 选择的规格属性
          chose_spec_info:{},
          chose_img_pos:0,
          goods_images_thumb:['/pc/loading_pic_400.png'],
          goods_images:[],
          comment_list:[], // 评论
          cart_change:0,
          is_fav:false,
          save_history:true, // 是否需要存储
        //   isSkeleton:false, // false 骨架显示
          comment_image:[],

          // 评论的东西
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          comment_type:0,
          bfb:100,
          star:5.0,
          comment_num:{
              good:0,
              secondary:0,
              bad:0,
              all:0,
          },

          // 秒杀活动显示
          seckill_intvalobj:null,
          seckill_hour:0,
          seckill_min:0,
          seckill_secs:0,
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
        handleClick:function(e){
            if(e.name == 'second'){
                this.current_page = 1;
                this.get_comment_list();
            }
        },
        current_change:function(e){
            this.current_page = e;
            this.get_comment_list();
        },
        get_comment_list:function(){
            this.$post(this.$api.homeGetCommentListByGoods,{goods_id:this.goods_id,comment_type:this.comment_type,page:this.current_page}).then(res=>{
                this.page_size = res.data.comment_list.per_page;
                this.total_data = res.data.comment_list.total;
                this.current_page = res.data.comment_list.current_page;
                this.comment_list = res.data.comment_list.data;
                this.bfb = res.data.bfb;
                this.star = res.data.star;
                this.comment_num = {good:res.data.good,secondary:res.data.secondary,bad:res.data.bad,all:res.data.all};
            });
        },
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
        // 收藏状态
        is_fav_fun:function(){
            if(this.$isEmpty(localStorage.getItem('token'))){
                return;
            }
            this.$post(this.$api.homeIsFav,{mix_id:this.goods_id,is_type:0}).then(res=>{
                if(res.code== 200){
                    this.is_fav = true;
                }else{
                    this.is_fav = false;
                }
            });
        },
        goods_fav:function(){
            this.$post(this.$api.homeEditFav,{mix_id:this.goods_id,is_type:0}).then(()=>{
                this.is_fav_fun();
            });
        },
        // 属性被选择点击
        attr_click:function(e,index){
            this.goods_info.spec_list[e]['list'].forEach((res,key)=>{
                res.is_chose = false;
                if(key == index){
                    res.is_chose = true;
                }
            });
            this.buy_num = 1;

            // 循环获取选择的规格属性
            let chose_spec = [];
            this.goods_info.spec_list.forEach(res=>{
                res['list'].forEach(listRes=>{
                    if(!this.$isEmpty(listRes.is_chose) && listRes.is_chose == true){
                        chose_spec.push(listRes.name);
                    }
                });
            });
            
            this.chose_spec = chose_spec;
            
            // 如果选择数量和规格数量一致则表示选择完所有的规格属性
            if(this.chose_spec.length == this.goods_info.spec_list.length){
                this.get_spec_attr_data();
            }

            this.$forceUpdate();
        },
        // 根据选择的规格属性去获取数据库存在的规格数据
        get_spec_attr_data:function(){
            this.goods_info.spec.forEach(res=>{
                let a = 0;
                this.chose_spec.forEach(specRes=>{
                    let resInfo = res.spec_name.split(' ');
                    if(resInfo.indexOf(specRes)>=0){
                        a += 1;
                    }
                });
                if(a == this.goods_info.spec_list.length){
                    this.chose_spec_info = res;
                    return this.change_goods_info();
                }
            });
        },
        change_goods_info:function(){
            this.goods_info.goods_price = this.chose_spec_info.goods_price;
            this.goods_info.goods_market_price = this.chose_spec_info.goods_market_price;
            this.goods_info.goods_num = this.chose_spec_info.goods_num;
        },
        // 如果是多规格产品则将商品价格取第一个规格产品
        is_spec_goods:function(){
            if(!this.$isEmpty(this.goods_info.chose_attr)){
                this.goods_info.goods_price = this.goods_info.spec[0].goods_price;
                this.goods_info.goods_market_price = this.goods_info.spec[0].goods_market_price;
                this.goods_info.goods_num = this.goods_info.spec[0].goods_num;
            }
        },
        // 立即购买
        buy:function(){
            if(!this.$isEmpty(this.goods_info.chose_attr) && this.chose_spec.length != this.goods_info.spec_list.length){
                return this.$message.error('请认真选择属性');
            }
            let str = this.goods_info.id+'|'+this.goods_info.store_info.id+'|'+this.buy_num;
            if(!this.$isEmpty(this.chose_spec_info['id']) && this.chose_spec_info['id']>0){
                str += '|'+this.chose_spec_info['id'];
            }
            this.$router.push("/order/create_order/0/"+str);
        },
        // 拼团购买
        group_buy:function(){
            if(!this.$isEmpty(this.goods_info.chose_attr) && this.chose_spec.length != this.goods_info.spec_list.length){
                return this.$message.error('请认真选择属性');
            }
            let str = this.goods_info.id+'|'+this.goods_info.store_info.id+'|'+this.buy_num;
            if(!this.$isEmpty(this.chose_spec_info['id']) && this.chose_spec_info['id']>0){
                str += '|'+this.chose_spec_info['id'];
            }
            this.$router.push("/order/create_order/2/"+str);
        },
        // 加入购物车
        add_cart:function(){
            if(!this.$isEmpty(this.goods_info.chose_attr) && this.chose_spec.length != this.goods_info.spec_list.length){
                return this.$message.error('请认真选择属性');
            }
            
            if(this.goods_info.spec.length>0){
                this.goods_info['spec_id'] = this.chose_spec_info['id'];
                this.goods_info['goods_spec_name'] = this.chose_spec_info.spec_name;
            }
            this.goods_info['buy_num'] = this.buy_num;
            this.$post(this.$api.homeAddCart,this.goods_info).then(res=>{
                if(res.code == 200){
                    this.get_goods_info();
                    this.cart_change += 1; // 修改购物车数据
                    return this.$message.success('加入购物车成功');
                }else{
                    return this.$message.error(res.msg);
                }
            });

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
        // 活动开始定时任务  秒杀
        seckill_active:function(){
            this.seckill_intvalobj = setInterval(()=>{
            let end_time = this.goods_info.seckill_info.end_time;
            let timestamp = Date.parse(new Date())/1000;
            if(end_time>timestamp){
                let sec2 = end_time-timestamp;
                this.seckill_hour = Math.floor(sec2/3600);
                this.seckill_min = Math.floor((sec2-(this.seckill_hour*3600))/60);
                this.seckill_secs = sec2-(this.seckill_hour*60+this.seckill_min)*60;
            }else{
                this.goods_info.is_seckill = 0;
                clearInterval(this.seckill_intvalobj);
            }
        },1000);
        },
        // 获取商品信息
        get_goods_info:function(){
            this.$post(this.$api.homeGetGoodsInfo,{goods_id:this.goods_id}).then(res=>{
                this.goods_info = res.data;
                this.store_info = res.data.store_info;
                this.goods_images_thumb = res.data.goods_images_thumb;
                this.goods_images = res.data.goods_images;
                this.buy_num = 1; // 初始化购买数量
                this.chose_spec = [];
                this.chose_spec_info = {};
                this.is_spec_goods();

                // this.isSkeleton = true; // 骨架显示

                // 存储游览记录
                if(this.save_history){
                    this.save_history_fun(this.goods_info);
                }

                // 数据获取成功则开始展示 秒杀活动信息
                if(this.goods_info.is_seckill == 1){
                    this.seckill_active();
                }
                

            });
        },

    },
    created() {
        this.goods_id = this.$route.params.goods_id;
        if(this.$isEmpty(this.goods_id)){
            return this.$message.error('请检查商品是否存在');
        }
        this.get_goods_info();
        this.is_fav_fun();
    },
    mounted() {},
    beforeRouteUpdate:function(to, from, next) {
        // console.log(to,from)
        this.goods_id = to.params.goods_id;
        if(to.path != from.path){
            this.get_goods_info();
            this.is_fav_fun();
        }
    // don't forget to call next()
    next();// 主要就是这一步
    }
};
</script>
<style lang="scss" scoped>

.goods_info_comment_list{
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
        font{
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
        }
    }
}
.comment_list_top{
    margin-bottom: 15px;
    .left_bfb{
        float: left;
        font-size: 48px;
        color:#ca151e;
        line-height: 95px;
        width: 190px;
        text-align: center;
        position: relative;
        border-right: 1px solid #efefef;
        padding-right: 35px;
        span{
            font-size: 14px;
            color:#999;
            position: absolute;
            top:-16px;
            left: 140px;
        }
    }
    .right_comment_list{
        ul li{
            float: left;
            line-height: 48px;
            margin-left: 35px;
            margin-top: 25px;
            color:#666;
            height: 48px;
            padding:0 40px;
            font-size: 14px;
            background: #efefef;
            border-radius: 3px;
        }
        ul li.red{
            background: #efefef;
            color:#ca151e;
        }
        ul li.red:hover{
            background: #e1e1e1;
            color:#ca151e;
        }
        ul li:hover{
            background: #e1e1e1;
            color:#666;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
        }
    }
}
.comment_list_top:after{
    clear:both;
    display: block;
    content:'';
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
.after_sale_content{
    font-size: 14px;
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
.goods_info_text{
    margin-top: 30px;
    .goods_info_text_left{
        width: 234px;
        float: left;
        margin-right: 20px;
    }
    .goods_info_text_right{
        margin-top: 20px;
        width: 946px;
        box-sizing: border-box;
        min-height: 900px;
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
        background: url("../../../public/pc/summary-bg.jpg");
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