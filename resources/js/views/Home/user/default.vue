<template>
    <div class="user_index">
        <div class="user_info_balance">
            <ul>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe8cc;</i></dt>
                    <dd>账号余额</dd>
                    <dd class="money">{{user_info.money}} 元</dd>
                </dl></li>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe629;</i></dt>
                    <dd>账号积分</dd>
                    <dd class="money">{{user_info.integral}} 积分</dd>
                </dl></li>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe8cc;</i></dt>
                    <dd>冻结资金</dd>
                    <dd class="money">{{user_info.frozen_money}} 元</dd>
                </dl></li>
            </ul>
        </div>
        <div class="user_info_order_num_show">
            <ul>
                <li><router-link :to="{ name: 'user_order',params:{order_type:'NOPAY'}}">
                     <el-badge :value="order_num.no_pay" class="item" :hidden="order_num.no_pay==0">
                    <i class="icon iconfont">&#xe617;</i>
                    <span>待支付</span>
                    </el-badge>
                </router-link></li>
                <li><router-link :to="{ name: 'user_order',params:{order_type:'WAITSEND'}}">
                    <el-badge :value="order_num.wait_send" class="item" :hidden="order_num.wait_send==0">
                    <i class="icon iconfont">&#xe63b;</i>
                    <span>待发货</span>
                    </el-badge>
                </router-link></li>
                <li><router-link :to="{ name: 'user_order',params:{order_type:'WAITREC'}}">
                    <el-badge :value="order_num.wait_rec" class="item" :hidden="order_num.wait_rec==0">
                    <i class="icon iconfont">&#xe61d;</i>
                    <span>待收货</span>
                    </el-badge>
                </router-link></li>
                <li><router-link :to="{ name: 'user_order',params:{order_type:'WAITCOMMENT'}}">
                    <el-badge :value="order_num.wait_comment" class="item" :hidden="order_num.wait_comment==0">
                    <i class="icon iconfont">&#xe610;</i>
                    <span>待评论</span>
                    </el-badge>
                </router-link></li>
                <li><router-link :to="{ name: 'user_order',params:{order_type:'REFUND'}}">
                    <el-badge :value="order_num.refund" class="item" :hidden="order_num.refund==0">
                    <i class="icon iconfont">&#xe64c;</i>
                    <span>售后处理</span>
                    </el-badge>
                </router-link></li>
            </ul>
        </div>
        <div class="user_index_myorder">
            <div class="user_index_myorder_title">
                近期订单<span><router-link to="/user/order">查看更多</router-link></span>
            </div>
            <div class="user_index_line"></div>
            <div class="user_index_order_list" v-if="order_list.length>0">
                <div class="user_index_store_list" v-for="(v,k) in order_list" :key="k">
                    <div class="user_index_store_title"><router-link :to="'/store/'+v[0].store_id"><img width="40px" height="40px" :src="v[0].store_logo">{{v[0].store_name}}<span>前往店铺</span></router-link></div>
                    <ul>
                        <li v-for="(vo,key) in v" :key="key">
                            <router-link :to="'/goods/info/'+vo.id">
                            <div class="user_order_goods_thumb"><img width="40px" height="40px" :src="vo.image" alt=""></div>
                            <div class="user_order_goods_title">{{vo.order_name}}</div>
                            <div class="user_order_goods_price">￥{{vo.total_price}}</div>
                            <div class="user_order_goods_num">订单号：{{vo.order_no}}</div>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 没有订单则 -->
            <div class="empty_order" v-else>
                <dl>
                    <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                    <dd>主人，您近期还没有购买任何商品哟~</dd>
                    <dd class="btn"><router-link to="">前往商城</router-link></dd>
                </dl>
            </div>
        </div>

        <!-- 近期收藏 -->
        <div class="user_index_myorder">
            <div class="user_index_myorder_title">
                近期收藏<span><router-link to="">查看更多</router-link></span>
            </div>
            <div class="user_index_line"></div>
            <div class="user_index_order_list" v-if="fav_list.length>0">
                <div class="user_index_store_list">
                    <ul>
                        <li v-for="(v,k) in fav_list" :key="k">
                            <router-link :to="'/goods/info/'+v.goods.id">
                            <div class="user_order_goods_thumb"><img width="40px" height="40px" :src="v.goods.image" :alt="v.goods.goods_name"></div>
                            <div class="user_order_goods_title">{{v.goods.goods_name}}</div>
                            <div class="user_order_goods_price">￥{{v.goods.goods_price}}</div>
                            <div class="user_order_goods_btn"><router-link :to="'/goods/info/'+v.goods.id">前往商品</router-link></div>
                            </router-link>
                        </li>
                        
                    </ul>
                </div>

            </div>

            <!-- 没有订单则 -->
            <div class="empty_order" v-else>
                <dl>
                    <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                    <dd>主人，您近期还没有收藏任何商品哟~</dd>
                    <dd class="btn"><router-link to="">前往商城</router-link></dd>
                </dl>
            </div>
        </div>


        <!-- 猜你喜欢 -->
        <div class="user_index_history_all">
            <div class="user_index_myorder_title">
                游览历史
            </div>
            <div class="user_index_line"></div>
            <div class="user_index_history" v-if="history_goods_list.length>0">
                <ul>
                    <li v-for="(v,k) in history_goods_list" :key="k">
                        <router-link :to="'/goods/info/'+v.id">
                            <dl>
                                <dt><img width="200px" height="200px" :src="v.image" alt=""></dt>
                                <dd class="title">{{v.goods_name}}</dd>
                                <dd class="price">￥{{v.goods_price}}</dd>
                            </dl>
                        </router-link>
                    </li>
                   
                </ul>
            </div>                

            <!-- 没有订单则 -->
            <div class="empty_order" v-else>
                <dl>
                    <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                    <dd>主人，您近期还没有游览任何商品哟~</dd>
                    <dd class="btn"><router-link to="">前往商城</router-link></dd>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          order_list:[],
          fav_list:[],
          order_num:{},
          user_info:{},
          history_goods_list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_default_info:function(){
            this.$get(this.$api.homeGetUserDefault).then(res=>{
                this.order_list = res.data.store_list;
                this.order_num = res.data.order_num;
                this.fav_list= res.data.fav_list;
                this.user_info= res.data.user_info;
            });
        },
        get_history_goods:function(){
            let goods_json = localStorage.getItem('shop_goods_historys');
            let goods_list = [];
            if(!this.$isEmpty(goods_json)){
                goods_list = JSON.parse(goods_json);
            }
            this.history_goods_list = goods_list;
        }
    },
    created() {
        this.get_default_info();
        this.get_history_goods();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_index_history_all{
    background: #fff;
    margin-top: 20px;
    padding: 20px;
    .user_index_myorder_title{
        border-left: 3px solid #ca151e;
        padding-left: 20px;
        
    }
    .user_index_line{
        height: 1px;
        background: #efefef;
        margin:15px 0;
    }
}


.user_index_history{
    border-bottom: none;
    margin-top: 20px;
    ul:after{
        content:'';
        clear: both;
        display: block;
    }
    ul li{
        float: left;
        margin-right: 32px;
        dl dd{
            width:200px;
            overflow: hidden;
            font-size: 14px;
            text-align: center;
        }
        dl dd.title{
            color:#666;
            width: 180px;
            margin:0 auto;
            height: 40px;
            margin-top: 10px;
        }
        dl dd.price{
            color:#ca151e;
            margin-top:15px;
        }
    }
    ul li:last-child{
        margin-right: 0;
    }
    
}

.user_index_myorder{
    background: #fff;
    margin-top: 20px;
    padding:20px;
    .user_index_myorder_title{
        border-left: 3px solid #ca151e;
        padding-left: 20px;
        span{
            a{font-size: 12px;color:#666;}
            a:hover{
                color:#ca151e;
            }
            float:right;
            font-size: 12px;
            padding-right:10px; 
        }
    }
    .user_index_line{
        height: 1px;
        background: #efefef;
        margin:15px 0;
    }
    
    .user_index_store_list{
        margin:20px 0;
        
        .user_index_store_title{
            background: #efefef;
            line-height: 60px;
            padding: 0 15px;
            border-radius: 3px;
            font-size: 14px;
            a{
                color:#333;
                font-weight: bold;
            }
            img{
                float: left;
                border-radius: 50%;
                border:1px solid #efefef;
                margin-right: 15px;
                margin-top: 9px;
            }
            span{
                background: #efefef;
                color:#ca151e;
                border:1px solid #ca151e;
                padding: 4px 5px;
                border-radius: 3px;
                font-weight: normal;
                font-size: 12px;
                margin-left: 15px;
            }
        }
    }
    ul{
        border:1px solid #efefef;
        margin-bottom: 20px;
        li{
            padding: 20px;
            border-bottom: 1px solid #efefef;
            .user_order_goods_thumb{
                margin-right: 15px;
                float: left;
                img{
                    border-radius: 3px;
                }
            }
            .user_order_goods_title{
                float: left;
                height: 40px;
                width: 300px;
            }
            .user_order_goods_price{
                float: left;
                height: 40px;
                line-height: 40px;
                width: 180px;
                text-align: center;
                color:#ca151e;
            }
            .user_order_goods_num{
                color:#999;
                line-height: 40px;
                text-align: center;
            }
            .user_order_goods_btn{
                line-height: 25px;
                border: 1px solid #efefef;
                float: left;
                border-radius: 3px;
                padding: 0 8px;
                margin-top:6px;
                margin-left: 200px;
                a{
                    color:#999;
                }
            }
            a{
                font-size: 14px;
            }
            a:hover{
                color:#ca151e;
            }
        }
        li:last-child{
            border-bottom: none;
        }
        li:after{
            content:'';
            display: block;
            clear: both;
        }
    }
    
}
.user_info_balance{
    ul li{
        background: #fff;
        width: 302px;
        margin-right: 20px;
        float: left;
        dl{
            padding:20px;
        }
        dl:after{
            content:'';
            display: block;
            clear: both;
        }
        dl dt{
            color:#fff;
            background: #409eff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            line-height: 60px;
            text-align: center;
            margin-right: 20px;
            float: left;
            i{
                font-size: 24px;
            }
        }
        dl dd{
            margin-top: 8px;
        }
        dl dd.money{
            font-size: 14px;
            color:#666;
            margin-top: 5px;
        }
    }
    ul li:nth-child(2) dl dt{
        background: #e6a23c;
    }
    ul li:nth-child(3) dl dt{
        background: #f56c6c;
    }
    ul li:last-child{
        margin-right: 0;
    }
    ul:after{
        content:'';
        display: block;
        clear: both;
    }
}
.user_info_order_num_show{
    background: #fff;
    padding:20px;
    font-size: 14px;
    height: 30px;;
    margin-top: 20px;
    ul li{
        float: left;
        text-align: center;
        width: 180px;
        i{
            font-size: 22px;
            margin-right: 6px;
            position:absolute;
            left: -30px;
            top:-2px;
        }
        
    }
    ul li:nth-child(2) i{
        font-size: 26px;
        left: -33px;
        top:-5px;
    }
    ul li:last-child{
        margin-right: 0;
    }
}
</style>