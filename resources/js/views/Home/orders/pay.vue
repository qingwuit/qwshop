<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><el-icon><Shop /></el-icon>  我的购物车</div>
                <div class="item check"><el-icon><MessageBox /></el-icon>物流地址</div>
                <div class="item check"><el-icon><Money /></el-icon>选择支付</div>
                <div class="item"><el-icon><CircleCheckFilled /></el-icon>支付成功</div>
            </div>
        </div>

        <!-- 当前用户余额 S -->
        <div class="block">
            <div class="title"><div class="now_money">当前账号余额：￥{{data.userInfo.money||0.00}}</div></div>
            
        </div>
        <!-- 当前用户余额 E -->

        <!-- 选择支付方式 S -->
        <div class="block">
            <div class="title">选择支付方式</div>
            <div class="pay">
                <ul>
                    <li @click="data.visible=true;"><img :src="require('@/assets/Home/pc_money_pay.png').default" alt="mpay"></li>
                    <li @click="pay('wechat')"><img :src="require('@/assets/Home/pc_wxpay.jpg').default" alt="wechatpay"></li>
                    <li @click="pay('alipay')"><img :src="require('@/assets/Home/pc_alipay.jpg').default" alt="alipay"></li>
                </ul>
            </div>
        </div>
        <!-- 选择支付方式 E -->

        <!-- 预生成订单信息 S -->
        <div class="block">
            <div class="title">订单商品信息</div>
            <div class="goods_list">
                <div class="goods_th">
                    <el-row>
                        <el-col :span="10">商品信息</el-col>
                        <el-col :span="4">属性信息</el-col>
                        <el-col :span="4">单价</el-col>
                        <el-col :span="2">数量</el-col>
                        <el-col :span="2">优惠</el-col>
                        <el-col :span="2">小计</el-col>
                    </el-row>
                </div>

                <div class="store_list" v-for="(v,k) in data.order" :key="k">
                    <div class="store_title">
                        <div>
                            <span class="float_left">订单号：{{v.order_no}}</span>
                            <div class="float_right" v-if="v.coupon_money && v.coupon_money>0"><span style="font-size:14px;color:#666;">优惠金额：</span> <font color="#ca151e">-{{v.coupon_money}}</font></div>
                            <div class="clear"></div>
                        </div>
                        
                        <!-- <font color="#ca151e">订单统计：￥{{v.total_price}}</font> -->
                        
                        <div class="og_list">
                            <ul>
                                <li v-for="(vo,key) in v.order_goods" :key="key">
                                    <el-row>
                                        <el-col :span="10">
                                            <dl>
                                                <dt><img :src="vo.goods_image" :alt="vo.goods_name"></dt>
                                                <dd :title="vo.goods_name">{{vo.goods_name}}</dd>
                                            </dl>
                                        </el-col>
                                        <el-col :span="4"><div class="goods_info_th">{{vo.sku_name}}</div></el-col>
                                        <el-col :span="4"><div class="goods_info_th">{{vo.goods_price}}</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th">{{vo.buy_num}}</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th">-</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th red">￥{{vo.total_price}}</div></el-col>
                                    </el-row>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sum_block">
                <div class="total">总金额：<span>￥{{data.total}}</span>( {{data.freight_money>0?'包含运费:'+data.freight_money+'元':'免运费'}} )</div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- 预生成订单信息 E -->

        <!-- 支付密码输入 pay('balance') -->
        <el-dialog width="30%" v-model="data.visible" title="输入6位支付密码" > 
            <input class="pay_password" type="password" v-model="data.pay_password" placeholder="pay password" />
            <div style="padding:25px 0 10px 0;text-align:center">
                <div class="error_btn" @click="pay('balance')">确定支付</div>
            </div>
        </el-dialog>

        <!-- 二维码扫描 -->
        <el-dialog destroy-on-close  v-model="data.dialogVisible" width="30%" title="支付扫码"  @closed="qrcodeClose" style="text-align:center;" > 
            <qrcode-vue style="margin-bottom:30px" :value="data.qr_code" :size="200" class="qrcode_class" />
        </el-dialog>
    </div>
</template>

