<template>
    <div class="user_default2">
        <div class="user_info_balance">
            <dl class="item1">
                <dt><a-font type="iconyue"></a-font></dt>
                <dd class="title">账号余额</dd>
                <dd>{{user_info.money||'0.00'}} 元</dd>
            </dl>
            <dl class="item2">
                <dt><a-font type="iconjifen"></a-font></dt>
                <dd class="title">账号积分</dd>
                <dd>{{user_info.integral||'0.00'}} 积分</dd>
            </dl>
            <dl class="item3">
                <dt><a-font type="iconyue"></a-font></dt>
                <dd class="title">冻结资金</dd>
                <dd>{{user_info.frozen_money||'0.00'}} 元</dd>
            </dl>
            <div class="clear"></div>
        </div>


        <div class="order_status_block">
            <ul>
                <li>
                    <router-link :to="{name:'home_user_order',params:{status:1}}">
                    <a-font type="icondengdaifh"></a-font>
                    <span><a-badge :count="count[0]" :offset="[11,-7]">待支付</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'home_user_order',params:{status:2}}">
                    <a-font type="iconwuliu"></a-font>
                    <span><a-badge :count="count[1]" :offset="[11,-7]">待发货</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'home_user_order',params:{status:3}}">
                    <a-font type="iconchanpin1"></a-font>
                    <span><a-badge :count="count[2]" :offset="[11,-7]">待收货</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'home_user_order',params:{status:4}}">
                    <a-font type="iconpinglun"></a-font>
                    <span><a-badge :count="count[3]" :offset="[11,-7]">待评论</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'home_user_order',params:{status:5}}">
                    <a-font type="iconshouhou"></a-font>
                    <span><a-badge :count="count[4]" :offset="[11,-7]">售后处理</a-badge></span>
                    </router-link>
                </li>
            </ul>
        </div>

        <div class="user_main">
            <div class="block_title">
                <span><router-link to="/user/order">查看更多</router-link></span>
                近期订单
            </div>
            <div class="x20"></div>
            <div class="order_list" v-if="order.length>0">
                <div class="order_item" v-for="(v,k) in order" :key="k">
                    <div class="order_item_title">
                        <span>{{v.created_at}}<font :color="v.order_status==6?'#42b983':'#ca151e'">{{v.order_status_cn||'-'}}</font></span>
                        订单号：{{v.order_no||'-'}}
                    </div>
                    <div class="order_item_list"  @click="$router.push('/user/order/'+v.id)">
                        <ul>
                            <li v-for="(vo,key) in v.order_goods" :key="key"><router-link to="">
                                <div class="order_thumb"><img :src="vo.goods_image||require('@/asset/order/default.png')" :alt="vo.goods_name"></div>
                                <div class="order_list_title">{{vo.goods_name||'-'}}</div>
                                <div class="order_list_attr">{{vo.sku_name||'-'}}</div>
                                <div class="order_list_num">x {{vo.buy_num||'1'}}</div>
                                <div class="order_list_price">￥{{vo.total_price||'0.00'}}</div>
                            </router-link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <a-empty v-else />
        </div>

        <div class="user_main">
            <div class="block_title">
                <span><router-link to="/user/favorite">查看更多</router-link></span>
                近期收藏
            </div>
            <div class="x20"></div>
            <div class="fav_item_list" v-if="fav.length>0">
                <dl v-for="(v,k) in fav" :key="k"><router-link :to="'/goods/'+v.out_id">
                    <dt><img :src="v.goods_master_image" :alt="v.goods_name"></dt>
                    <dd class="title">{{v.goods_name}}</dd>
                    <dd class="price">￥{{v.goods_price}}</dd>
                </router-link></dl>
            </div>
            <a-empty v-else />
        </div>

        <div class="user_main">
            <div class="block_title">
                游览历史
            </div>
            <div class="x20"></div>
            <div class="fav_item_list" v-if="history.length>0">
                <dl v-for="(v,k) in history" :key="k"><router-link :to="'/goods/'+v.id">
                    <dt><img :src="v.image" :alt="v.goods_name"></dt>
                    <dd class="title">{{v.goods_name}}</dd>
                    <dd class="price">￥{{v.goods_price}}</dd>
                </router-link></dl>
            </div>
            <a-empty v-else />
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          order:[],
          fav:[],
          history:[],
          count:[],
          user_info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_info(){
            this.$get(this.$api.homeUser+'/default',{per_page:4,is_type:0}).then(res=>{
                this.user_info = res.data.user;
                this.order = res.data.order.data;
                this.fav = res.data.fav.data;
                this.count = res.data.count;
            })
        },

    },
    created() {
        this.get_info();
        let history = localStorage.getItem('shop_goods_historys')
        if(!this.$isEmpty(history)){
            this.history = JSON.parse(history) 
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.fav_item_list{
    &:after{
        clear:both;
        display: block;
        content:'';
    }
  
    dl{
        width: 200px;
        float: left;
        margin-right: 32px;
        &:last-child{
            margin-right: 0;
        }
        dt{
            box-sizing: border-box;
            width: 200px;
            height: 200px;
            border:1px solid #efefef;
            img{width: 200px;}
            margin-bottom: 10px;
            background: #efefef;
        }
        dd{
            text-align: center;
            margin-bottom: 10px;
            &.price{color:#ca151e}
            &.title{height: 40px;overflow: hidden;}
        }
    }
}
.order_item_list{
    ul li{
        padding:20px 15px;
        border-top: 1px solid #efefef;
        border-left: 1px solid #efefef;
        border-right: 1px solid #efefef;
        &:last-child{
            border-bottom: 1px solid #efefef;
        }
        &:after{
            clear:both;
            display: block;
            content:'';
        }
        .order_thumb{
            width: 42px;
            height: 42px;
            background: #efefef;
            border:1px solid #efefef;
            box-sizing: border-box;
            float: left;
            margin-right: 15px;
            img{
                width: 40px;
                height: 40px;
            }
        }
        .order_list_title{
            width: 280px;
            float: left;
            font-size: 12px;
            line-height: 20px;
            height: 40px;
            overflow: hidden;
        }

        .order_list_attr{
            float: left;
            text-align: center;
            width: 190px;
            line-height: 40px;
        }
        .order_list_num{
            float: left;
            text-align: center;
            width: 140px;
            line-height: 40px;
        }
        .order_list_price{
            float: right;
            color:#ca151e;
            text-align: center;
            width: 90px;
            line-height: 40px;
            // font-weight: bold;
        }
    }
}
.order_item_title{
    background: #f6f6f6;
    line-height: 40px;
    padding: 0 15px;
    margin: 20px 0;
    span{
        float: right;
        font-size: 12px;
        font{
            margin-left:15px;
        }
    }
}
.order_status_block{
    background: #fff;
    padding: 20px;
    font-size: 14px;
    margin-top: 20px;
    margin-bottom: 20px;
    ul{
        &:after{
            clear:both;
            display: block;
            content:'';
        }
        li{
            position: relative;
            &:first-child i{
                font-weight: bold;
                font-size: 26px;
            }
            &:nth-child(1) i{
                top:-2px;
            }
            &:nth-child(2) i{
                left :36px;
            }
             &:nth-child(3) i{
                 left :40px;
                 top:-2px;
            }
             &:nth-child(4) i{
                top:1px;
                left :40px;
            }
            float: left;
            text-align: center;
            width: 180px;
            i{
                position: absolute;
                font-size: 22px;
                margin-right: 4px;
                left :34px;
            }
        
        }
    }
}
.user_info_balance{
    dl{
        float: left;
        background: #fff;
        width: 302px;
        display: block;
        padding: 20px;
        dt{
            color: #fff;
            font-size: 26px;
            float: left;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            line-height: 56px;
            text-align: center;
            margin-right: 20px;
        }
        dd{
            color:#666;
            &.title{
                font-size: 16px;
                margin-top: 8px;
            }
        }
    }
    
}
.item1{
    margin-right: 20px;
    dt{background: #409eff;}
}
.item2{
    margin-right: 20px;
    dt{background: #e6a23c;}
}
.item3{
    dt{background: #f56c6c;}
}
</style>