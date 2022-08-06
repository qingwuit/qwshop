<template>
    <div class="qwit">
        <table-view :options="options" :handleWidth="'80px'" :params="params" :searchOption="searchOptions" :btnConfig="btnConfigs" :dialogParam="dialogParam" ></table-view>
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
            {label:'提现资金',value:'money',type:'tags'},
            {label:'手续费',value:'commission',type:"tags"},
            {label:'银行名称',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'提现状态',value:'cash_status',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'真实姓名',value:'name',where:'likeRight'},
            {label:'提现银行',value:'bank_name',where:'likeRight'},
            {label:'银行卡号',value:'card_no',where:'likeRight'},
            {label:'提现金额',value:'money'},
            {label:'提现状态',value:'cash_status',type:'select'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'真实姓名',value:'name'},
            {label:'银行名称',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'提现资金',value:'money'},
        ]

        let viewColumn = _.cloneDeep(addColumn)
        viewColumn.push({label:'手续费',value:'commission'})
        
        const btnConfigs = reactive({
            update:{show:false},
        })

        const dialogParam = reactive({
            dictData:{
                cash_status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1},{label:proxy.$t('btn.rejected'),value:2}],
            },
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                bank_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                card_no:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                money:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            view:{column:viewColumn},
            add:{column:addColumn},
        })

        const params = reactive({
            store_id:'0|gt'
        })
        return {options,btnConfigs,searchOptions,dialogParam,params}
    }
}
</script>

<style>

</style>