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
            <div class="title">积分商品信息</div>
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

                <div class="store_list" >
                    <div class="store_title">
                        <div class="og_list">
                            <ul>
                                <li >
                                    <a-row>
                                        <a-col :span="10">
                                            <dl>
                                                <dt><img :src="order.goods_master_image" :alt="order.goods_name"></dt>
                                                <dd :title="order.goods_name">{{order.goods_name}}</dd>
                                            </dl>
                                        </a-col>
                                        <a-col :span="4"><div class="goods_info_th"> - </div></a-col>
                                        <a-col :span="4"><div class="goods_info_th">{{order.goods_price}}积分</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">{{buy_num}}</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th">-</div></a-col>
                                        <a-col :span="2"><div class="goods_info_th red">￥{{order.goods_price*buy_num}}</div></a-col>
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
                <div class="total">总积分：<span>{{buy_num*order.goods_price}}</span>( 包邮 )</div>
                <div :class="loading?'btn hide':'btn'" @click="create_order">{{loading?'加载中..':'支付积分'}}</div>
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
          buy_num:1,
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
                    this.$message.error(res.msg)
                }
            })
        },
        // 订单建立前预览商品信息
        create_order_before(){
            this.$get(this.$api.homeIntegral+'/goods/'+this.$route.params.id).then(res=>{
                if(res.code == 200){
                    this.order = res.data;
                }else{
                    this.$message.error(res.msg)
                    return this.$router.go(-1)
                }
                
            })
        },
        // 创建订单
        create_order(){
            if(this.loading){
                return this.$message.error('请耐心等待，不要重复下单');
            }
            let params = {
                id:this.$route.params.id,
                buy_num:this.$route.params.num,
                address_id:this.address_id,
                remark:this.remark,
            }
            this.loading = true;
            this.$post(this.$api.homeIntegral+'/pay',params).then(res=>{
                this.loading = false;
                if(res.code == 200){
                    this.$router.push("/integral/index");
                }else{
                    return this.$message.error(res.msg)
                }
                
            });
        }
    },
    created() {
        this.buy_num = this.$route.params.num
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