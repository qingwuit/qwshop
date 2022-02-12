<template>
    <table-view :handleWidth="'80px'" :options="options" :dialogParam="dialogParam" :btnConfig="btnConfigs"></table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const options = reactive([
            {label:'结算单号',value:'settlement_no'},
            {label:'总金额',value:'total_price',type:'tags'},
            {label:'结算金额',value:'settlement_price',type:'tags'},
            {label:'结算状态',value:'status',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);


        // 表单配置 
        const viewColumn = [
            {label:'结算单号',value:'settlement_no'},
            {label:'总金额',value:'total_price'},
            {label:'结算金额',value:'settlement_price'},
            {label:'结算状态',value:'status'},
            {label:'备注',value:'info'},
        ]
        const dialogParam = reactive({
            view:{column:viewColumn},
        })

        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            destroy:{show:false},
        })


        return {
            options,dialogParam,btnConfigs,
        }
    }
}
</script>

<style>

</style>