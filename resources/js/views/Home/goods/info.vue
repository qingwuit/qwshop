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
                    <div :class="isFav?'goods_info_sc red_color':'goods_info_sc'" @click="goods_fav()">{{isFav?'已收藏':'收藏'}}<a-icon type="like" /></div>
                </div>
                <div class="goods_info_group">
                    <template v-if="seckills">
                        <div class="goods_info_price"><span>秒杀价：</span>￥{{goods_info.goods_price*(1-seckills.discount/100)||'0.00'}}</div>
                        <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||'0.00'}}</div></div>
                    </template>
                    <template v-else>
                        <div class="goods_info_price"><span>商城价：</span>￥{{goods_info.goods_price||'0.00'}}</div>
                        <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">￥{{goods_info.goods_market_price||'0.00'}}</div></div>
                    </template>
                
                    <!-- <div class="goods_info_active"><span>优惠：</span><font class="noy" v-if="goods_info.goods_freight<=0 && goods_info.freight_id<=0">包邮</font><font v-else-if="store_info.free_freight>0">满{{store_info.free_freight}}包邮</font><font class="noz" v-else>暂无优惠</font></div> -->
                    <div class="goods_info_sale_num">累计销量<font color="#ca151e">{{goods_info.goods_sale}}</font></div>
                    <div class="goods_info_phone_read">手机查看<a-icon style="font-size:16px" type="qrcode" /></div>

                    <!-- 优惠券 S -->
                    <div class="coupons_block" v-if="coupons.length>0">
                        <ul>
                            <li v-for="(v,k) in coupons" :key="k" @click="receiveCoupon(v.id)">
                                <div class="price">￥{{v.money}} </div>
                                <div class="block">
                                    <div>优 惠 券</div>
                                    <div>满￥{{v.use_money}}可用</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- 优惠券 E -->
                    
                </div>
                <!-- 参加活动 -->
                <div class="goods_info_active">
                    <div class="goods_skill" v-if="seckills">
                        <span><a-icon type="history" /></span>
                        <span>参加秒杀活动</span>
                        <span class="span_time">距离结束 {{seckills.format_time||'0 天 00 时 00 分 00 秒'}}</span>
                    </div>
                    <div class="goods_skill tuan_active" v-if="collectives"  @click="collective_id=-1;buy()">
                        <span><a-icon type="usergroup-delete" /></span>
                        <span>参加团购活动 <span style="font-weight:bold; margin-left:15px;" >( 点击开新团 )</span></span>
                        <span class="span_time">团购价：￥ {{$formatFloat(goods_info.goods_price*(1-collectives.discount/100)||'0.00')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 需要 {{collectives.need}} 人</span>
                    </div>
                    <div class="tuan_list" v-if="collectives && collective_list.length>0">
                        <a-carousel autoplay :autoplaySpeed="3000" speed="1000" :vertical="true" :adaptiveHeight="true" :dots="false">
                            <div class="tuan_item" v-for="(v,k) in collective_list" :key="k">
                                <img v-lazy="v.avatar||require('@/asset/user/user_default.png')">
                                <div class="nickname">{{v.nickname}}</div>
                                <div class="btn" @click="collective_id=v.id;buy()">参团</div>
                                <div class="orders_count">已经参团 {{v.orders_count}} / {{v.need}} 人</div>
                            </div>
                        </a-carousel>
                    </div>
                </div>
                
                <!-- 满减 S -->
                <div class="goods_info_full_reduction" v-if="full_reductions.length>0">
                    <span class="title">活动：</span>
                    <span class="act" v-for="(v,k) in full_reductions" :key="k"> 满￥{{v.use_money}}减￥{{v.money}}；</span>
                </div>
                <!-- 满减 E -->

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
                    <!-- <div v-show="goods_info.is_groupbuy==1" class="goods_info_add_groupbuy" @click="group_buy()"><i class="icon iconfont">&#xe601;</i>选择团购</div> -->
                    <template v-if="seckills">
                        <div class="goods_info_buy" @click="buy()"><a-font type="iconchanpin1" />立即抢购</div>
                    </template>
                    <template v-else>
                        <div class="goods_info_buy" @click="buy()"><a-font type="iconchanpin1" />立即购买</div>
                        <div class="goods_info_add_cart" @click="add_cart()"><a-font type="icongouwuche1" />加入购物车</div>
                    </template>
                    
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <div class="goods_info_content w1200">
            <div class="left_item">
                <div class="store_info">
                    <div class="store_title">
                        <span class="tip">店铺</span>
                        <span class="title">{{store_info['store_name']||'加载中...'}}</span>
                    </div>
                    <div class="rate">
                        <span style="float:left;padding-top:2px;margin-right:10px">综合评分</span>
                        <a-rate style="font-size:14px;float:left" :value="rate_info.scoreAll" :tooltips="desc" disabled />
                        <span style="float:left;padding-top:2px;" class="ant-rate-text">{{ desc[rate_info.scoreAll - 1] }}</span>
                        <div class="clear"></div>
                    </div>
                    <div class="store_rate">
                        <div class="title">店铺评分：</div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px">描述相符</span>
                            <a-rate style="font-size:14px;float:left" :value="rate_info.agreeAll" :tooltips="desc" disabled />
                            <span style="float:left;padding-top:2px;" class="ant-rate-text">{{ desc[rate_info.scoreAll - 1] }}</span>
                            <div class="clear"></div>
                        </div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px">服务态度</span>
                            <a-rate style="font-size:14px;float:left" :value="rate_info.serviceAll" :tooltips="desc" disabled />
                            <span style="float:left;padding-top:2px;" class="ant-rate-text">{{ desc[rate_info.scoreAll - 1] }}</span>
                            <div class="clear"></div>
                        </div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px">发货速度</span>
                            <a-rate style="font-size:14px;float:left" :value="rate_info.speedAll" :tooltips="desc" disabled />
                            <span style="float:left;padding-top:2px;" class="ant-rate-text">{{ desc[rate_info.scoreAll - 1] }}</span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="store_com" style="margin-top:10px">公司名称：<font color="#999">{{store_info.store_company_name}}</font></div>
                    <div class="store_com" style="margin-bottom:10px">公司地址：<font color="#999">{{store_info.area_info+' '+store_info.store_address}}</font></div>
                    <div class="btn">
                        <span class="navstore" @click="$router.push('/store/'+store_info.id)">进入店铺</span>
                        <span class="contact" @click="chat=true">联系客服</span>
                        <div class="clear"></div>
                    </div>
                </div>
                <!-- // 销售排行 -->
                <div class="store_info">
                    <div class="store_title"><span class="title">销售排行</span></div>
                    <div class="goods_list" v-if="goods_info.sale_list.length>0">
                        <dl v-for="(v,k) in goods_info.sale_list" :key="k"><a :href="'/goods/'+v.id">
                            <dt><img v-lazy="v.goods_master_image" :alt="v.goods_name"></dt>
                            <dd class="info">
                                <div class="title">{{v.goods_name||''}}</div>
                                <div class="price">￥{{v.goods_price}}</div>
                                <div class="round">{{k+1}}</div>
                            </dd></a>
                        </dl>
                    </div>
                    <div style="line-height:90px;text-align:center;color:#999;" v-else>暂时没有商品~~</div>
                </div>
            </div>
            <div class="right_item">
                 <a-tabs default-active-key="1" >
                <a-tab-pane key="1" tab="商品详情" force-render>
                    <div v-html="goods_info.goods_content||''"></div>
                </a-tab-pane>
                <a-tab-pane key="2" :tab="'用户评价 ('+(comment_statistics.all||0)+')'" force-render>
                     <!-- 评论 -->
                    <div class="comment_list_top">
                        <div class="left_bfb">
                            {{comment_statistics.rate||100}}<font style="font-size:20px">%</font>
                            <span>好评率</span>
                        </div>
                        <div class="right_comment_list">
                            <ul>
                                <li :class="params.is_type==0?'red':''" @click="comment_type_click(0)">全部 ({{comment_statistics.all||0}})</li>
                                <li :class="params.is_type==1?'red':''" @click="comment_type_click(1)">好评 ({{comment_statistics.good||0}})</li>
                                <li :class="params.is_type==2?'red':''" @click="comment_type_click(2)">中评 ({{comment_statistics.commonly||0}})</li>
                                <li :class="params.is_type==3?'red':''" @click="comment_type_click(3)">差评 ({{comment_statistics.bad||0}})</li>
                            </ul>
                        </div>
                    </div>
                    <div class="user_content_blcok_line"></div>
                    <div class="comment_list_block">
                        <a-list :data-source="comments">
                            <a-list-item slot="renderItem" slot-scope="item">
                                <a-comment :author="item.user.nickname" :avatar="item.user.avatar||require('@/asset/user/user_default.png')">
                                    <!-- <template slot="actions">
                                    </template> -->
                                    {{ item.content }}
                                    <a-tooltip slot="datetime" :title="item.created_at">
                                    <span>{{item.created_at}}</span>
                                    </a-tooltip>
                                    <a-comment v-if="item.reply != ''" :author="'售后客服'" :avatar="item.user.avatar||require('@/asset/user/kefu.png')">
                                        <font color="red">{{ item.reply }}</font>
                                        <a-tooltip slot="datetime" :title="item.reply_time">
                                        <span>{{item.reply_time}}</span>
                                        </a-tooltip>
                
                                    </a-comment>
                                </a-comment>
                            </a-list-item>
                        </a-list>
                    </div>
                    <div class="fy" v-if="comments.length>0" style="margin-top:20px">
                        <a-pagination v-model="params.page" :default-page-size="params.per_page" :total="params.total" @change="onChange" />
                    </div>
                    
                </a-tab-pane>
                <a-tab-pane key="3" tab="售后服务" force-render>
                    <div v-html="store_info.after_sale_service"></div>
                </a-tab-pane>
                </a-tabs>
            </div>
            <div class="clear"></div>
        </div>

        <!-- 聊天 -->
        <chat v-if="chat" :store_id="store_info.id" />

    </div>
</template>

<script>
import Chat from "@/components/chat/index"
import PicZoom from '@/components/home/goods/vue-piczoom.vue' // 放大镜组件 
export default {
    components: {PicZoom,Chat},
    props: {},
    data() {
      return {
          goods_info:{
              goods_images_thumb_400:[],
              goods_images_thumb_150:[],
              goods_images:[],
              sale_list:[],
          },
          params:{
              page:1,
              per_page:30,
              total:0,
              is_type:0,
          },
          comment_statistics:[],
          comments:[],
          store_info:{
              area_info:'',
              store_address:'',
          },
          chat:false,
          rate_info:{},
          buy_num:1, // 购买数量
          goods_id:0,
          chose_img_pos:0,
          chose_spec:[],
          sku_id:0,
          isFav:false,
          save_history:true,
          desc: ['1.0分', '2.0分', '3.0分', '4.0分', '5.0分'],
          coupons:[], // 优惠券
          full_reductions:[], // 满减
          seckills:false, // 秒杀
          collectives:false, // 拼团
          collective_list:[], // 正在进行的团
          collective_id:0,
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
                    this.store_info = res.data.store_info;
                    this.rate_info = this.store_info.rate;
                    this.coupons = res.data.coupons; // 优惠券
                    this.full_reductions = res.data.full_reductions; // 满减
                    this.seckills = res.data.seckills; // 秒杀
                    this.timing(this.seckills); // 倒计时
                    this.collectives = res.data.collectives; // 拼团
                    this.collective_list = res.data.collective_list; // 拼团

                    this.is_fav(res.data.id); // 获取收藏情况
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
        // 定时器
        timing(market){
            if(market){
                let obj = setInterval(()=>{
                    let timeVal = moment(market.end_time).format('X') - moment().format('X');
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
                    this.$set(this.seckills,'format_time',d + ' 天 ' + hh + ' 时 ' + mm + ' 分 ' + ss + ' 秒')
                    if(moment(market.end_time).valueOf()<moment().valueOf()){
                        clearInterval(obj);
                        this.$router.go(0);
                    }
                },1000)
            }
            
        },
        get_goods_comments(){
            this.$get(this.$api.homeGoods+'/comments/'+this.goods_id,this.params).then(res=>{
                if(res.code == 200){
                    this.params.total = res.data.total;
                    this.params.per_page = res.data.per_page;
                    this.params.current_page = res.data.current_page;
                    this.comments = res.data.data;
                }else{
                    this.$message.error(res.msg);
                }
            })
        },
        onChange(e){
            this.params.page = e;
            this.get_goods_comments();
        },
        comment_type_click(is_type){
            this.params.page = 1;
            this.params.is_type = is_type;
            this.get_goods_comments();
        },
        get_goods_goods_comment_statistics(){
            this.$get(this.$api.homeGoods+'/comment_statistics/'+this.goods_id).then(res=>{
                if(res.code == 200){
                    this.comment_statistics = res.data;
                }else{
                    this.$message.error(res.msg);
                }
            })
        },
        // 立即购买
        buy(){
            let params = {
                order:[
                    {
                        goods_id:this.goods_info.id, // 商品ID
                        sku_id:this.sku_id, // SKUid 没有则为0
                        buy_num:this.buy_num, // 购买数量
                        collective_id:this.collective_id, // 拼团ID 非必传
                    },
                ],
                ifcart:0, // 是否购物车
            };

            // 恢复 collective_id
            this.collective_id = 0

            let str = window.btoa(JSON.stringify(params)); 
            this.$router.push("/order/create_order/"+str);
        },
        // 加入购物车
        add_cart(){
            let params = {
                goods_id:this.goods_info.id, // 商品ID
                sku_id:this.sku_id, // SKUid 没有则为0
                buy_num:this.buy_num, // 购买数量
            };
            this.$post(this.$api.homeCarts,params).then(res=>{
                this.cart_count();
                this.$returnInfo(res);
            })
            // this.$get(this.$api.homeCarts).then(res=>{
            //     this.$returnInfo(res);
            // })
        },
        cart_count(){
            this.$get(this.$api.homeCarts+'/cart_count').then(res=> {
                if(res.code == 200){
                    this.$store.dispatch('homeCart/set_cart_num',res.data);
                }
            });
        },

        // 属性被选择点击
        attr_click:function(e,index){
            console.log(123)
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
        goods_fav(){
            if(this.isFav){
                return this.del_fav(this.goods_info.id);
            }else{
                return this.add_fav(this.goods_info.id);
            }
        },
        // 添加收藏
        add_fav(id){
            this.$post(this.$api.homeFav,{id:id,is_type:0}).then(res=>{
                return this.is_fav(id);
            })
        },
        // 删除收藏
        del_fav(id){
            this.$delete(this.$api.homeFav+'/'+id,{is_type:0}).then(res=>{
                return this.is_fav(id);
            })
        },
        // 判断是否收藏该产品
        is_fav(id){
            this.$get(this.$api.homeFav+'/'+id,{is_type:0}).then(res=>{
                if(res.code == 200){
                    return this.isFav = true;
                }else{
                    return this.isFav = false;
                }
            })
        },
        // 领取优惠券
        receiveCoupon(id){
            this.$post(this.$api.homeCoupon+'/receive',{id:id}).then(res=>{
                return this.$returnInfo(res)
            })
        }
        
    },
    created() {
        this.goods_id = this.$route.params.id;
        this.get_goods_info();
        this.get_goods_comments();
        this.get_goods_goods_comment_statistics();
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
        width: 946px;
        float: left;
        .user_content_blcok_line{
            clear: both;
            height: 1px;
            background: #efefef;
            margin: 15px 0;
        }
        .comment_list_top{
            margin-bottom: 15px;
            .left_bfb{
                float: left;
                font-size: 48px;
                color:#ca151e;
                line-height: 95px;
                width: 225px;
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
    }
    .left_item{
        width: 234px;
        float: left;
        margin-right: 20px;
        .store_info{
            width: 100%;
            border:1px solid #efefef;
            margin-bottom: 20px;
            .goods_list{
                dl{
                    border-bottom: 1px solid #efefef;
                    padding: 20px 10px;
                    &:last-child{
                        border-bottom: none;
                    }
                    &:after{
                        clear: both;
                        display: block;
                        content:'';
                    }
                    .info{
                        display: flex;
                        flex-direction:column;
                        float: left;
                        position: relative;
                        .title{
                            width: 120px;
                            height: 45px;
                            overflow: hidden;
                        }
                        .price{
                            color:#ca151e;
                            margin-top: 5px;
                        }
                        .round{
                            background: #333;
                            color:#fff;
                            width: 16px;
                            height: 16px;
                            text-align: center;
                            line-height: 16px;
                            border-radius: 50%;
                            position: absolute;
                            font-size: 12px;
                            top:-15px;
                            left:-95px;

                        }
                    }
                    &:nth-child(1) .round,&:nth-child(2) .round,&:nth-child(3) .round{
                        background: #ca151e;
                    }
                }
                
                dt{
                    width: 80px;
                    height: 80px;
                    margin-right: 10px;
                    float: left;
                    img{
                       width: 80px;
                        height: 80px; 
                    }
                }
                
            }
            .btn{
                border-top: 1px solid #efefef;
                span:hover{
                    background: #ca151e;
                    color:#fff;
                }
                span{
                    text-align: center;
                    width: 50%;
                    box-sizing: border-box;
                    height: 40px;
                    line-height: 40px;
                    display: block;
                    float: left;
                    cursor: pointer;
                    &:first-child{
                        border-right: 1px solid #efefef;
                    }
                }
            }
            .store_com{
                padding:3px 10px;
            }
            .store_rate{
                padding-left:10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #efefef;
                .item{
                    line-height: 30px;
                }
                .title{
                    color:#000;
                    line-height: 35px;
                }
            }
            .rate{
                line-height: 35px;
                font-size: 14px;
                padding-left:10px;
                border-bottom: 1px solid #efefef;
            }
            .store_title{
                background: #fafafa;
                height: 35px;
                padding:0 10px;
                padding-top: 6px;
                box-sizing: border-box;
                border-bottom: 1px solid #efefef;
                .tip{
                    background: #ca151e;
                    color:#fff;
                    text-align: center;
                    line-height: 24px;
                    border-radius: 3px;
                    margin-right: 10px;
                    padding:2px 10px;
                }
                .title{
                    color:#000;
                }
            }
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
        cursor: pointer;
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
        cursor: pointer;
        background: #ca151e;
        i{
            font-size: 16px;
            font-weight: bold;
        }
    }

    .goods_info_add_groupbuy{
        cursor: pointer;
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
        background: url("../../../asset/pc/summary-bg.jpg");
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
                    line-height: 42px;
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
            cursor: pointer;
            background: #f0f9eb;
            border: 1px solid #e1f3d8;
            span{
                color:#67c23a;
            }
            &:hover{
                background: #67c23a;
                span{
                    color:#fff;
                }
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
</style>