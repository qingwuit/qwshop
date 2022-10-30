<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :btnConfig="btnConfigs" :dialogParam="dialogParam" ></table-view>
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
            {label:'用户',value:'user_id',type:'dict_tags',labelName:'nickname',valueName:'id'},
            {label:'店铺',value:'store_id',type:'dict_tags',labelName:'store_name',valueName:'id'},
            {label:'真实姓名',value:'name'},
            {label:'提现银行',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'提现金额',value:'money',type:"tags"},
            {label:'手续费',value:'commission',type:"tags"},
            {label:'提现状态',value:'cash_status',type:'dict_tags'},
            {label:'备注',value:'remark'},
            {label:'拒绝原因',value:'refuse_info'},
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
            {label:'提现银行',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'提现金额',value:'money'},
            {label:'手续费',value:'commission'},
        ]

        const editColumn = [
            {label:'提现状态',value:'cash_status',type:'select'},
            {label:'备注',value:'remark',type:'textarea',span:24},
            {label:'拒绝原因',value:'refuse_info',type:'textarea',span:24},
        ]
        
        const btnConfigs = reactive({
            store:{show:false},
        })

        const dialogParam = reactive({
            dict:[
                {name:'user_id',url:'/Admin/users',isPageDict:true,selectDictByColumId:true},
                {name:'store_id',url:'/Admin/stores',isPageDict:true,selectDictByColumId:true}
            ],
            dictData:{
                cash_status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1},{label:proxy.$t('btn.rejected'),value:2}],
            },
            view:{column:addColumn},
            edit:{column:editColumn},
        })
        return {options,searchOptions,btnConfigs,dialogParam}
    }
}
</script>

<style>

</style>