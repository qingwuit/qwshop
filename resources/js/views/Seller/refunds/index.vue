<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            售后处理
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <div class="order_info_list">
                <a-row>
                    <a-col :span="8">
                        订单号：<span class="content">{{info.order_no}}</span>
                    </a-col>
                    <a-col :span="8">
                        状态：
                        <span class="content">
                            <a-tag color="red" v-if="refund_info.refund_verify==0">等待审核</a-tag>
                            <a-tag color="red" v-if="refund_info.refund_verify==2">拒绝申请</a-tag>
                            <a-tag color="orange" v-if="refund_info.refund_step==0 && refund_info.refund_verify==1">等待用户发货</a-tag>
                            <a-tag color="orange" v-if="refund_info.refund_step==1">等待确认发货</a-tag>
                            <a-tag color="blue" v-if="refund_info.refund_step==2">等待用户确认</a-tag>
                            <a-tag color="green" v-if="refund_info.refund_step==3">售后处理完成</a-tag>
                        </span>
                    </a-col>
                    <a-col :span="8">
                        操作：
                        <span class="content">
                            <a-button v-if="refund_info.refund_verify==0" @click="edit_verify(true)" type="primary">同意</a-button>
                            <a-button v-if="refund_info.refund_verify==0" @click="edit_verify(false)" type="danger">拒绝</a-button>
                            <a-button v-if="refund_info.refund_step==1" @click="edit_express()"><a-icon type="edit" />编辑物流</a-button>
                        </span>
                    </a-col>
                </a-row>
            </div>
            
            <div class="order_info_list">
                <a-row>
                    <a-col :span="8">
                        支付方式：<span class="content">{{info.payment_name_cn||'-'}}</span>
                    </a-col>
                    <a-col :span="8">
                        支付时间：<span class="content">{{info.pay_time||'-'}}</span>
                    </a-col>
                    <a-col :span="8">
                        -
                    </a-col>
                </a-row>
            </div>
            <div class="order_info_list">
                <a-row>
                    <a-col :span="24">
                        退款/退货原因：<span class="content">{{refund_info.refund_remark||'-'}}</span>
                    </a-col>
                </a-row>
            </div>
            <div class="order_info_list">
                <a-row>
                    <a-col :span="24">
                        拒绝原因：<span class="content">{{refund_info.refuse_remark||'-'}}</span>
                    </a-col>
                </a-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">物流地址</span></div>
            <div class="unline underm"></div>

            <div class="order_info_list">
                <a-row>
                    <a-col :span="8">
                        用户：<span class="content">{{info.receive_name}}</span>
                    </a-col>
                    <a-col :span="8">
                        联系电话：<span class="content">{{info.receive_tel}}</span>
                    </a-col>
                    <a-col :span="8">
                        取货地址：<span class="content">{{info.receive_area+info.receive_address}}</span>
                    </a-col>
                </a-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">快递单号</span></div>
            <div class="unline underm"></div>

            <div class="order_info_list">
                <a-row>
                    <a-col :span="8">
                        用户快递：<span class="content">{{refund_info.delivery_no||'-'}}</span>
                    </a-col>
                    <a-col :span="8">
                        商家快递：<span class="content">{{refund_info.re_delivery_no||'-'}}</span>
                    </a-col>
                    <a-col :span="8">
                        -
                    </a-col>
                </a-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">商品信息</span></div>
            <div class="unline underm"></div>

            <div class="admin_table_list">
                <a-table :columns="columns" :data-source="info.order_goods" :pagination="false"  >
                    <span slot="name" slot-scope="rows">
                        <div class="admin_pic_txt">
                            <div class="img"><img v-if="rows.goods_image" :src="rows.goods_image"><a-icon v-else type="picture" /></div>
                            <div class="text">{{rows.goods_name}}</div>
                            <div class="clear"></div>
                        </div>
                    </span>
                    <span slot="buy_num" slot-scope="rows">
                        x {{rows.buy_num}}
                    </span>
                    <span slot="goods_price" slot-scope="rows">
                        <font color="#ca151e">￥{{rows.goods_price}}</font>
                    </span>
                </a-table>
            </div>


            <div class="order_info_right_price">总计：￥ {{info.total_price}}<span data-v-01d38243="">（运费：{{info.freight_money}}）</span></div>

            
            <br>
            <!-- <a-button type="primary" @click="handleSubmit">提交</a-button> -->
        </div>

        <!-- 修改物流 -->
        <a-modal v-model="visible" title="编辑物流" ok-text="确认" cancel-text="取消" @ok="handleSubmit">
            <a-input-group compact>
                <a-select style="width: 25%" :value="refund_info.re_delivery_code||'yd'">
                    <a-select-option :value="v.code" v-for="(v,k) in express" :key="k">{{v.name}}</a-select-option>
                </a-select>
                <a-input style="width: 75%" placeholder="输入快递单号发货" v-model="refund_info.re_delivery_no" />
            </a-input-group>
        </a-modal>

        <!-- 修改物流 -->
        <a-modal v-model="visible2" title="拒绝申请" ok-text="确认" cancel-text="取消" @ok="handleSubmit2">
            <a-input style="width: 75%" placeholder="拒绝原因" v-model="refund_info.refuse_remark" />
        </a-modal>
    </div>
</template>

<script>
import wangEditor from "@/components/wangeditor"
export default {
    components: {wangEditor},
    props: {},
    data() {
      return {
          info:{
          },
          refund_info:{},
          express:[],
          id:0,
          columns:[
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'商品名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'规格属性',dataIndex:'sku_name'},
              {title:'购买数量',key:'id',scopedSlots: { customRender: 'buy_num'}},
              {title:'商品价格',key:'id',scopedSlots: { customRender: 'goods_price'} },
          ],
          visible:false,
          visible2:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            
            // 验证代码处
            // if(this.$isEmpty(this.info.name)){
            //     return this.$message.error('分类名不能为空');
            // }

            
            this.$put(this.$api.sellerRefunds+'/'+this.id,{re_delivery_no:this.refund_info.re_delivery_no}).then(res=>{
                this.visible = false;
                this.$returnInfo(res);
                this.onload();
            })
           
   
            
        },
        handleSubmit2(){
            this.$put(this.$api.sellerRefunds+'/'+this.id,{refund_verify:2,refuse_remark:this.refund_info.refuse_remark}).then(res=>{
                this.visible2 = false;
                this.$returnInfo(res);
                this.onload();
            })
        },
        edit_express(e){
            this.visible = true;
            this.get_express();
        },
       
        get_info(){
            this.$get(this.$api.sellerOrders+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        get_refund(){
            this.$get(this.$api.sellerRefunds+'/'+this.id).then(res=>{
                this.refund_info = res.data;
            })
        },
        get_express(){
            this.$get(this.$api.sellerExpresses).then(res=>{
                this.express = res.data;
            })
        },
        edit_verify(e){
            var _this = this;
            let params = {
                refund_verify:e?1:2,
            }

            if(e){
                this.$confirm({
                    title: '是否同意退款/退货？',
                    content: '执行后将会退还金额',
                    okText: '确认',
                    okType: 'danger',
                    cancelText: '取消',
                    onOk() {
                        
                        _this.$put(_this.$api.sellerRefunds+'/'+_this.id,{refund_verify:1}).then(res=>{
                            _this.$returnInfo(res);
                            _this.onload();
                        })
                    },
                    onCancel() {
                    },
                });
            }else{
                this.visible2 = true;
            }
            
        },

        // 获列表
        onload(){
            // 判断你是否是编辑
            this.id = this.$route.params.id;
            this.get_info();
            this.get_refund();
        },

        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>