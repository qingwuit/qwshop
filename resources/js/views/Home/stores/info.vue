<template>
    <div class="home_store_info">
        <banner :banner="data.store_info.store_slide||[{image:require('@/assets/Home/store_banner.png').default}]" :height="350" />
        
        <div class="store_info_block w1200">
            <div class="left_item">
                <div class="store_info">
                    <div class="store_title">
                        <span class="tip">店铺</span>
                        <span class="title">{{data.store_info['store_name']||'加载中...'}}</span>
                    </div>
                    <div class="rate">
                        <span style="float:left;padding-top:2px;margin-right:10px">综合评分</span>
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
                        <span :class="isFav?'navstore':''"  @click="storeFav()">{{isFav?'已收藏':'收藏店铺'}}</span>
                        <span class="contact" @click="$refs['chat'].openChat()">联系客服</span>
                        <div class="clear"></div>
                    </div>
                </div>
                <!-- // 销售排行 -->
                <div class="store_info">
                    <div class="store_title"><span class="title">销售排行</span></div>
                    <div class="goods_list" v-if="data.store_info.sale_list.length>0">
                        <dl v-for="(v,k) in data.store_info.sale_list" :key="k"><a :href="'/goods/'+v.id">
                            <dt><img :src="v.goods_master_image" :alt="v.goods_name" fit="cover" /></dt>
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
                <!-- 搜索条件 S -->
                <div class="goods_where">
                    <div class="item">
                        <div class="title">筛选排序：</div>
                        <div class="list">
                            <div class="other">
                                <ul>
                                    <li @click="sortChange('')" :class="(R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type=='')?'red':''"
                                        >默认
                                        <div class="sorts">
                                            <el-icon :class="((R.isEmpty(data.base64Decode.sort_order) || data.base64Decode.sort_order=='asc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'" ><CaretTop /></el-icon>
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'" ><CaretBottom /></el-icon>
                                        </div>
                                    </li>
                                    <li @click="sortChange('goods_price')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price')?'red':''">
                                        价格
                                        <div class="sorts">
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price'))?'caret red':'caret'" ><CaretTop /></el-icon>
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price'))?'caret red':'caret'" ><CaretBottom /></el-icon>
                                        </div>
                                    </li>
                                    <li @click="sortChange('goods_sale')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_sale')?'red':''">
                                        销量
                                        <div class="sorts">
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" ><CaretTop /></el-icon>
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" ><CaretBottom /></el-icon>
                                        </div>
                                    </li>
                                    <li @click="sortChange('order_comment_count')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='order_comment_count')?'red':''">
                                        评论
                                        <div class="sorts">
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'" ><CaretTop /></el-icon>
                                            <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'" ><CaretBottom /></el-icon>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 搜索条件 E -->

                <!-- 产品列表 S -->
                <div class="goods_list" v-if="data.list.length>0">
                    <ul>
                        <li v-for="(v,k) in data.list" :key="k"><router-link :to="'/goods/'+v.id">
                            <div class="product_act_in">
                                <dl>
                                    <dt><el-image class="el_image" :src="v.goods_master_image" :alt="v.goods_name" /></dt>
                                    <dd class="product_title" :title="v.goods_name">{{v.goods_name}}</dd>
                                    <dd class="product_subtitle">{{v.goods_subname||'-'}}</dd>
                                    <dd class="product_price">￥{{v.goods_price}}<span>{{v.goods_market_price}}元</span></dd>
                                </dl>
                            </div>
                        </router-link></li>
                    </ul>
                    <div class="clear"></div>
                    <div class="fy" style="margin-top:30px">
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
                <a-empty v-else style="margin-top:70px" />
                <!-- 产品列表 E -->
            </div>
        </div>
        <!-- S -->

        <!-- 聊天 -->
        <chat ref="chat" :position="true" :params="data.chatParams" />
    </div>
</template>

