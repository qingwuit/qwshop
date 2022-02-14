<template>
    <table-view :handleWidth="'80px'" :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" :btnConfig="btnConfigs">
        <template #table_topleft_hook="{dialogParams}">
            <el-button type="primary" :icon="Setting" @click="handleSett(dialogParams)">手动结算</el-button>
        </template>
    </table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
import { Setting  } from '@element-plus/icons'
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const options = reactive([
            {label:'结算单号',value:'settlement_no'},
            {label:'总金额',value:'total_price',type:'tags'},
            {label:'结算金额',value:'settlement_price',type:'tags'},
            {label:'结算状态',value:'status',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'结算状态',value:'status',type:'select'},
        ])

        // 表单配置 
        const viewColumn = [
            {label:'结算单号',value:'settlement_no'},
            {label:'总金额',value:'total_price'},
            {label:'结算金额',value:'settlement_price'},
            {label:'结算状态',value:'status',viewType:'dict_tags'},
            {label:'备注',value:'info'},
        ]
        const dialogParam = reactive({
            view:{column:viewColumn},
            dictData:{
                status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1},{label:proxy.$t('btn.rejected'),value:2}],
            },
        })

        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            destroy:{show:false},
        })

        const handleSett = (dialogParams)=>{
            proxy.R.get('/Admin/order_settlement_handle').then(res=>{
                if(!res.code){
                    dialogParams.reloadData()
                    return proxy.$message.success(proxy.$t('msg.success'))
                }
            })
        }

        return {
            options,searchOptions,dialogParam,btnConfigs,
            handleSett,
            Setting
        }
    }
}
</script>

<style>

</style>