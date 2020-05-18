<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            个人订单
            <div class="order_search">
                <el-input placeholder="请输入订单号" v-model="order_no" >
                    <el-button @click="get_user_order()" slot="append" icon="el-icon-search"></el-button>
                </el-input>
            </div>
            <div class="order_type">
                <ul>
                    <li @click="order_type='';order_no='';get_user_order()" :class="order_type==''?'red':''">全部 ({{order_num.all}})</li>
                    <li @click="order_type='NOPAY';order_no='';get_user_order()" :class="order_type=='NOPAY'?'red':''">待支付 ({{order_num.no_pay}})</li>
                    <li @click="order_type='WAITSEND';order_no='';get_user_order()" :class="order_type=='WAITSEND'?'red':''">待发货 ({{order_num.wait_send}})</li>
                    <li @click="order_type='WAITREC';order_no='';get_user_order()" :class="order_type=='WAITREC'?'red':''">待收货 ({{order_num.wait_rec}})</li>
                    <li @click="order_type='WAITCOMMENT';order_no='';get_user_order()" :class="order_type=='WAITCOMMENT'?'red':''">待评论 ({{order_num.wait_comment}})</li>
                    <li @click="order_type='REFUND';order_no='';get_user_order()" :class="order_type=='REFUND'?'red':''">售后处理 ({{order_num.refund}})</li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="user_index_order_list" v-if="order_list.length>0">
            <div class="user_index_store_list" v-for="(v,k) in order_list" :key="k">
                <div class="user_index_store_title">订单号：{{v.order_no}}<div class="order_time" :style="'color:'+v.cn_status_color">{{v.cn_status}}</div><div class="order_time">{{v.add_time|formatDate}}</div></div>
                <ul>
                    <li v-for="(vo,key) in v.order_goods" :key="key">
                        <router-link :to="'/goods/info/'+vo.goods_id">
                        <div class="user_order_goods_thumb"><img width="40px" height="40px" :src="vo.image" alt=""></div>
                        <div class="user_order_goods_title">{{vo.goods_name}}</div>                     
                        <div class="user_order_goods_price">￥{{vo.goods_price}}</div>
                        <div class="user_order_goods_num">{{vo.goods_spec||' - '}}</div>
                        <div class="user_order_goods_num">x{{vo.goods_num}}</div>
                        </router-link>
                        <div class="clear"></div>
                        
                    </li>
                </ul>
                <!-- 处理订单 -->
                <div class="handle_btn">
                    <el-button type="danger" v-if="v.cn_status=='等待支付'" @click="pay(k)">前往支付</el-button>
                    <el-button v-if="v.cn_status=='等待支付'" @click="close_order(v.order_no)">取消订单</el-button>
                    <el-button type="danger" v-if="v.cn_status=='等待收货'" @click="change_order_status(v.id)">确认收货</el-button>
                    <el-button v-if="v.cn_status=='等待收货'" @click="delivery_show(v.delivery_no)">查看物流</el-button>
                    <el-button type="danger" v-if="v.cn_status=='等待评论'" @click="$router.push('/user/comment/add/'+v.id)">前往评论</el-button>
                    <el-button type="warning" v-if="v.cn_status!='售后处理' && v.cn_status!='等待支付' && v.cn_status!='等待发货'  && v.order_status<1 && v.refund==0" @click="open_after_sale(v)">申请售后</el-button>
                    <el-button v-if="v.refund_step==4">售后结束</el-button>
                    <el-button type="warning" v-if="v.cn_status!='等待支付' && v.cn_status!='等待发货'  && v.order_status!=1 && v.refund>0" @click="open_after_sale(v)">查看售后</el-button>
                </div>
            </div>
        </div>
        <!-- 没有订单则 -->
        <div class="empty_order" v-else>
            <dl>
                <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                <dd>主人，您近期还没有购买任何商品哟~</dd>
                <dd class="btn"><router-link to="/">前往商城</router-link></dd>
            </dl>
        </div>

        <div class="home_fy_block" v-show="order_list.length>0">
            <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
        </div>

        <!-- 物流显示 -->
        <el-dialog title="物流列表" :visible.sync="deliveryShow">
            <!-- 快递信息 -->
            <div class="freight_info">
                <el-timeline v-if="delivery_list != undefined && delivery_list != null && delivery_list.result.list.length>0">
                    <el-timeline-item size='large' color="#0bbd87" :timestamp="delivery_list.result.list[0].time">{{delivery_list.result.list[0].status}}</el-timeline-item>
                    <el-timeline-item v-for="(vo,kess) in delivery_list.result.list" :key="kess" v-show="kess>=1" :timestamp="vo.time">{{vo.status}}</el-timeline-item>
                </el-timeline>
                <div class="no_freight" v-else>
                    没有任何快递信息
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="deliveryShow = false">关 闭</el-button>
            </span>
        </el-dialog>

        <!-- 售后处理 -->
        <el-dialog title="售后处理" :visible.sync="afterSale">
            
            <div class="after_sale">
                <el-form ref="after_sale" label-width="120px">
                    <el-form-item label="订单号">
                        {{afterSaleInfo.order_no}}
                    </el-form-item>
                    <el-form-item label="退款/换货">
                        <div class="red_radio" v-if="afterSaleInfo.refund_step == 0">
                            <el-radio-group v-model="afterParams.refund_status">
                                <el-radio  :label="1">退款</el-radio>
                                <el-radio  :label="2">换货</el-radio>
                            </el-radio-group>
                        </div>
                        <div v-else>{{afterSaleInfo.refund_status==1?'退款':'退货'}}</div>
                    </el-form-item>
                    <el-form-item label="退款/退货原因" >
                        <el-input v-if="afterSaleInfo.refund_step == 0" type="text" placeholder="请输入原因" v-model="afterParams.refund_info" maxlength="100" show-word-limit ></el-input>
                        <div v-else>{{afterSaleInfo.refund_info}}</div>
                    </el-form-item>
                    <el-form-item label="输入快递单号" v-if="afterSaleInfo.refund_step == 1">
                        <el-input type="text" placeholder="请输入快递单号" v-model="afterParams.refund_delivery_no" maxlength="100" show-word-limit ></el-input>
                    </el-form-item>
                    <el-form-item label="商家发货单号" v-if="afterSaleInfo.refund_step == 3">
                        {{afterSaleInfo.refund_no+'（商家已经发货，收到商品后请确定收货）'}}
                    </el-form-item>
                    <el-form-item label="商家发货单号" v-if="afterSaleInfo.refund_step == 4">
                        售后已经处理结束！
                    </el-form-item>
                </el-form>
                
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="afterSale = false">关 闭</el-button>
                <el-button type="danger"  v-if="afterSaleInfo.refund_step==0" @click="refund()">申 请</el-button>
                <el-button type="danger"  v-if="afterSaleInfo.refund_step==1" @click="refund_no()">提交</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          order_list:[],
          order_num:{},
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          order_no:'',
          order_type:'',
          deliveryShow:false,
          delivery_list:undefined,
          afterSale:false,
          afterSaleInfo:{
              id:0,
          },
          afterParams:{
              refund_status:1,
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_user_order:function(){
            // console.log(this.order_type);
            this.$post(this.$api.homeGetUserOrder,{page:this.current_page,order_no:this.order_no,order_type:this.order_type}).then(res=>{
                this.order_list = res.data.order_list.data;
                this.order_num = res.data.order_num;
                this.page_size = res.data.order_list.per_page;
                this.total_data = res.data.order_list.total;
                this.current_page = res.data.order_list.current_page;
            });
        },
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.search_goods();
        },
        pay:function(index){
            let order_info = this.order_list[index];
            // console.log(this.order_list[index]);
            let order_no = order_info.order_no;
            let strArr = [];
            let str = '';
            order_info.order_goods.forEach(res => {
                str = res.goods_id+'|'+order_info.store_id+'|'+res.goods_num;
                if(!this.$isEmpty(res['spec_id']) && res['spec_id']>0){
                    str += '|'+res['spec_id'];
                }
                strArr.push(str);
            });
            let str_info = strArr.join(',');
            return this.$router.push('/order/chose_pay/'+order_no+'/0/'+str_info)
        },
        // 取消订单
        close_order:function(order_no){
            this.$post(this.$api.homeCloseOrder,{order_no:order_no}).then(res=>{
                if(res.code == 200){
                    this.get_user_order();
                    return this.$message.success('取消订单成功');
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        delivery_show:function(no){
            this.$get(this.$api.homeGetDeliveryList,{delivery_no:no}).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }else{
                    this.delivery_list = res.data;
                    this.deliveryShow = true;
                }
            })
        },
        // 修改订单状态
        change_order_status:function(id){
            this.$post(this.$api.homeChangeOrderStatus,{order_id:id}).then(()=>{
                this.get_user_order();
                this.$message.success('确认收货');
            });
        },
        // 打开售后处理
        open_after_sale:function(v){
            this.afterSale = true;
            this.afterSaleInfo = v;
        },
        refund:function(){
            this.afterParams.id = this.afterSaleInfo.id;
            this.$post(this.$api.homeOrderRefund,this.afterParams).then(()=>{
                this.$message.success('申请成功');
                this.afterSale = false;
                this.get_user_order();
            });
        },
        refund_no:function(){
            this.afterParams.id = this.afterSaleInfo.id;
            this.$post(this.$api.homeOrderRefundDelivery,this.afterParams).then(()=>{
                this.$message.success('提交成功');
                this.afterSale = false;
                this.get_user_order();
            });
        }
    },
    created() {
        if(!this.$isEmpty(this.$route.params.order_type)){
            this.order_type = this.$route.params.order_type;
        }
        this.get_user_order();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.no_freight{
    line-height: 60px;
    text-align: center;
    font-size: 14px;
    color:#999;
}
.handle_btn{
    text-align: right;
    margin: 20px 0;
}
.user_index_order_list{
    margin-bottom: 50px;
}
.order_time{
    margin-left: 20px;
    color:#666;
    float: right;
    font-size: 12px;
}
.user_index_store_list{
    ul{
        border:1px solid #efefef;
        
    }
    ul li{
        border-bottom: 1px solid #efefef;
        padding:20px 15px;
    }
    ul li:last-child{
        border-bottom: none;
    }
}
.user_order_goods_thumb{
    float: left;
    margin-right: 15px;
    display: block;
    width: 40px;
    
}

.user_order_goods_title{
    font-size: 12px;
    color:#666;
    width: 280px;
    float: left;
}
.user_order_goods_num{
    font-size: 12px;
    color:#666;
    float: left;
    display: block;
    line-height: 40px;
    width: 200px;
    text-align: center;
}
.user_order_goods_price{
    font-size: 12px;
    line-height: 40px;
    color:#ca151e;
    width: 90px;
    float: left;
}
.order_search{
    float: right;
    margin-left: 20px;
    position: absolute;
    right: 10px;
    top: -5px;
    width: 180px;
}
.user_index_store_title{
    background: #f6f6f6;
    line-height: 40px;
    color:#666;
    padding:0 15px;
    margin:20px 0;
    a:hover{
        color:#ca151e;
    }
}
.order_type{
    float: right;
    font-size: 14px;
    margin-top: 2px;
    ul{
        margin-right: 280px;
        
    }
    ul li {
        color:#999;
        float: left;
        margin-right: 20px;
    }
    ul li.red{
        color:#ca151e;
    }
    ul li:hover{
        color: #ca151e;
    }
}

</style>