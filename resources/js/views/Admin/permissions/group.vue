<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam"></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const options = reactive([
            {label:'分组名称',value:'name'},
            {label:'分组描述',value:'content'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'分组名称',value:'name',where:'likeRight'},
            {label:'分组描述',value:'content',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
             {label:'分组名称',value:'name'},
             {label:'分组描述',value:'content',type:'textarea',span:24},
        ]

        let viewColumn = _.cloneDeep(addColumn)
        viewColumn[1]['type'] = undefined
        
        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:'不能为空'}]
            },
            view:{column:viewColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })
        return {options,searchOptions,dialogParam}
    }
}
</script>

<style>

</style>