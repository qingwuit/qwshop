<template>
    <div class="user_main">
        <div class="block_title">
            订单详情
        </div>
        <div class="x20"></div>

        
        <div class="admin_form">
            <div class="order_info_list">
                <el-row>
                    <el-col :span="12">
                        订单号：<span class="content">{{data.info.order_no||'-'}}</span>
                    </el-col>
                    <el-col :span="12">
                        状态：
                        <span class="content">
                            <el-tag type="danger" v-show="data.info.order_status==0">{{data.info.order_status_cn}}</el-tag>
                            <el-tag type="warning" v-show="data.info.order_status==1">{{data.info.order_status_cn}}</el-tag>
                            <el-tag v-show="data.info.order_status>1 && data.info.order_status<6">{{data.info.order_status_cn}}</el-tag>
                            <el-tag type="info" v-show="data.info.order_status==6">{{data.info.order_status_cn}}</el-tag>
                            <el-tag type="success" v-show="data.info.order_status>=7">{{data.info.order_status_cn}}</el-tag>
                        </span>
                    </el-col>
                </el-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">订单支付</span></div>
            <div class="x20"></div>
            
            <div class="order_info_list">
                <el-row>
                    <el-col :span="8">
                        支付方式：<span class="content">{{data.info.payment_name_cn||'-'}}</span>
                    </el-col>
                    <el-col :span="8">
                        支付时间：<span class="content">{{data.info.pay_time||'-'}}</span>
                    </el-col>
                    <el-col :span="8">
                        快递单号：<span class="content">{{data.info.delivery_no||'-'}}</span>
                    </el-col>
                </el-row>
            </div>
            <div class="order_info_list">
                <el-row>
                    <el-col :span="24">
                        备注：<span class="content">{{data.info.remark||'-'}}</span>
                    </el-col>
                </el-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">物流地址</span></div>
            <div class="x20"></div>

            <div class="order_info_list">
                <el-row>
                    <el-col :span="8">
                        收货人：<span class="content">{{data.info.receive_name}}</span>
                    </el-col>
                    <el-col :span="8">
                        联系电话：<span class="content">{{data.info.receive_tel}}</span>
                    </el-col>
                    <el-col :span="8">
                        取货地址：<span class="content">{{data.info.receive_area+data.info.receive_address}}</span>
                    </el-col>
                </el-row>
            </div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">商品信息</span></div>
            <div class="x20"></div>

            <div class="admin_table_list">
                <el-table :data="data.info.order_goods"  >
                    <el-table-column v-for="(v,k) in data.columns" :key="k" :prop="v.value" :label="v.label" >
                        <template #default="scope">
                            <el-image v-if="v.type=='avatar'" :style="{width:'50px',height:'50px',borderRadius:'4px',textAlign:'center',lineHeight:'65px',background:R.isEmpty(scope.row[v.value])?'#f2f2f2':'null',display:'block'}" :fit="'fill'" :hide-on-click-modal="true" :src="scope.row[v.value]" lazy :preview-src-list="[scope.row[v.value]]" :preview-teleported="true">
                                <template #error>
                                    <el-icon :color="'#888'" :size="26"><Picture /></el-icon>
                                </template>
                            </el-image>
                        </template>
                    </el-table-column>
                </el-table>
            </div>


            <div class="order_info_right_price">总计：￥ {{data.info.total_price}}<span data-v-01d38243="">（运费：{{data.info.freight_money}} | 优惠：{{data.info.coupon_money||0}}）</span></div>

            <div style="margin-top:40px"><span style="font-size: 14px;font-weight: bold;">快递信息</span></div>
            <div class="x20"></div>

            <div class="order_info_kd">
                <el-timeline v-if="data.info.delivery_list && data.info.delivery_list.length>0">
                    <el-timeline-item  v-for="(v,k) in data.info.delivery_list" :key="k" :timestamp="v.time" :color="k==0?'red':'gray'">
                    {{v.context}}
                    </el-timeline-item>
                </el-timeline>
                <el-empty v-else />
            </div>
            <br>
        </div>
        
        
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import {useRoute} from "vue-router"
export default {
    components: {},
    setup() {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const data = reactive({
            id:0,
            info:{
                order_status:0
            },
            columns:[
                {label:'图片',value:'goods_image',type:'avatar'},
                {label:'商品名称',value:'goods_name'},
                {label:'规格属性',value:'sku_name'},
                {label:'价格',value:'goods_price'},
            ],
            list:[],
        })

        const loadData = ()=>{
            if(!proxy.R.isEmpty(route.params.id)) data.id = route.params.id
            proxy.R.get('/user/order/'+data.id+'?isResource=Home').then(res=>{
                if(!res.code) data.info = res
            }).catch(error=>{
                console.log(error)
            })
        }

        onMounted(() => {
            loadData()
        })

        return {
            data,
        }
    },

};
</script>
<style lang="scss" scoped>
.order_info_list{
    margin-bottom: 20px;
}
.admin_table_list{
    margin-bottom: 20px;
}
</style>