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
            {label:'标题',value:'name'},
            {label:'标签',value:'tag',type:"tags"},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'标题',value:'name',where:'likeRight'},
            {label:'标签',value:'tag',where:'likeRight'},
            {label:'内容',value:'content',where:'like'},
        ])

        // 表单配置 
        const addColumn = [
            // {label:'上级菜单',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
            {label:'标题',value:'name'},
            {label:'标签',value:'tag'},
            {label:'内容',value:'content',type:'editor',span:24,viewType:'html'},
        ]
        const dialogParam = reactive({
            // dict:[{name:'pid',url:'/Admin/article_menus?pid=0',addSelect:{name:proxy.$t('btn.default'),id:0}}],
            rules:{
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