<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" ></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const options = reactive([
            {label:'调用名称',value:'name'},
            {label:'签名模板',value:'code',type:'tags'},
            {label:'签名名称',value:'content'},
            {label:'描述',value:'description'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'调用名称',value:'name',where:'likeRight'},
            {label:'签名模板',value:'code',where:'likeRight'},
            {label:'签名名称',value:'content',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'调用名称',value:'name'},
            {label:'签名模板',value:'code'},
            {label:'签名名称',value:'content'},
            {label:'描述',value:'description',type:'textarea',span:24,maxlength:35},
        ]
        
        const dialogParam = reactive({
            rules:{
                code:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}],
                content:[{required:true,message:'不能为空'}]
            },
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })
        return {options,searchOptions,dialogParam}
    }
}
</script>

<style>

</style>