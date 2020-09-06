<template>
    <div class="user_default2">
        <div class="user_info_balance">
            <dl class="item1">
                <dt><a-font type="iconyue"></a-font></dt>
                <dd class="title">账号余额</dd>
                <dd>10.00 元</dd>
            </dl>
            <dl class="item2">
                <dt><a-font type="iconjifen"></a-font></dt>
                <dd class="title">账号积分</dd>
                <dd>20.00 积分</dd>
            </dl>
            <dl class="item3">
                <dt><a-font type="iconyue"></a-font></dt>
                <dd class="title">冻结资金</dd>
                <dd>0.00 元</dd>
            </dl>
            <div class="clear"></div>
        </div>


        <div class="order_status_block">
            <ul>
                <li>
                    <router-link to="#">
                    <a-font type="icondengdaifh"></a-font>
                    <span><a-badge count="5" :offset="[11,-7]">待支付</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link to="#">
                    <a-font type="iconwuliu"></a-font>
                    <span><a-badge count="5" :offset="[11,-7]">待发货</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link to="#">
                    <a-font type="iconchanpin1"></a-font>
                    <span><a-badge count="0" :offset="[11,-7]">待收货</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link to="#">
                    <a-font type="iconpinglun"></a-font>
                    <span><a-badge count="0" :offset="[11,-7]">待评论</a-badge></span>
                    </router-link>
                </li>
                <li>
                    <router-link to="#">
                    <a-font type="iconshouhou"></a-font>
                    <span><a-badge count="0" :offset="[11,-7]">售后处理</a-badge></span>
                    </router-link>
                </li>
            </ul>
        </div>

        <div class="user_main">
            <div class="block_title">
                <span><router-link to="#">查看更多</router-link></span>
                近期订单
            </div>
            <div class="x20"></div>
            <div class="order_list" v-if="order.length>0||true">
                <div class="order_item">
                    <div class="order_item_title">
                        <span>2020-01-06 15:11<font color="#ca151e">等待发货</font></span>
                        订单号：202001071215136783
                    </div>
                    <div class="order_item_list">
                        <ul>
                            <li><router-link to="">
                                <div class="order_thumb"><img :src="''||require('@/asset/order/default.png')" alt=""></div>
                                <div class="order_list_title">三星C24F396FH曲面显示器23.5英寸电脑显示器24液晶显示屏幕超22</div>
                                <div class="order_list_attr">-</div>
                                <div class="order_list_num">X 1</div>
                                <div class="order_list_price">￥2500.00</div>
                            </router-link></li>
                            <li><router-link to="">
                                <div class="order_thumb"><img :src="''||require('@/asset/order/default.png')" alt=""></div>
                                <div class="order_list_title">三星C24F396FH曲面显示器23.5英寸电脑显示器24液晶显示屏幕超22</div>
                                <div class="order_list_attr">-</div>
                                <div class="order_list_num">X 1</div>
                                <div class="order_list_price">￥2500.00</div>
                            </router-link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <a-empty v-else />
        </div>

        <div class="user_main">
            <div class="block_title">
                <span><router-link to="#">查看更多</router-link></span>
                近期收藏
            </div>
            <div class="x20"></div>
            <div class="fav_item_list" v-if="true">
                <dl><router-link to="">
                    <dt><img src="" alt=""></dt>
                    <dd class="title">Haier/海尔 KFR-33GW/10EBBAL13U1 1.5匹智能壁挂式家用空调挂机 智能操控 快速冷暖 送装一体</dd>
                    <dd class="price">￥250.00</dd>
                </router-link></dl>
                <dl><router-link to="">
                    <dt><img src="" alt=""></dt>
                    <dd class="title">Haier/海尔 KFR-33GW/10EBBAL13U1 1.5匹智能壁挂式家用空调挂机 智能操控 快速冷暖 送装一体</dd>
                    <dd class="price">￥250.00</dd>
                </router-link></dl>
                <dl><router-link to="">
                    <dt><img src="" alt=""></dt>
                    <dd class="title">Haier/海尔 KFR-33GW/10EBBAL13U1 1.5匹智能壁挂式家用空调挂机 智能操控 快速冷暖 送装一体</dd>
                    <dd class="price">￥250.00</dd>
                </router-link></dl>
                <dl><router-link to="">
                    <dt><img src="" alt=""></dt>
                    <dd class="title">Haier/海尔 KFR-33GW/10EBBAL13U1 1.5匹智能壁挂式家用空调挂机 智能操控 快速冷暖 送装一体</dd>
                    <dd class="price">￥250.00</dd>
                </router-link></dl>
       
            </div>
            <a-empty v-else />
        </div>

        <div class="user_main">
            <div class="block_title">
                游览历史
            </div>
            <div class="x20"></div>
            <div class="fav_item_list" v-if="false">
                <dl><router-link to="">
                    <dt><img src="" alt=""></dt>
                    <dd class="title">Haier/海尔 KFR-33GW/10EBBAL13U1 1.5匹智能壁挂式家用空调挂机 智能操控 快速冷暖 送装一体</dd>
                    <dd class="price">￥250.00</dd>
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
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {},
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
            width: 40px;
            height: 40px;
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