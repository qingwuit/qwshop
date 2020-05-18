<template>
    <div class="create_order">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>

        <div class="join_over width_center_1200">
            <div class="join_bzt">
                <el-steps :active="2" finish-status="process" simple >
                    <el-step title="我的购物车" icon="el-icon-shopping-cart-full"></el-step>
                    <el-step title="物流地址" icon="el-icon-map-location"></el-step>
                    <el-step title="选择支付" icon="el-icon-mouse"></el-step>
                    <el-step title="支付成功" icon="el-icon-check"></el-step>
                </el-steps>
            </div>
        </div>

        <div class="address_chose width_center_1200">
            <div class="address_chose_title">
                选择支付方式
            </div>
            <div class="pay_list">
                <ul>
                    <li @click="pay('wxpay_native')"><el-image src="/pc/pc_wxpay.jpg"></el-image></li>
                    <li @click="pay('alipay_pc')"><el-image src="/pc/pc_alipay.jpg"></el-image></li>
                </ul>
            </div>
            
            <div class="address_chose_title">
                订单商品信息
            </div>
            <div class="order_list_title">
                <el-row>
                    <el-col :span="10"><div class="th_title">商品信息</div></el-col>
                    <el-col :span="4"><div class="th_title">属性信息</div></el-col>
                    <el-col :span="4"><div class="th_title">单价</div></el-col>
                    <el-col :span="2"><div class="th_title">数量</div></el-col>
                    <el-col :span="2"><div class="th_title">优惠</div></el-col>
                    <el-col :span="2"><div class="th_title">小计</div></el-col>
                </el-row>
            </div>

            <div class="store_list" v-for="(v,k) in befor_order_list" :key="k">
                <div class="store_info">
                    <dl>
                        <dt><el-image :src="v[0].store_logo"></el-image></dt>
                        <dd>{{v[0].store_name}}</dd>
                    </dl>
                    <div class="clear"></div>
                </div>
                <div class="order_list">
                    <ul>
                        <li v-for="(vo,key) in v" :key="key">
                            <el-row>
                                <el-col :span="10"><div class="goods_info">
                                    <dl>
                                        <dt><el-image :src="vo.image"></el-image></dt>
                                        <dd>{{vo.goods_name}}</dd>
                                    </dl>
                                </div></el-col>
                                <el-col :span="4"><div class="th_title">{{$isEmpty(vo.goods_spec)?' - ':vo.goods_spec}}</div></el-col>
                                <el-col :span="4"><div class="th_title">￥{{vo.goods_price}}</div></el-col>
                                <el-col :span="2"><div class="th_title" style="text-indent:30px;">x {{vo.goods_num}}</div></el-col>
                                <el-col :span="2"><div class="th_title" style="text-indent:40px;"> - </div></el-col>
                                <el-col :span="2"><div class="x_price" style="text-indent:30px;">￥{{vo.goods_price*vo.goods_num}}</div></el-col>
                            </el-row>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 二维码扫描 -->
            <el-dialog title="扫描支付" :visible.sync="dialogVisible" width="20%">
                <div class="scan_qr"><el-image :src="qr_code"></el-image></div>
            </el-dialog>



            <div class="foot_create_order">
                <div class="total_price">
                    <span>结算：</span>
                    ￥ {{total_price}}
                    <font>( {{total_freight>0?'运费:'+total_freight:'免运费'}} )</font>
                </div>
            </div>
        </div>

        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          befor_order_list:[],
          total_price:0,
          total_freight:0,
          dialogVisible:false,
          qr_code:'',
          timeObj:null,
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 根据购物车的数据或者商品的数据来获取订单的数据
        get_order_list:function(){
            this.$post(this.$api.homeGetBeforOrder,{type:this.$route.params.type,info:this.$route.params.info}).then(res=>{
                this.befor_order_list = res.data;
                this.befor_order_list.forEach(res=>{
                    res.forEach(goodsRes=>{
                        this.total_price += (goodsRes.goods_price*goodsRes.goods_num);
                    });
                });
            });
        },
        // 计算运费
        get_order_info:function(){
            this.$post(this.$api.homeGetOrderInfoByOrderNo,{order_no:this.$route.params.order_no}).then(res=>{
                res.data.forEach(orderRes=>{
                    this.total_freight += orderRes.freight_money;
                });
            });
        },
        // 支付
        pay:function(payment_name){
            this.$post(this.$api.homePayOrder,{payment_name:payment_name,order_no:this.$route.params.order_no}).then(res=>{
                if(res.code==200){
                    if(payment_name == 'wxpay_native'){
                        this.qr_code = res.data.qr_code;
                        this.dialogVisible = true;
                        this.timeObj = setInterval(()=>{
                            this.check_pay(this.$route.params.order_no+'|'+payment_name);
                        },1000);
                    }else{
                        // var newwindow = window.open("#","_blank");
                        // newwindow.document.write(res.data); // 打开新页面
                        const div = document.createElement('div') // 创建div
                        div.innerHTML = res.data // 将返回的form 放入div
                        document.body.appendChild(div)
                        document.forms[0].submit()
                    }
                }else{
                    this.$message.error('调起支付失败');
                }
            });
        },
        // 定时查询是否支付成功
        check_pay:function(out_trade_no){
            this.$post(this.$api.homeCheckWxpayNative,{out_trade_no:out_trade_no}).then(res=>{
                if(res.code == 200){
                    clearInterval(this.timeObj);
                    this.$router.push('/order/pay_success');
                }
            });
        },
    },
    created() {
        this.get_order_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.scan_qr{
    text-align: center;
}
.empty_address{
    margin-bottom: 80px;
    line-height: 105px;
    border:3px solid #efefef;
    border-radius: 3px;
    text-align: center;
    width: 292px;
    height: 105px;
    span{
        color:#ca151e;
    }
    a:hover{
        color:#ca151e;
    }
}
.join_bzt{
    margin-top:40px;
}
.foot_create_order{
    margin-top: 50px;
    float: right;
    margin-right: 30px;
    .total_price{
        font-size: 28px;
        color:#ca151e;
        line-height: 28px;
        span{
            float: left;
            font-size: 14px;
            color:#333;
            
        }
        font{
            float: right;
            font-size: 14px;
            color:#666;
            margin-left: 20px;
        }
    }
    .foot_create_order_btn{
        float:right;
        margin-top: 20px;
        margin-bottom: 40px;
    }
}
.store_list{
    .store_info{
        margin-top: 20px;
        margin-left: 15px;
        
        dl dt{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f1f1f1;
            float: left;
            margin-right: 15px;
        }
        dl dd{
            float: left;
            line-height: 30px;
        }
    }
    ul li{
        border-bottom: 1px solid #efefef;
        padding-bottom: 20px;
        margin-bottom: 20px;
        .x_price{
            color:#ca151e;
        }
        text-indent: 20px;
    }
    ul li .goods_info{
        text-indent: 0px;
    }
    ul li:last-child{
        border-bottom: none;
        padding-bottom: 0px;
        margin-bottom: 0px;
    }
    .order_list{
        margin-top: 20px;
        color:#666;
        font-size: 12px;
        border:1px solid #efefef;
        padding:20px;
        line-height: 40px;
        .goods_info{
            dl dt{
                width: 40px;
                height: 40px;
                background: #f8f8f8;
                float: left;
                margin-right: 15px;
            }
            dl dd{
                line-height: 20px;
            }
        }
    }
}
.order_list_title{
    color:#666;
    background: #f2f2f2;
    line-height: 40px;
    text-indent: 20px;
}
.address_chose{
    margin-top: 75px;
    font-size: 14px;
    .pay_list{
        margin-bottom: 30px;
        ul:after{
            content:'';
            display: block;
            clear:both;
        }
        ul li{
            float: left;
            width: 292px;
            height: 105px;
            border-radius: 3px;
            border:1px solid #efefef;
            margin-right: 20px;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
        }
        ul li:hover{
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        }
    }
    .address_chose_title{
        font-size: 16px;
        clear: both;
        font-weight: bold;
        padding-bottom: 20px;
    }
    
}
</style>