<script>
import Chat from "@/components/common/chat"
import Banner from '@/components/home/banner'
import {reactive,onMounted,computed,getCurrentInstance} from "vue"
import { useRoute,useRouter } from 'vue-router'
import { useStore } from 'vuex'
import { CaretTop,CaretBottom } from '@element-plus/icons'
export default {
    components: {Banner,CaretTop,CaretBottom,Chat},
    setup() {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const router = useRouter()
        const store = useStore()
        const data = reactive({
            id:0,
            params:{
                page:1,
                per_page:40,
                last_page:1,
                total:0,
            },
            base64Decode:{},
            base64Code:'',
            store_info:{
                area_info:'',
                store_address:'',
                sale_list:[],
            },
            chatParams:{},
            list:[],
            comment_statistics:[],
            rate_info:{},
            isFav:false,
        })

        const routeUriIndex = store.state.load.routeUriIndex
        const isLogin = computed( () => store.state.login.loginData[routeUriIndex].isLogin )

        const loadData = ()=>{
            if(!proxy.R.isEmpty(route.params.id)) data.id = route.params.id
            get_store_info();
            get_goods_list();
            is_fav();
        }

        const get_store_info = () =>{
            proxy.R.get('/store/'+data.id).then(res=>{
                if(!res.code){
                    data.store_info = res
                    data.rate_info = res.rate;
                    if(res.store_verify != 4 || res.store_status != 1){
                        router.go(-1)
                    }
                    // 设置聊天参数
                    data.chatParams = {provider:isLogin.value?'users':'anonymous',rid:data.store_info.user_id,rtype:'users',token:localStorage.getItem('token')}
                }
            })
        }

        const get_goods_list = async ()=>{
            data.base64Decode.store_id = data.id
            data.base64Code = window.btoa(JSON.stringify(data.base64Decode));
            data.params.params = data.base64Code;
            const resp = await proxy.R.get('/goods',data.params)
            if(!resp.code){
                data.list = resp.data
                data.params.total = parseInt(resp.total)
                data.params.last_page = parseInt(resp.last_page)
                data.params.per_page = parseInt(resp.per_page)
                data.params.current_page = parseInt(resp.current_page)
            }
        }

        const sortChange = (e)=>{
            data.params.page = 1;
            if(e == ''){
                if(!proxy.R.isEmpty(data.base64Decode.sort_type)){
                    data.base64Decode.sort_order= 'asc';
                }else{
                    if(data.base64Decode.sort_order== 'desc'){
                        data.base64Decode.sort_order= 'asc';
                    }else{
                        data.base64Decode.sort_order= 'desc';
                    }
                }
                data.base64Decode.sort_type = undefined
            }else{
                if(data.base64Decode.sort_type == undefined || data.base64Decode.sort_type != e){
                    data.base64Decode.sort_order= 'asc';
                }else{
                    if(data.base64Decode.sort_order== 'desc'){
                        data.base64Decode.sort_order= 'asc';
                    }else{
                        data.base64Decode.sort_order= 'desc';
                    }
                }
                
                data.base64Decode.sort_type= e;
            }
            data.base64Decode.store_id = data.id
            data.base64Code = window.btoa(JSON.stringify(data.base64Decode));
            get_goods_list();
        }

        // 页面切换
        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }


        // 收藏
        const storeFav = async ()=>{
            await proxy.R.post('/user/favorites',{is_type:1,out_id:data.id})
            is_fav()
        }
        // 是否收藏
        const is_fav = async ()=>{
            data.isFav = await proxy.R.get('/is_fav',{is_type:1,out_id:data.id})
        }

        onMounted(() => {
            loadData()
        })

        return {
            data,
            storeFav,sortChange,handleCurrentChange
        }
    },
 

};
</script>
<style lang="scss" scoped>
.store_info_block{
    margin-top: 30px;
    .right_item{
        float: right;
        width: 946px;
        .goods_list{
            margin-top: 30px;
            ul li{
                width: 220px;
                height: 300px;
                margin-bottom: 14px;
                margin-right: 20px;
                box-sizing: border-box;
                &:nth-child(4n){
                    margin-right: 0;
                }
                
                float: left;
                .product_act_in{
                    width: 220px;
                    height: 300px;
                    background: #fff;
                    box-sizing: border-box;
                    -webkit-transition: all .2s linear;
                    transition: all .2s linear;
                    // background: #fafafa;
                    border:1px solid #f1f1f1;
                }
                dl dt{
                    width: 140px;
                    height: 140px;
                    margin:0 auto;
                    padding-top: 20px;
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
                    line-height: 30px;
                }
                dl dd.product_title{
                    font-size: 14px;
                    margin-top: 30px;
                    height: 30px;
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
        .goods_where{
            // border: 1px solid #efefef;
            line-height: 50px;
            background: #fafafa;
            font-size: 14px;
            width: 100%;
            .item{
                padding:0 20px;
                box-sizing: border-box;
                border-bottom: 1px solid #efefef;
                &:last-child{
                    border-bottom: none;
                }
                &:after{
                    clear:both;
                    display: block;
                    content:'';
                }
                .title{
                    float: left;
                    margin-right: 20px;
                }
                .list{
                    float: left;
                    ul li{
                        cursor: pointer;
                    }
                    .other{
                        ul li{
                            float: left;
                            margin-right: 20px;
                            padding:0 10px;
                            position: relative;
                            &:hover{
                                color:#ca151e;
                            }
                            &.red{
                            color:#ca151e;
                            }
                            .sorts{
                                position: absolute;
                                top:0;
                                right: 0;
                                color:#666;
                                .caret{
                                    font-size: 12px;
                                    position: absolute;
                                    -webkit-transform-origin-x: 0; //缩小后文字居左
                                    -webkit-transform: scale(0.80);   //关键
                                    &:first-child{
                                    top:16px;
                                    right:-5px; 
                                    }
                                    &:last-child{
                                    top:22px;
                                    right:-5px; 
                                    }
                                    &.red{
                                        color:#ca151e;
                                    }
                                }
                            }
                    
                        }
                    }
                }
            }
        }
    }
    .left_item{
        width: 234px;
        float: left;
        margin-right: 20px;
        .navstore{
            background: #ca151e;
            color:#fff;
        }
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
                            height: 40px;
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
    &:after{
        clear: both;
        display: block;
        content:'';
    }
}
.rate_class{
    font-size: 14px;
    // float: left;
    // margin-top: 15px;
    &.other{
        // margin-top: 6px;
    }
}
</style>