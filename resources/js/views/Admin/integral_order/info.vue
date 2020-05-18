<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>订单详情</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <div class="order_info">
                    <div class="order_info_block">
                        <el-row>
                            <el-col :span="8"><div>订单号：<font color="#999">{{info.order_no}}</font></div></el-col>
                            <el-col :span="8"><div>状态：
                                <el-tag type="success" v-if="info.cn_status=='订单完成'">{{info.cn_status}}</el-tag>
                                <el-tag type="warning" v-else-if="info.cn_status=='售后处理'">{{info.cn_status}}</el-tag>
                                <el-tag type="danger" v-else-if="info.cn_status=='取消订单'">{{info.cn_status}}</el-tag>
                                <el-tag type="info" v-else>{{info.cn_status}}</el-tag>
                            
                            </div></el-col>
                            <el-col :span="8"><div>处理： 
                                <el-button type="primary" v-if="info.cn_status == '等待发货'" @click="deliveryInto = true">点击发货</el-button>
                                <el-button type="primary" v-else-if="info.cn_status == '售后处理'">处理售后</el-button>
                                <font color="#999" v-else>{{info.cn_status}}</font>
                            </div></el-col>
                        </el-row>
                    </div>
                    <div class="order_info_block">
                        <el-row>
                            <el-col :span="8"><div>用户：<font color="#999">{{info.receive_name}}</font></div></el-col>
                            <el-col :span="8"><div>联系电话：<font color="#999">{{info.receive_tel}}</font></div></el-col>
                            <el-col :span="8"><div>取货地址：<font color="#999">{{info.province+' '+info.city+' '+info.region+' '+info.address}} </font></div></el-col>
                        </el-row>
                    </div>
                    <div class="order_info_block">
                        <el-row>
                            <el-col :span="8"><div>备注：<font color="#999">{{info.remark}} </font></div></el-col>
                            <el-col :span="8"><div></div></el-col>
                            <el-col :span="8"><div> </div></el-col>
                        </el-row>
                    </div>
                </div>

                <!-- <div class="unline"></div> -->

                <div class="order_goods_list">
                    <el-table :data="info.integral_order_goods" >
                        <!-- <el-table-column prop="id" label="#"  width="70px"></el-table-column> -->
                        <el-table-column label="商品名称">
                            <template slot-scope="scope">
                                <dl class="table_dl">
                                    <dt><el-image style="width: 50px; height: 50px" :src="scope.row.image"><div slot="error" class="image-slot"><i class="el-icon-picture-outline"></i></div></el-image></dt>
                                    <dd class="table_dl_dd_all">{{ scope.row.goods_name }}</dd>
                                </dl>
                            </template>
                        </el-table-column>
                        <el-table-column prop="goods_spec" label="规格">
                            <template slot-scope="scope">
                                <div>{{scope.row.goods_spec||' - '}}</div>
                            </template> 
                        </el-table-column>
                        <el-table-column prop="goods_price" label="积分"></el-table-column>
                        <el-table-column prop="goods_num" label="购买数量">
                            <template slot-scope="scope">
                                <div>{{'x'+scope.row.goods_num}}</div>
                            </template> 
                        </el-table-column>
                    </el-table>
                    <div class="order_info_right_price">
                        总计： {{info.total_price}}<span>积分</span>
                    </div>
                </div>
            </div>

            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>快递信息</div>
                </div>

                <div class="admin_main_block_right">
                    <!-- <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div> -->
                </div>
            </div>

            <!-- 快递信息 -->
            <div class="freight_info">
                <el-timeline v-if="info.delivery_list != undefined && info.delivery_list != null && info.delivery_list.result.list.length>0">
                    <el-timeline-item v-for="(v,k) in info.delivery_list.result.list" :key="k" v-show="k<1" size='large' color="#0bbd87" timestamp="2019-10-10">{{v.status}}</el-timeline-item>
                    <el-timeline-item v-for="(v,k) in info.delivery_list.result.list" :key="k" v-show="k>=1" :timestamp="v.time">{{v.status}}</el-timeline-item>
                </el-timeline>
                <div class="no_freight" v-else>
                    没有任何快递信息
                </div>
            </div>


        </div>

        <!-- 发货 -->
        <el-dialog title="填写快递单号" :visible.sync="deliveryInto">
            <span>
                <el-input type="text" v-model="delivery_no"><template slot="prepend">快递单号</template></el-input>
            </span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="deliveryInto = false">取 消</el-button>
                <el-button type="primary" @click="send_delivery()">确 定</el-button>
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
          info:{
              province:'',
              city:'',
              region:'',
              address:'',
          },
          deliveryInto:false, // 打开填写快递单号
          delivery_no:'', // 快递单号
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_order_info:function(){
            this.$post(this.$api.adminGetIntegralOrderInfo,{id:this.$route.params.id}).then(res=>{
                this.info = res.data;
            });
        },
        // 发货
        send_delivery:function(){
            this.$post(this.$api.adminIntegralOrderSendDelivery,{order_id:this.$route.params.id,delivery_no:this.delivery_no}).then(res=>{
                if(res.code == 200){
                    this.deliveryInto = false;
                    this.get_order_info();
                    return this.$message.success('发货成功');
                }
            });
        },
    },
    created() {
        this.get_order_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.order_info{
    font-size: 14px;
}
.order_info_block{
    padding-bottom: 15px;
    margin-bottom: 15px;
    // border-bottom: 1px solid #efefef;
}
.order_info_right_price{
    text-align: right;
    color:#ca151e;
    padding:20px 20px 40px 20px;
    span{
        color:#999;
        font-size: 12px;
        margin-left: 20px;
    }
}
.no_freight{
    line-height: 60px;
    text-align: center;
    font-size: 14px;
    color:#999;
}
</style>