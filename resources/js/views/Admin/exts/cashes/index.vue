<template>
    <div class="qwit">
        <table-view :options="options" :btnConfig="btnConfigs" :dialogParam="dialogParam" ></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'真实姓名',value:'name'},
            {label:'提现银行',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'提现金额',value:'money',type:"tags"},
            {label:'提现状态',value:'cash_status',type:'dict_tags'},
            {label:'备注',value:'is_type'},
            {label:'拒绝原因',value:'refuse_info'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 表单配置 
        const addColumn = [
            {label:'名称',value:'name'},
            {label:'资金',value:'money'},
        ]
        
        const btnConfigs = reactive({
            store:{show:false},
        })

        const dialogParam = reactive({
            dictData:{
                cash_status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1},{label:proxy.$t('btn.rejected'),value:2}],
            },
            view:{column:addColumn},
        })
        return {options,btnConfigs,dialogParam}
    }
}
</script>

<style>

</style>