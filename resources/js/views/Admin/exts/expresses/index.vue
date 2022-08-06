<template>
    <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam"></table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'物流名称',value:'name'},
            {label:'物流代码',value:'code',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'公司名称',value:'name',where:'likeRight'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'物流名称',value:'name'},
            {label:'物流代码',value:'code'},
        ]
        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                code:[{required:true,message:proxy.$t('msg.requiredMsg')}]
            },
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })
        return {options,dialogParam,searchOptions}
    }
}
</script>

<style>

</style>