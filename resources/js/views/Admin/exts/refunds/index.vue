<template>
    <table-view pageUrl="/Admin/orders" :params="params" :btnConfig="btnConfigs" :options="options" :searchOption="searchOptions" :dialogParam="dialogParam"></table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'订单图片',value:'order_image',type:'avatar'},
            {label:'订单名称',value:'order_name'},
            {label:'店铺名称',value:'store_name'},
            {label:'买家昵称',value:'buyer_name'},
            {label:'订单总额',value:'total_price'},
            {label:'订单状态',value:'order_status_cn',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'订单号',value:'order_no',where:'likeRight'},
            {label:'订单名称',value:'order_name',where:'likeRight'},
            {label:'时间',value:'created_at',type:'daterange'},
        ])

        const params = {
            isWith:'store,user,refund',
            order_status:5,
            refund_status:0,

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
            {label:'快递订单号',value:'delivery_no'},
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
            {label:'快递订单号',value:'delivery_no'},
        ]
        const dialogParam = reactive({
            view:{column:viewColumn},
            edit:{column:addColumn},
        })
        return {options,searchOptions,dialogParam,btnConfigs,params}
    }
}
</script>

<style>

</style>