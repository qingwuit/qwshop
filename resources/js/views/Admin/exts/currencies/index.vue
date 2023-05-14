<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" ></table-view>
    </div>
</template>

<script setup>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"

const {proxy} = getCurrentInstance()
const options = reactive([
    {label:'货币名称',value:'name'},
    {label:'语种标识',value:'ename',type:'tags'},
    {label:'英文符号',value:'currency',type:'tags'},
    {label:'支付标识',value:'currency_ename'},
    {label:'货币描述',value:'content'},
    {label:'创建时间',value:'created_at'},
]);

// 搜索字段
const searchOptions = reactive([
    {label:'名称',value:'name',where:'likeRight'},
    {label:'语种标识',value:'ename',where:'like'},
    {label:'英文符号',value:'currency',where:'like'},
    {label:'支付标识',value:'currency_ename',where:'like'},
])

// 表单配置 
const addColumn = [
    {label:'货币名称',value:'name',span:24},
    {label:'语种标识',value:'ename'},
    {label:'英文符号',value:'currency'},
    {label:'支付标识',value:'currency_ename'},
    {label:'货币描述',value:'content',type:'textarea',viewType:'text',span:24},
]

const dialogParam = reactive({
    rules:{
        name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
        ename:[{required:true,message:proxy.$t('msg.requiredMsg')}],
        currency_ename:[{required:true,message:proxy.$t('msg.requiredMsg')}],
        currency:[{required:true,message:proxy.$t('msg.requiredMsg')}],
    },
    view:{column:addColumn},
    add:{column:addColumn},
    edit:{column:addColumn},
})

</script>