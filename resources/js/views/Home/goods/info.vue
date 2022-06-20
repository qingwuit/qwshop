<template>
    <div class="goods_info_temp">
        <div class="mbx w1200">
            <el-breadcrumb>
                <el-breadcrumb-item><a href="/">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item v-for="(v,k) in data.goods_info.classList" :key="k"><a href="#">{{v.name}}</a></el-breadcrumb-item>
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
                    <div :class="data.isFav?'goods_info_sc red_color':'goods_info_sc'" @click="goods_fav()">{{data.isFav?'已收藏':'收藏'}}<el-icon><Star /></el-icon></div>
                </div>
                <div class="goods_info_group">
                    <template v-if="data.seckills">
                        <div class="goods_info_price"><span>秒杀价：</span>{{$t('btn.money')}}{{data.goods_info.goods_price*(1-data.seckills.discount/100)||'0.00'}}</div>
                        <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">{{$t('btn.money')}}{{data.goods_info.goods_market_price||'0.00'}}</div></div>
                    </template>
                    <template v-else>
                        <div class="goods_info_price"><span>商城价：</span>{{$t('btn.money')}}{{data.goods_info.goods_price||'0.00'}}</div>
                        <div class="goods_info_market_price"><span>市场价：</span><div class="overx_goods_info">{{$t('btn.money')}}{{data.goods_info.goods_market_price||'0.00'}}</div></div>
                    </template>
                
                    <!-- <div class="goods_info_active"><span>优惠：</span><font class="noy" v-if="goods_info.goods_freight<=0 && goods_info.freight_id<=0">包邮</font><font v-else-if="store_info.free_freight>0">满{{store_info.free_freight}}包邮</font><font class="noz" v-else>暂无优惠</font></div> -->
                    <div class="goods_info_sale_num">累计销量<em color="#ca151e">{{data.goods_info.goods_sale||0}}</em></div>
                    <div class="goods_info_phone_read">手机查看<i style="font-size:16px" class="fa fa-qrcode"></i></div>

                    <!-- 优惠券 S -->
                    <div class="coupons_block" v-if="data.coupons.length>0">
                        <ul>
                            <li v-for="(v,k) in data.coupons" :key="k" @click="receiveCoupon(v.id)">
                                <div class="price">{{$t('btn.money')}}{{v.money}} </div>
                                <div class="block">
                                    <div>优 惠 券</div>
                                    <div>满{{$t('btn.money')}}{{v.use_money}}可用</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- 优惠券 E -->
                    
                </div>
                <!-- 参加活动 -->
                <div class="goods_info_active">
                    <div class="goods_skill" v-if="data.seckills">
                        <span><el-icon style="margin:0 10px"><Timer /></el-icon>参加秒杀活动</span>
                        <span class="span_time">距离结束 {{data.seckills.format_time||'0 天 00 时 00 分 00 秒'}}</span>
                    </div>
                    <div class="goods_skill tuan_active" v-if="data.collectives"  @click="data.collective_id=-1;buy()">
                        <span><el-icon style="margin:0 10px"><UserFilled /></el-icon>参加团购活动 ( 点击开新团 ) </span>
                        <span class="span_time">团购价：{{$t('btn.money')}} {{R.formatFloat(data.goods_info.goods_price*(1-data.collectives.discount/100)||'0.00')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 需要 {{data.collectives.need}} 人</span>
                    </div>
                    <div class="tuan_list" v-if="data.collectives && data.collective_list.length>0">
                        <el-carousel autoplay  :direction="'vertical'" height="30px" indicator-position="none">
                            <el-carousel-item  v-for="(v,k) in data.collective_list" :key="k">
                                <div class="tuan_item">
                                    <img :src="v.avatar||require('@/assets/Home/user_default.png').default">
                                    <div class="nickname">{{v.nickname}}</div>
                                    <div class="btn" @click="data.collective_id=v.id;buy()">参团</div>
                                    <div class="orders_count">已经参团 {{v.orders_count}} / {{v.need}} 人</div>
                                </div>
                            </el-carousel-item>
                        </el-carousel>
                    </div>
                </div>
                
                <!-- 满减 S -->
                <div class="goods_info_full_reduction" v-if="data.full_reductions.length>0">
                    <span class="title">活动：</span>
                    <span class="act" v-for="(v,k) in data.full_reductions" :key="k"> 满{{$t('btn.money')}}{{v.use_money}}减{{$t('btn.money')}}{{v.money}}；</span>
                </div>
                <!-- 满减 E -->

                <div class="goods_info_spec" v-show="!R.isEmpty(data.goods_info.skuList)">
                    <div class="spec_list" v-for="(v,k) in data.goods_info.attrList" :key="k">
                        <span>{{v.name}}：</span>
                        <ul>
                            <li :class="(R.isEmpty(val.is_chose) || val.is_chose==false)?'':'red'" v-for="(val,key) in v.specs" :key="key" @click="attr_click(k,key)" >{{val.name}}</li>
                        </ul>
                    </div>
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
                    <!-- <div v-show="goods_info.is_groupbuy==1" class="goods_info_add_groupbuy" @click="group_buy()"><i class="icon iconfont">&#xe601;</i>选择团购</div> -->
                    <template v-if="data.seckills">
                        <div class="goods_info_buy" @click="buy()"><img :src="require('@/assets/Home/buy.png').default" /> 立即抢购</div>
                    </template>
                    <template v-else>
                        <div class="goods_info_buy" @click="buy()"><img :src="require('@/assets/Home/buy.png').default" /> 立即购买</div>
                        <div class="goods_info_add_cart" @click="add_cart()"><img :src="require('@/assets/Home/cart.png').default" />加入购物车</div>
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
                        <span class="title">{{data.store_info['store_name']||'加载中...'}}</span>
                    </div>
                    <div class="rate">
                        <span style="padding-top:2px;margin-right:10px">综合评分</span>
                        <el-rate class="rate_class" v-model="data.rate_info.scoreAll"  :score-template="'{value} 分'" text-color="#F7BA2A" show-score disabled />
                        <div class="clear"></div>
                    </div>
                    <div class="store_rate">
                        <div class="title">店铺评分：</div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px;line-height:32px">描述相符</span>
                            <el-rate class="rate_class other" v-model="data.rate_info.agreeAll"  :score-template="'{value} 分'" text-color="#F7BA2A" show-score disabled />
                            <div class="clear"></div>
                        </div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px;line-height:32px">服务态度</span>
                            <el-rate class="rate_class other" v-model="data.rate_info.serviceAll"  :score-template="'{value} 分'" text-color="#F7BA2A" show-score disabled />
                            <div class="clear"></div>
                        </div>
                        <div class="item">
                            <span style="float:left;padding-top:2px;margin-right:10px;line-height:32px">发货速度</span>
                            <el-rate class="rate_class other" v-model="data.rate_info.speedAll" :score-template="'{value} 分'" text-color="#F7BA2A" show-score  disabled />
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="store_com" style="margin-top:10px">公司名称：<em style="color:#999">{{data.store_info.store_company_name}}</em></div>
                    <div class="store_com" style="margin-bottom:10px">公司地址：<em style="color:#999">{{data.store_info.area_info+' '+data.store_info.store_address}}</em></div>
                    <div class="btn">
                        <span class="navstore" @click="$router.push('/store/'+data.store_info.id)">进入店铺</span>
                        <span class="contact" @click="$refs['chat'].openChat()">联系客服</span>
                        <div class="clear"></div>
                    </div>
                </div>
                <!-- // 销售排行 -->
                <div class="store_info">
                    <div class="store_title"><span class="title">销售排行</span></div>
                    <div class="goods_list" v-if="data.goods_info.sale_list && data.goods_info.sale_list.length>0">
                        <dl v-for="(v,k) in data.goods_info.sale_list" :key="k"><a :href="'/goods/'+v.id">
                            <dt><img :src="v.goods_master_image" :alt="v.goods_name"></dt>
                            <dd class="info">
                                <div class="title">{{v.goods_name||''}}</div>
                                <div class="price">{{$t('btn.money')}}{{v.goods_price}}</div>
                                <div class="round">{{k+1}}</div>
                            </dd></a>
                        </dl>
                    </div>
                    <div style="line-height:90px;text-align:center;color:#999;" v-else>暂时没有商品~~</div>
                </div>
            </div>
            <div class="right_item">
                 <el-tabs model-value="1" @tab-click="tabClick">
                    <el-tab-pane name="1" label="商品详情" >
                        <div v-html="editorHtml(data.goods_info.goods_content)||''"></div>
                    </el-tab-pane>
                    <el-tab-pane name="2" :label="'用户评价 ('+(data.comment_statistics.all||0)+')'" >
                        <!-- 评论 -->
                        <div class="comment_list_top">
                            <div class="left_bfb">
                                {{data.comment_statistics.rate||100}}<em style="font-size:20px">%</em>
                                <span>好评率</span>
                            </div>
                            <div class="right_comment_list">
                                <ul>
                                    <li :class="data.params.is_type==0?'red':''" @click="comment_type_click(0)">全部 ({{data.comment_statistics.all||0}})</li>
                                    <li :class="data.params.is_type==1?'red':''" @click="comment_type_click(1)">好评 ({{data.comment_statistics.good||0}})</li>
                                    <li :class="data.params.is_type==2?'red':''" @click="comment_type_click(2)">中评 ({{data.comment_statistics.commonly||0}})</li>
                                    <li :class="data.params.is_type==3?'red':''" @click="comment_type_click(3)">差评 ({{data.comment_statistics.bad||0}})</li>
                                </ul>
                            </div>
                        </div>
                        <div class="user_content_blcok_line"></div>
                        <div class="comment_list_block">
                            <ul>
                                <li v-for="(v,k) in data.comments" :key="k">
                                    <div class="comment_avatar"><el-image :src="v.avatar||require('@/assets/Home/user_default.png').default"></el-image></div>
                                    <div class="comment_nickname">{{v.nickname}}</div>
                                    <div class="comment_star"><em>评价得分：</em><div class="store_star_in"><el-rate disabled v-model="v.score" :score-template="'{value} 分'" text-color="#F7BA2A" show-score></el-rate></div></div>
                                    <div class="comment_content">评价内容：<em style="color:#999">{{v.content||'good!~'}}</em></div>
                                    <div class="comment_images" v-if="v.image.length>0">
                                        <div class="comment_image" v-for="(vo,key) in v.image" :key="key"><el-image class="el_image" :initial-index="key" :preview-src-list="v.image" :fit="'cover'" :src="vo"  /></div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="reply" v-if="v.reply!=''">
                                        <div class="comment_avatar"><el-image :src="require('@/assets/Home/kefu.png').default"></el-image></div>
                                        <div class="comment_nickname">售后客服</div>
                                        <div class="comment_content">回复内容：<em >{{v.reply||'good!~'}}</em></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="fy" v-if="data.comments.length>0" style="margin-top:20px">
                            <div class="fy">
                                <el-pagination background 
                                layout="total, prev, pager, next" 
                                :page-size="data.params.per_page" 
                                @current-change="handleCurrentChange"
                                :page-count="data.params.last_page"
                                :current-page="data.params.current_page"
                                :total="data.params.total">
                                </el-pagination>
                            </div>
                        </div>
                        <el-empty v-else />
                        
                    </el-tab-pane>
                    <el-tab-pane name="3" label="售后服务" force-render>
                        <div v-html="editorHtml(data.store_info.after_sale_service)"></div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="clear"></div>
        </div>

        <!-- 聊天 -->
        <chat ref="chat" :position="true" :params="data.chatParams" />

    </div>
</template>

<script>
import Chat from "@/components/common/chat"
import {reactive,watch,onMounted,computed,onBeforeUnmount,getCurrentInstance} from "vue"
import {useRoute,useRouter} from 'vue-router'
import { useStore } from 'vuex'
import dayjs from "dayjs"
import {ArrowLeftBold,ArrowRightBold,Star,Timer,UserFilled,SemiSelect,Plus} from '@element-plus/icons'
import PicZoom from '@/components/home/vue-piczoom.vue' // 放大镜组件 
import {editorHandle,formatTime} from '@/plugins/config'
export default {
    components: {PicZoom,ArrowLeftBold,ArrowRightBold,Star,Timer,UserFilled,SemiSelect,Plus,Chat}, // Chat
    setup(props) {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const router = useRouter()
        const store = useStore()
        const data = reactive({
            goods_info:[],
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
            chatParams:{},
            rate_info:{},
            buy_num:1, // 购买数量
            goods_id:0,
            chose_img_pos:0,
            chose_spec:[],
            sku_id:0,
            isFav:false,
            save_history:true,
            coupons:[], // 优惠券
            full_reductions:[], // 满减
            seckills:false, // 秒杀
            collectives:false, // 拼团
            collective_list:[], // 正在进行的团
            collective_id:0,
            timeObj:null,
        })

        const routeUriIndex = store.state.load.routeUriIndex
        const isLogin = computed( () => store.state.login.loginData[routeUriIndex].isLogin )

        const loadData = async ()=>{
            if(!data.goods_id) return
            const resp = await proxy.R.get('/goods/'+data.goods_id,{saveCheck:true})
            if(!resp.code){
                data.goods_info = resp
                data.store_info = resp.store_info
                data.rate_info = resp.rate
                data.comment_statistics = resp.comment_statistics
                data.coupons = resp.coupons || [] // 优惠券
                data.full_reductions = resp.full_reductions||[] // 满减
                data.seckills = resp.seckills // 秒杀
                timing(data.seckills) // 倒计时
                data.collectives = resp.collectives // 拼团
                data.collective_list = resp.collective_list // 拼团

                is_fav() // 获取收藏情况
                // 存储游览记录
                if(data.save_history){
                    save_history_fun(data.goods_info);
                }
                // 设置聊天参数
                data.chatParams = {provider:isLogin.value?'users':'anonymous',rid:data.store_info.user_id,rtype:'users',token:localStorage.getItem('token')}
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
        // 收藏
        const goods_fav = async ()=>{
            await proxy.R.post('/user/favorites',{is_type:0,out_id:data.goods_id})
            is_fav()
        }
        // 是否收藏
        const is_fav = async ()=>{
            data.isFav = await proxy.R.get('/is_fav',{is_type:0,out_id:data.goods_id})
        }
        const receiveCoupon = (id)=>{
            proxy.R.post('/user/coupon/receive',{id:id}).then(res=>{
                if(!res.code) return proxy.$message.success(proxy.$t('msg.success'))
            })
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
            if(data.goods_info.skuList && data.goods_info.skuList.length>0 && data.sku_id == 0) return proxy.$message.error(proxy.$t('home.goods.skuErr')); 
            let params = {
                order:[
                    {
                        goods_id:data.goods_info.id, // 商品ID
                        sku_id:data.sku_id, // SKUid 没有则为0
                        buy_num:data.buy_num, // 购买数量
                        collective_id:data.collective_id, // 拼团ID 非必传
                    },
                ],
                ifcart:0, // 是否购物车
            };

            // 恢复 collective_id
            data.collective_id = 0

            let str = window.btoa(JSON.stringify(params)); 
            router.push("/order/before/"+str);

        }
        // 加入购物车
        const add_cart = ()=>{
            let params = {
                goods_id:data.goods_info.id, // 商品ID
                sku_id:data.sku_id, // SKUid 没有则为0
                buy_num:data.buy_num, // 购买数量
            };
            proxy.R.post('/user/carts',params).then(res=>{
                if(!res.code) proxy.$message.success(proxy.$t('msg.success'))
                store.dispatch('init/loadCart')
            }).catch((err)=>{
                console.log(err)
            })
        }

        // tabs 选项卡改变
        const tabClick = (e)=>{
            if(e.paneName == 2) comment_type_click(0)
        }

        // 评论选择
        const comment_type_click = (e)=>{
            data.params.is_type = e
            goods_comments()
        }

        // 获取评论
        const goods_comments = async ()=>{
            let resp = await proxy.R.get('/goods_comments/'+data.goods_id,data.params)
            data.comments = resp.data
            data.params.total = parseInt(resp.total)
            data.params.per_page = parseInt(resp.per_page)
            data.params.current_page = parseInt(resp.current_page)
        }

        const timing = (market)=>{
            if(market){
                if(data.timeObj != null) clearInterval(data.timeObj)
                data.timeObj = setInterval(()=>{
                    let timeVal = dayjs(market.end_time).diff(dayjs(),'s')
                    data.seckills.format_time = formatTime(timeVal)// 时间戳转换
                    if(dayjs(market.end_time).unix()<dayjs().unix()){
                        clearInterval(data.timeObj);
                        router.go(0);
                    }
                },1000)
            }
        }

        // 规格属性选择
        const attr_click = (e,index)=>{
            data.goods_info.attrList[e]['specs'].forEach((res,key)=>{
                res.is_chose = false;
                if(key == index){
                    res.is_chose = true;
                }
            });
            data.buy_num = 1;

            // 循环获取选择的规格属性
            let chose_spec = [];
            data.goods_info.attrList.forEach(res=>{
                res['specs'].forEach(listRes=>{
                    if(!proxy.R.isEmpty(listRes.is_chose) && listRes.is_chose == true){
                        chose_spec.push(listRes.id);
                    }
                });
            });
            
            data.chose_spec = chose_spec;
            
            // 如果选择数量和规格数量一致则表示选择完所有的规格属性
            if(data.chose_spec.length == data.goods_info.attrList.length){
                get_spec_attr_data();
            }

            // this.$forceUpdate();
        }

        // 根据选择的规格属性去获取数据库存在的规格数据
        const get_spec_attr_data = ()=>{
            data.goods_info.skuList.forEach(res=>{
                let a = 0;
                data.chose_spec.forEach(specRes=>{
                    res.spec_id.forEach(itm=>{
                        if(itm == specRes){
                             a += 1;
                        }
                    })
                });
                if(a == data.goods_info.attrList.length){
                    data.chose_spec_info = res;
                    data.sku_id = res.id;
                    return change_goods_info(res);
                }
            });
        }

        const change_goods_info = (res)=>{
            data.goods_info.goods_price = res.goods_price;
            data.goods_info.goods_market_price = res.goods_market_price;
            data.goods_info.goods_stock = res.goods_stock;
        }

        // 存储数据记录
        const save_history_fun = (goods_info)=>{
            data.save_history=false;
            let goods_json = localStorage.getItem('shop_goods_historys');
            let goods_list = [];

            if(!proxy.R.isEmpty(goods_json)){
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

        }

        // 分页
        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
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

        // 页面取消挂载
        onBeforeUnmount(()=>{
            if(data.timeObj != null) clearInterval(data.timeObj)
        })

        return {
            data,handleCurrentChange,tabClick,
            pre_img,next_img,click_silde_img,goods_fav,receiveCoupon,comment_type_click,editorHtml,
            change_buy_num,buy,add_cart,attr_click,
        }
    },
   
    
  
    // beforeRouteUpdate (to, from, next) {
    //     console.log(to,from);
    //     if(from.params.id != to.params.id){
    //         this.goods_id = to.params.id;
    //         this.get_goods_info();
    //     }
    //     next();
    //     // react to route changes...
    //     // don't forget to call next()
    // }
}
</script>
<style lang="scss" scoped>
.goods_info_content{
    .right_item{
        border:1px solid #efefef;
        padding:20px;
        box-sizing: border-box;
        min-height: 600px;
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
                    left: 150px;
                }
            }
            .right_comment_list{
                ul{
                    display: flex;
                }
                ul li{
                    cursor: pointer;
                    flex: 1;
                    line-height: 48px;
                    margin-left: 35px;
                    margin-top: 25px;
                    color:#666;
                    height: 48px;
                    font-size: 14px;
                    background: #efefef;
                    border-radius: 3px;
                    text-align: center;
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
                            height: 55px;
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
                    // line-height: 20px;
                }
                .title{
                    color:#000;
                    line-height: 35px;
                }
            }
            .rate{
                font-size: 14px;
                display: flex;
                align-items: center;
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
        .goods_skill{
            margin-bottom: 10px;
            background: #fef0f0;
            border:1px solid  #fde2e2;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content:space-between;
            span{
                color:#f56c6c;
                line-height: 40px;
                display: flex;
                align-items: center;
            }
            span.span_time{
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
        height: 30px;
        overflow: hidden;
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
    // margin-top: 10px;
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
        margin-top: 10px;
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
    .reply{
        margin-top: 10px;
        margin-left: 40px;
        .comment_nickname{color:#ca151e}
        .comment_content{color:#ca151e}
        .comment_content{color:#ca151e}
    }
}
</style>