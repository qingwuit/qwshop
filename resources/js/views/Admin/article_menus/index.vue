<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" :tableCfg="{lazy:true}"></table-view>
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
            {label:'栏目名称',value:'name'},
            {label:'排序',value:'is_sort'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'栏目名称',value:'name',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'上级菜单',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
            {label:'栏目名称',value:'name'},
            {label:'排序',value:'is_sort',type:'number'},
        ]

        let viewColumn = _.cloneDeep(addColumn)
        viewColumn[0]['type'] = 'dict_tags'
        viewColumn[0]['labelName'] = 'name'
        viewColumn[0]['valueName'] = 'id'
        viewColumn[0]['props'] = {}
        const dialogParam = reactive({
            dict:[{name:'pid',url:'/load_article_menu?deep=1',addSelect:{name:proxy.$t('btn.default'),id:0}}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
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