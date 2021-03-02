<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><a-icon type="shopping-cart" />我的购物车</div>
                <div class="item check"><a-icon type="car" />物流地址</div>
                <div class="item"><a-icon type="account-book" />选择支付</div>
                <div class="item"><a-icon type="check-circle" />支付成功</div>
            </div>
        </div>

        <!-- 地址信息选择 S -->
        <div class="block">
            <div class="title">选择送货地址</div>
            <div class="address_list" v-if="address.length>0">
                <ul>
                    <li :class="v.is_default==1?'red':''" v-for="(v,k) in address" :key="k" @click="addressChange(v.id)">
                        <div class="receive_name">
                            {{v.receive_name}}
                            <span>({{v.receive_tel}})</span>
                        </div>
                        <div class="area_info">{{v.area_info+' '+v.address}}</div>
                        <div class="cmarker"><a-font type="iconcmarker"></a-font></div>
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
                        <div>
                            <router-link :to="'/store/'+v.store_info.id" class="float_left">
                                <img :src="v.store_info.store_logo||require('@/asset/store/default_store_image.png')" :alt="v.store_info.store_name">
                                <span>{{v.store_info.store_name}}</span>
                            </router-link>
                            <div v-if="v.is_coupon" class="float_right">优惠券：<a-select style="width:130px;margin-right:10px;" v-model="v.coupon_id"><a-select-option :value="0" >不使用优惠券</a-select-option><a-select-option v-for="(val,key) in v.coupons" :key="key" :value="val.id" >{{val.money}}优惠券</a-select-option></a-select></div>
                            <div class="clear"></div>
                        </div>

                        <div class="og_list">
                            <ul>
                                <li v-for="(vo,key) in v.goods_list" :key="key">
                                    <a-row>
                                        <a-col :span="10">
                                            <dl>
                                                <dt><img :src="vo.goods_master_image" :alt="vo.goods_name"></dt>
                                                <dd :title="vo.goods_name">{{vo.goods_name}}</dd>
                                            </dl>
                                        </a-col>
                                        <a-col :span="4"><div class="goods_info_th">{{vo.sku_name}}</div></a-col>
                                        <a-col :span="4"><div class="goods_info_th">{{vo.goods_price}}</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">{{vo.buy_num}}</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">-</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th red">￥{{vo.total}}</div></a-col>
                                    </a-row>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="remark">
                <div class="goods_th">备注</div>
                <div class="remark_input">
                    <textarea cols="60" rows="3" v-model="remark"></textarea>
                </div>
            </div>

            <div class="sum_block">
                <div class="total">总金额：<span>￥{{total}}</span>( 不包含运费和优惠 )</div>
                <div :class="loading?'btn hide':'btn'" @click="create_order">{{loading?'加载中..':'创建订单'}}</div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- 预生成订单信息 E -->
    </div>
</template>

<script>

export default {
    components: {},
    props: {},
    data() {
      return {
          address:[],
          order:[],
          address_id:0,
          total:0,
          remark:'',
          loading:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        onload(){
            this.get_address();
            this.create_order_before();
        },
        // 获取地址
        get_address(){
            this.$get(this.$api.homeAddress).then(res=>{
                res.data.forEach(item=>{
                    if(item.is_default==1){
                        this.address_id = item.id;
                    }
                })
                this.address = res.data;
            })
        },
        // 地址选择
        addressChange(id){
            this.$put(this.$api.homeAddress+'/default/set',{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_address();
                    this.$message.success('设置地址成功');
                    this.address_id = id;
                }else{
                    this.$message.destroy();
                    this.$message.error(res.msg)
                }
            })
        },
        // 订单建立前预览商品信息
        create_order_before(){
            this.$get(this.$api.homeOrder+'/create_order_before',{params:this.$route.params.params}).then(res=>{
                if(res.code == 200){
                    res.data.forEach(item=>{
                        item.goods_list.forEach(item2=>{
                            this.total += item2.total;
                        })
                    })
                    this.order = res.data;
                }else{
                    // this.$message.destroy();
                    this.$message.error(res.msg)
                    // return this.$router.go(-1)
                }
                
            })
        },
        // 创建订单
        create_order(){

            if(this.loading){
                return this.$message.error('请耐心等待，不要重复下单');
            }

            let coupon_id = [];
            this.order.forEach(item=>{
                coupon_id.push(item.coupon_id)
            })

            let params = {
                params:this.$route.params.params,
                address_id:this.address_id,
                coupon_id:coupon_id.join(','),
                remark:this.remark,
            }
            this.loading = true;
            this.$post(this.$api.homeOrder+'/create_order',params).then(res=>{
                if(res.code == 200){
                    let str = window.btoa(JSON.stringify(res.data)); 
                    this.$router.push("/order/order_pay/"+str);
                }else{
                    this.$message.error(res.msg);
                }
                this.loading = false;
                
            });
        }
    },
    created() {
        this.onload(); 
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
                    right: -10px;
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
</style>