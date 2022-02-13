<template>
    <table-view :params="params" :btnConfig="btnConfigs" :options="options" :dialogParam="dialogParam">
        <!-- 物流信息 -->
        <template #table_show_bottom_hook="{formData}">
            <div class="delivery_list" v-if="!R.isEmpty(formData.view.delivery_no) && !R.isEmpty(formData.view.delivery_code)">
                <el-collapse accordion @change="(activeName)=>{collapseChange(formData,activeName)}">
                    <el-collapse-item name="1">
                        <template #title>
                            <el-icon><List /></el-icon>
                            <span style="margin-left:10px">物流信息</span>
                        </template>
                        <el-timeline v-if="data.express.length && data.express.length>0">
                            <el-timeline-item v-for="(v, k) in data.express" :key="k" :timestamp="v.time" :color="k==0?'#0bbd87':null">
                                {{ v.context }}
                            </el-timeline-item>
                        </el-timeline>
                        <el-empty v-else />
                    </el-collapse-item>
                </el-collapse>
            </div>
        </template>
    </table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
import { List  } from '@element-plus/icons'
export default {
    components:{tableView,List},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const data = reactive({
            express:[]
        })
        const options = reactive([
            {label:'订单图片',value:'order_image',type:'avatar'},
            {label:'订单名称',value:'order_name'},
            {label:'店铺名称',value:'store_name'},
            {label:'买家昵称',value:'buyer_name'},
            {label:'订单总额',value:'total_price'},
            {label:'订单状态',value:'order_status_cn',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        const params = {
            isWith:'store,user,refund'
        }

        const btnConfigs = reactive({
            store:{show:false},
        })


        // 表单配置 
        const viewColumn = [
            {label:'订单图片',value:'order_image',type:'avatar',span:24},
            {label:'订单名称',value:'order_name'},
            {label:'店铺名称',value:'store_name'},
            {label:'买家昵称',value:'buyer_name'},
            {label:'订单总额',value:'total_price'},
            {label:'商品总额',value:'order_price'},
            {label:'优惠价格',value:'coupon_money'},
            {label:'收件人名',value:'receive_name'},
            {label:'收件人手机',value:'receive_tel'},
            {label:'地址信息',value:'receive_area'},
            {label:'详细地址',value:'receive_address'},
            {label:'快递单号',value:'delivery_no'},
            {label:'订单状态',value:'order_status_cn'},
        ]
        const addColumn = [
            {label:'订单名称',value:'order_name'},
            {label:'店铺名称',value:'store_name'},
            {label:'买家昵称',value:'buyer_name'},
            {label:'订单总额',value:'total_price'},
            {label:'商品总额',value:'order_price'},
            {label:'优惠价格',value:'coupon_money'},
            {label:'收件人名',value:'receive_name'},
            {label:'收件人手机',value:'receive_tel'},
            {label:'地址信息',value:'receive_area'},
            {label:'详细地址',value:'receive_address'},
            {label:'快递单号',value:'delivery_no'},
        ]
        const dialogParam = reactive({
            view:{column:viewColumn},
            edit:{column:addColumn},
        })

        // 物流查询
        const collapseChange = (formData,activeName)=>{
            data.express = []
            if(proxy.R.isEmpty(activeName)) return
            const orderId = formData.view.id
            proxy.R.post('/Admin/orders/express/find',{id:orderId}).then(res=>{
                if(!res.code) data.express = res
            })
        }

        return {options,dialogParam,btnConfigs,params,data,collapseChange}
    }
}
</script>

<style>

</style>