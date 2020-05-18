<template>
    <div class="create_order">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>

        <div class="join_over width_center_1200">
            <div class="join_bzt">
                <el-steps :active="1" finish-status="process" simple >
                    <el-step title="我的购物车" icon="el-icon-shopping-cart-full"></el-step>
                    <el-step title="物流地址" icon="el-icon-map-location"></el-step>
                    <el-step title="选择支付" icon="el-icon-mouse"></el-step>
                    <el-step title="支付成功" icon="el-icon-check"></el-step>
                </el-steps>
            </div>
        </div>

        <div class="address_chose width_center_1200">
            <div class="address_chose_title">
                选择送货地址
            </div>
            <div class="address_list" v-if="address_list.length>0">
                <ul>
                    <li :class="v.is_default==1?'red':''" v-for="(v,k) in address_list" :key="k" @click="set_default(v.id)">
                        <div class="receive_name">{{v.receive_name}} <span>({{v.receive_tel}})</span></div>
                        <div class="area_info">{{v.area_info+' '+v.address}}</div>
                        <div :class="v.is_default==1?'is_default_red':'is_default'"><i class="icon iconfont">&#xe611;</i></div>
                    </li>
                </ul>
            </div>
            <div class="empty_address" v-else>
                <router-link to="/user/address">没有设置收货地址，请先前往<span>设置</span></router-link>
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

            
            <div class="remark">
                <div class="remark_title">备注</div>
                <el-input style="margin-top:20px;width:300px;" v-model="remark" show-word-limit type="textarea" maxlength="200"></el-input>
            </div>


            <div class="foot_create_order">
                <div class="total_price">
                    <span>结算：</span>
                    ￥ {{total_price}}
                    <!-- <font>( {{total_freight>0?'运费:'+total_freight:'免运费'}} )</font> -->
                    <font>( 不包含运费 )</font>
                </div>
                <div class="money_pay">
                    <el-checkbox @change="money_page_change" :disabled="user_info.money<=0" v-model="money_pay">余额结算: {{user_info.money||0}} 元</el-checkbox>
                </div>
                <div class="clear"></div>
                <div class="foot_create_order_btn"><el-button type="danger" @click="create_order">前往支付</el-button></div>
            </div>

            <el-dialog :visible.sync="pwdVisible" width="20%" :show-close="false" title="输入六位支付密码">
                <el-input placeholder="请输入密码" v-model="pay_password" show-password></el-input>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="pwdVisible = false;money_pay=false;">取 消</el-button>
                    <el-button type="danger" @click="submitPayPwd()">确 定</el-button>
                </div>
            </el-dialog>
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
          address_list:[],
          befor_order_list:[],
          user_info:{},
          total_price:0,
          total_freight:0,
          remark:'', // 备注
          money_pay:false, // 余额支付
          pwdVisible:false, // 支付密码输入
          pay_password:'',
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_address_list:function(){
            this.$get(this.$api.getAddressList).then(res=>{
                this.address_list = res.data;

                // 如果没有设置默认地址则设置一个
                if(this.address_list.length>0){
                    let a = 0;
                    this.address_list.forEach(addressRes=>{
                        if(addressRes.is_default == 1){
                            a += 1;
                        }
                    });
                    if(a==0){
                        this.set_default(this.address_list[0].id);
                    }
                }
                
            });
        },
        set_default:function(id){
            this.$post(this.$api.setDefaultAddress,{id:id}).then(()=>{
                this.get_address_list();
                return this.$message.success('修改默认地址成功');
            });
        },
        create_order:function(){

            // 判断是否有收货地址
            if(this.address_list<=0){
                return this.$message.error('请先设置收货地址！');
            }

            //  如果没有设置默认则设置一个默认地址
            if(this.address_list.length>0){
                let a = 0;
                this.address_list.forEach(addressRes=>{
                    if(addressRes.is_default == 1){
                        a += 1;
                    }
                });
                if(a==0){
                    this.set_default(this.address_list[0].id);
                }
            }

            this.$post(this.$api.homeCreateOrder,{type:this.$route.params.type,info:this.$route.params.info,remark:this.remark,is_money_pay:this.money_pay}).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }else{
                    if(res.data.is_balance){  // 余额支付成功 直接跳转
                        this.$message.success('购买成功');
                        return this.$router.push('/order/pay_success');
                    }else{
                        this.$message.success('订单创建成功，等待支付');
                        return this.$router.push('/order/chose_pay/'+res.data.order_no+'/'+this.$route.params.type+'/'+this.$route.params.info)
                    }
                    
                }
            });
        },
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
        count_freight:function(){

        },
        // 获取用户信息
        get_user_info:function(){
            this.$get(this.$api.homeEditUserInfo).then(res=>{
                this.user_info = res.data;
            });
        },
        money_page_change:function(e){
            if(e){
                this.pwdVisible = true;
            }
        },
        submitPayPwd:function(){
            this.$post(this.$api.homeCheckPayPassword,{pay_password:this.pay_password}).then(res=>{
                if(res.code == 200){
                    this.money_pay = true;
                    this.pwdVisible = false;
                }else{
                    this.money_pay = false;
                    return this.$message.error(res.msg);
                }
            });
        }
    },
    created() {
        this.get_address_list();
        this.get_order_list();
        this.get_user_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.remark{
    margin-top:40px;
    color:#666;
    .remark_title{
        background: #f2f2f2;
        line-height: 40px;
        padding-left: 20px;
    }
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
    .money_pay{
        line-height: 28px;
        float: right;
        color:#666;
        font-size: 12px;
        margin-top: 20px;
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
    .address_chose_title{
        font-size: 16px;
        clear: both;
        font-weight: bold;
        padding-bottom: 20px;
    }
    .address_list{
        ul:after{
            content:'';
            display: block;
            clear:both;
        }
        margin-bottom:30px; 
        ul li{
            float: left;
            box-sizing: border-box;
            width: 292px;
            height: 105px;
            border-radius: 3px;
            border:2px solid #efefef;
            margin-right: 10px;
            margin-bottom: 10px;
            padding:20px;
            position: relative;
            .receive_name{
                margin-bottom: 10px;
                font-weight: bold;
                line-height: 18px;
                span{
                    font-weight: normal;
                    color:#666;
                    font-size: 12px;
                }
            }
            .area_info{
                color:#666;
            }
            .is_default{
                i{
                    font-size: 20px;
                }
                position: absolute;
                right: -10px;
                bottom: -6px;
            }
            .is_default_red{
                i{
                    font-size: 20px;
                }
                position: absolute;
                right: -10px;
                bottom: -6px;
                color:#ca151e;
            }
        }
        ul li.red{
            border-color: #ca151e;
        }
        ul li:nth-child(4n){
            margin-right: 0;
        }
    }
}
</style>