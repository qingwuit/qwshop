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
            {label:'调用名称',value:'name'},
            {label:'手机号码',value:'phone',type:'tags'},
            {label:'发送内容',value:'content',type:'tags'},
            {label:'状态',value:'status',type:'dict_tags',tag_type:true},
            {label:'IP',value:'ip'},
            {label:'错误信息',value:'error_msg',overFlow:true},
            {label:'创建时间',value:'created_at'},
        ]);
        // 表单配置 
        const addColumn = [
            {label:'调用名称',value:'name'},
            {label:'手机号码',value:'phone'},
            {label:'发送内容',value:'content'},
            {label:'状态',value:'status',type:"dict_tags",tag_type:true},
            {label:'IP',value:'ip'},
            {label:'错误信息',value:'error_msg',viewType:'text',span:24},
        ]
        
        const btnConfigs = reactive({
            store:{show:false},
            edit:{show:false},
        })

        const dialogParam = reactive({
            dictData:{
                status:[{label:proxy.$t('btn.success'),value:'1'},{label:proxy.$t('btn.error'),value:'0'}],
            },
            view:{column:addColumn},
        })
        return {options,btnConfigs,dialogParam}
    }
}
</script>

<style>

</style>