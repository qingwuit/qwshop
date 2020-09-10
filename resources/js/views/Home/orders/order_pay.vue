<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><a-icon type="shopping-cart" />我的购物车</div>
                <div class="item check"><a-icon type="car" />物流地址</div>
                <div class="item check"><a-icon type="account-book" />选择支付</div>
                <div class="item"><a-icon type="check-circle" />支付成功</div>
            </div>
        </div>

        <!-- 当前用户余额 S -->
        <div class="block">
            <div class="title"><div class="now_money">当前账号余额：￥{{userInfo.money}}</div></div>
            
        </div>
        <!-- 当前用户余额 E -->

        <!-- 选择支付方式 S -->
        <div class="block">
            <div class="title">选择支付方式</div>
            <div class="pay">
                <ul>
                    <li><img :src="require('@/asset/order/pc_money_pay.png')" alt="mpay"></li>
                    <li><img :src="require('@/asset/order/pc_wxpay.jpg')" alt="wechatpay"></li>
                    <li><img :src="require('@/asset/order/pc_alipay.jpg')" alt="alipay"></li>
                </ul>
            </div>
        </div>
        <!-- 选择支付方式 E -->

        <!-- 预生成订单信息 S -->
        <div class="block">
            <div class="title">订单商品信息</div>
            <div class="goods_list">
                <div class="goods_th">
                    <a-row>
                        <a-col :span="10">商品信息</a-col>
                        <a-col :span="4">属性信息</a-col>
                        <a-col :span="4">单价</a-col>
                        <a-col :span="2">数量</a-col>
                        <a-col :span="2">优惠</a-col>
                        <a-col :span="2">小计</a-col>
                    </a-row>
                </div>

                <div class="store_list" v-for="(v,k) in order" :key="k">
                    <div class="store_title">
                        <span>订单号：{{v.order_no}}</span>
                        <!-- <font color="#ca151e">订单统计：￥{{v.total_price}}</font> -->
                        <div class="clear"></div>
                        <div class="og_list">
                            <ul>
                                <li v-for="(vo,key) in v.order_goods" :key="key">
                                    <a-row>
                                        <a-col :span="10">
                                            <dl>
                                                <dt><img :src="vo.goods_image" :alt="vo.goods_name"></dt>
                                                <dd :title="vo.goods_name">{{vo.goods_name}}</dd>
                                            </dl>
                                        </a-col>
                                        <a-col :span="4"><div class="goods_info_th">{{vo.sku_name}}</div></a-col>
                                        <a-col :span="4"><div class="goods_info_th">{{vo.goods_price}}</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">{{vo.buy_num}}</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">-</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th red">￥{{vo.total_price}}</div></a-col>
                                    </a-row>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sum_block">
                <div class="total">总金额：<span>￥{{total}}</span>( 不包含运费 )</div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- 预生成订单信息 E -->
    </div>
</template>

<script>
import {mapState} from 'vuex'
export default {
    components: {},
    props: {},
    data() {
      return {
          order:[],
          total:0,
      };
    },
    watch: {},
    computed: {...mapState('homeLogin',['userInfo'])},
    methods: {
        // 订单建立前预览商品信息
        create_order_before(){
            this.$get(this.$api.homeOrder+'/create_order_after',{params:this.$route.params.params}).then(res=>{
                res.data.forEach(item=>{
                    this.total += parseFloat(item.total_price);
                })
                this.order = res.data;                
            })
        },
    },
    created() {
        this.create_order_before();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.step_bar{
    margin:40px 0;
}
.block{
    .title{
        font-size: 16px;
        clear: both;
        font-weight: bold;
        padding-bottom: 20px;
    }
    .pay{
        margin-bottom: 30px;
        ul:after{
            content:'';
            display: block;
            clear:both;
        }
        ul li{
            float: left;
            cursor: pointer;
            width: 294px;
            height: 107px;
            border-radius: 3px;
            border: 1px solid #efefef;
            margin-right: 20px;
            transition: all 0.2s linear;
            &:hover{
                box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
            }
        }
    }
    .store_list{
        margin-top: 20px;
        .og_list{
            margin-top: 20px;
            color: #666;
            font-size: 12px;
            border: 1px solid #efefef;
            padding: 20px 0;
            line-height: 40px;
            .red{
                color:#ca151e;
            }
            dl{
                &:after{
                    clear:both;
                    display: block;
                    content:''
                }
                dt{
                    width: 40px;
                    height: 40px;
                    display: block;
                    float: left;
                    background: #f8f8f8;
                    margin-right: 15px;
                    margin-left: 20px;
                    border:1px solid #efefef;
                    img{
                        margin:0;
                        width: 100%;
                        height: 100%;
                        border-radius: 0;
                        vertical-align:unset;
                    }
                }
                dd{
                    float: left;
                    width: 400px;
                    height: 40px;
                    text-overflow: hidden;
                    line-height: 20px;
                }
            }
            .goods_info_th{
                text-indent: 20px;
            }
        }
        .store_title{
            img{
                width: 35px;
                height: 35px;
                margin-right: 10px;
                margin-left: 20px;
                border-radius: 50%;
            }
            span{
                line-height: 35px;
                font-size: 16px;
                float: left;
            }
            font{
                float:right;
                line-height: 35px;
                padding-right: 15px;
            }
          
        }
    }
    .sum_block{
        text-align: right;
        .total{
            line-height: 60px;
            span{
                font-size: 28px;
                color: #ca151e;
                margin-right: 16px;
            }
        }
        .btn{
            background: #ca151e;
            color:#fff;
            border-radius: 3px;
            width: 80px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            display: block;
            float:right;
        }
    }
    .goods_th{
        background: #f2f2f2;
        line-height: 40px;
        text-indent: 20px;
    }

    
 
}
.now_money{
    font-size: 16px;
    background: #333;
    color:#fff;
    margin-bottom: 20px;
    border:1px solid #fff;
    display: inline-block;
    padding:4px 15px;
    font-weight: normal;
}
</style>