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
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'栏目',value:'class_name',type:'tags'},
            {label:'标题',value:'name'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'标题',value:'name',where:'likeRight'},
            {label:'栏目',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
        ])

        // 表单配置 
        const addColumn = [
            {label:'栏目',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
            {label:'标题',value:'name'},
            {label:'内容',value:'content',type:'editor',span:24,viewType:'html'},
        ]
        const dialogParam = reactive({
            dict:[{name:'pid',url:'/load_article_menu?deep=2'}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}]
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