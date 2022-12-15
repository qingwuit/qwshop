<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><el-icon><Shop /></el-icon>  我的购物车</div>
                <div class="item check"><el-icon><MessageBox /></el-icon>物流地址</div>
                <div class="item"><el-icon><Money /></el-icon>选择支付</div>
                <div class="item"><el-icon><CircleCheckFilled /></el-icon>支付成功</div>
            </div>
        </div>

        <!-- 地址信息选择 S -->
        <div class="block">
            <div class="title">选择送货地址</div>
            <div class="address_list" v-if="data.address.length>0">
                <ul>
                    <li :class="v.is_default==1?'red':''" v-for="(v,k) in data.address" :key="k" @click="addressChange(v.id)">
                        <div class="receive_name">
                            {{v.receive_name}}
                            <span>({{v.receive_tel}})</span>
                        </div>
                        <div class="area_info">{{v.area_info+' '+v.address}}</div>
                        <div class="cmarker"> <img :src="v.is_default==1?require('@/assets/Home/checkAddress.png').default:require('@/assets/Home/checkAddress2.png').default" alt=""> </div>
                    </li>
                </ul>
            </div>

            <div class="empty_address" v-else>
                没有设置收货地址，请先前往<router-link to="/user/address">设置</router-link>
            </div>
        </div>
        <!-- 地址信息选择 E -->

        <!-- 预生成订单信息 S -->
        <div class="block">
            <div class="title">商品信息</div>
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
                            <router-link :to="'/store/'+v.store_info.id" class="float_left">
                                <img  class="float_left" :src="v.store_info.store_logo||require('@/assets/Home/default_store_image.png').default" :alt="v.store_info.store_name">
                                <span  class="float_left">{{v.store_info.store_name}}</span>
                            </router-link>
                            <div v-if="v.is_coupon" class="float_right coupon_order"><el-icon color="#ca151e" size="18"><PriceTag /></el-icon>优惠券：<el-select style="width:130px;margin-right:10px;" v-model="v.coupon_id"><el-option :value="0" label="不使用优惠券"></el-option><el-option v-for="(val,key) in v.coupons" :key="key" :label="$t('btn.money')+val.money+' 优惠券'" :value="val.id" ></el-option></el-select></div>
                            <div class="clear"></div>
                        </div>

                        <div class="og_list">
                            <ul>
                                <li v-for="(vo,key) in v.goods_list" :key="key">
                                    <el-row>
                                        <el-col :span="10">
                                            <dl>
                                                <dt><img :src="vo.goods_master_image" :alt="vo.goods_name"></dt>
                                                <dd :title="vo.goods_name">{{vo.goods_name}}</dd>
                                            </dl>
                                        </el-col>
                                        <el-col :span="4"><div class="goods_info_th">{{vo.sku_name}}</div></el-col>
                                        <el-col :span="4"><div class="goods_info_th">{{vo.goods_price}}</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th">{{vo.buy_num}}</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th">-</div></el-col>
                                        <el-col :span="2"><div class="goods_info_th red">￥{{vo.total}}</div></el-col>
                                    </el-row>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="remark">
                <div class="goods_th">备注</div>
                <div class="remark_input">
                    <textarea cols="60" rows="3" v-model="data.remark"></textarea>
                </div>
            </div>

            <div class="sum_block">
                <div class="total">总金额：<span>￥{{data.total}}</span>( 不包含运费和优惠 )</div>
                <div :class="loading?'btn hide':'btn'" @click="createOrder">{{data.loading?'加载中..':'创建订单'}}</div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- 预生成订单信息 E -->
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import { Shop,PriceTag,CircleCheckFilled,Money,MessageBox,Check} from '@element-plus/icons'
import {useRouter,useRoute} from 'vue-router'
export default {
    components: {Shop,PriceTag,CircleCheckFilled,Money,MessageBox,Check},
    setup() {
        const {proxy} = getCurrentInstance()
        const router = useRouter()
        const route = useRoute()
        const data = reactive({
            address:[],
            order:[],
            address_id:0,
            total:0,
            remark:'',
            loading:false,
        })

        const loadData = ()=>{
            address();
            createOrderBefore()
        }

        // 获取地址列表
        const address = ()=>{
            proxy.R.get('/user/addresses').then(res=>{
                if(!res.code){
                    data.address = res.data
                    res.data.map(item=>{
                        if(item.is_default == 1) data.address_id = item.id
                    })
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        // 地址选择
        const addressChange = (id)=>{
            proxy.R.get('/user/addresses/default/'+id).then(res=>{
                if(!res.code) address()
            })
        }

        // 下单前数据处理
        const createOrderBefore = ()=>{
            proxy.R.post('/user/order/before',{params:route.params.params}).then(res=>{
                if(!res.code){
                    res.map(item=>{
                        item.goods_list.map(item2=>{
                            data.total += item2.total
                        })
                    })
                    data.order = res;
                }else{
                    router.go(-1)
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        // 创建订单
        const createOrder = ()=>{
            if(data.loading) return proxy.$message.error(proxy.$t('home.goods.createOrderThr'))
            let coupon_id = []
            data.order.map(item=>{
                if(!proxy.R.isEmpty(item.coupon_id))coupon_id.push(item.coupon_id)
            })
            let params = {
                params:route.params.params,
                address_id:data.address_id,
                coupon_id:coupon_id.join(','),
                remark:data.remark,
            }
            data.loading = true
            proxy.R.post('/user/order/create',params).then(res=>{
                if(!res.code){
                    let str = window.btoa(JSON.stringify(res))
                    // 如果是REDIS 
                    if(res.order_id.length==0){
                        let timers = setInterval( async ()=>{
                            let resp = await proxy.R.post('/user/order/after',{params:str})
                            if(!resp.code){
                                if(resp.length>0){
                                    clearInterval(timers)
                                    resp.map(afterItem=>{
                                        res.order_id.push(afterItem.id);
                                    })
                                    data.loading = false
                                    str = window.btoa(JSON.stringify(res))
                                    router.push('/order/pay/'+str)
                                } 
                            }
                        },500)
                    }else{
                        data.loading = false
                        router.push('/order/pay/'+str)
                    }
                }
            }).catch(error=>{
                console.log(error)
                data.loading = false
            }).finally(()=>{
                // data.loading = false
            })
        }

        onMounted(() => {
            loadData()
        })

        return {
            data,
            addressChange,createOrder,
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
.block{
    .title{
        font-size: 16px;
        clear: both;
        font-weight: bold;
        padding-bottom: 20px;
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
            cursor: pointer;
            &.hide{
                background: #ccc;
                cursor: not-allowed;
                color:#666;
            }
        }
    }
    .goods_th{
        background: #f2f2f2;
        line-height: 40px;
        text-indent: 20px;
    }
    .remark{
        margin-top: 40px;
        .remark_input{
            margin:20px 0;
            textarea{
                border-color:#cfcfcf;
                outline: none;
                border-radius: 4px;
                padding:8px;
            }
        }
    }
    .empty_address{
        margin-bottom: 80px;
        line-height: 100px;
        border: 3px solid #efefef;
        border-radius: 3px;
        text-align: center;
        width: 292px;
        height: 105px;
        a{
            font-weight: bold;
            color:#ca151e;
        }
    }
    .address_list{
        margin-bottom: 30px;
        ul{
            &:after{
                clear:both;
                content:'';
                display: block;
            }
            li{
                cursor: pointer;
                float: left;
                box-sizing: border-box;
                width: 292px;
                height: 105px;
                border-radius: 3px;
                border: 2px solid #efefef;
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 20px;
                position: relative;
                color:#666;
                &:nth-child(4n){
                    margin-right: 0;
                }
                .receive_name{
                    margin-bottom: 10px;
                    font-weight: bold;
                    line-height: 18px;
                    color:#333;
                    span{
                        font-weight: normal;
                    }
                }
                .cmarker{
                    position: absolute;
                    right: -16px;
                    bottom: -17px;
                    font-size: 30px;
                    color:#333;
                }
                &.red{
                    border-color:#ca151e;
                    .cmarker{
                        color:#ca151e;
                    }
                }
            }
        }
    }
}
.coupon_order{
    display: flex;
    align-items: center;
    color:#ca151e;
    i{margin-right: 5px;}
}
</style>