<script>
import {reactive,onMounted,onBeforeUnmount,getCurrentInstance} from "vue"
import { Shop,CircleCheckFilled,Money,MessageBox,Check} from '@element-plus/icons'
import { useStore } from 'vuex'
import {useRouter,useRoute} from 'vue-router'
import QrcodeVue from 'qrcode.vue'
export default {
    components: {Shop,CircleCheckFilled,Money,MessageBox,Check,QrcodeVue},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const router = useRouter()
        const route = useRoute()
        const data = reactive({
            order:[],
            total:0,
            freight_money:0,
            visible:false,
            dialogVisible:false,
            qr_code:'',
            pay_password:'',
            userInfo:{},
            loading: false,
            timeObj:null,
        })

        const loadData = async ()=>{
            createOrderBefore()
            data.userInfo = await store.dispatch('login/getUserSer')
        }

        // 下单前数据处理
        const createOrderBefore = ()=>{
            proxy.R.post('/user/order/after',{params:route.params.params}).then(res=>{
                if(!res.code){
                    res.map(item=>{
                        data.total += parseFloat(item.total_price);
                        data.freight_money += parseFloat(item.freight_money);
                    })
                    data.order = res;
                }else{
                    router.go(-1)
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        const pay = (payment_name)=>{
            const params = JSON.parse(window.atob(route.params.params));
            let order_id = params.order_id.join(',');
            let sendData = {order_id:order_id,payment_name:payment_name,device:'scan',pay_password:''};
            if(payment_name == 'balance'){
                sendData.pay_password = data.pay_password;
            }
            proxy.R.post('/user/order/pay',sendData).then(res=>{
                if(res.qr_code || res.code_url){
                    if(payment_name == 'alipay') data.qr_code = res.qr_code
                    if(payment_name == 'wechat') data.qr_code = res.code_url
                    data.dialogVisible = true;
                    if(data.timeObj != null ) qrcodeClose()
                    data.timeObj = setInterval(()=>{
                        proxy.R.post('/user/order/check',{order_id:params.order_id[0]||0}).then((checkRes)=>{
                            if(!checkRes.code){
                                qrcodeClose()
                                router.push('/order/success')
                            }
                        })
                    },1000)
                }else{
                    if(payment_name == 'balance' && !res.code) return router.push('/order/success')
                }
                
            })
        }

     
        const qrcodeClose = ()=>{
            if(data.timeObj != null) clearInterval(data.timeObj);
        }

        onMounted(()=>{
            loadData()
        })

        onBeforeUnmount(()=>{
            qrcodeClose()
        })


        return {
            data,
            pay,qrcodeClose
        }
    },

};
</script>
<style lang="scss" scoped>
.step_bar{
    margin:40px 0;
    .step{
        height: 46px;
        line-height: 46px;
        background: #F5F7FA;
        margin-bottom: 50px;
        display: flex;
    }
    .step .item{
        font-size: 16px;
        color:#C0C4CC;
        flex: 1;
        justify-content: center;
        align-items: center;
        display: flex;
        i{
            margin-right: 10px;
        }
        text-align: center;
        border-right: 4px solid #fff;
    }
    .step .item.check{
        color:#333;
        font-weight: bold;
    }
}
.qrcode_class{
    width: 200px;
    height: 200px;
    margin: 0 auto;
    display: block;
}
.pay_password{
    width: 100%;
    border:1px solid #efefef;
    border-radius: 3px;
    height: 40px;
    box-sizing: border-box;
    padding:15px;
    outline: #ccc;
}

.error_btn{
    background: #ca151e;
    color:#fff;
    border-radius: 3px;
    box-sizing: border-box;
    font-size: 14px;
    padding: 5px 15px;
    margin-right: 10px;
    display: inline;
    cursor: pointer;
    box-shadow: 0 2px 0 rgba(0,0,0,.015);
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
            margin-top: 10px;
            color: #666;
            font-size: 12px;
            border: 1px solid #efefef;
            padding: 20px 0;
            line-height: 40px;
            li{
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #efefef;
                &:last-child{
                    margin-bottom: 0;
                    padding-bottom: 0;
                    border-bottom: none;
                }
            }
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
                font-size: 15px;
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