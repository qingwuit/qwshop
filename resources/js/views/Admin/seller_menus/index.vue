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
            {label:'菜单名称',value:'name'},
            {label:'图标',value:'icon',type:'icon_tags'},
            {label:'路由',value:'apis',type:'tags'},
            {label:'组件',value:'view',type:'tags'},
            // {label:'排序',value:'is_sort'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'菜单名称',value:'name',where:'likeRight'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'上级菜单',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
            {label:'菜单名称',value:'name'},
            {label:'图标',value:'icon',type:'icon'},
            {label:'路由',value:'apis'},
            {label:'组件',value:'view'},
            {label:'类型',value:'is_open',type:'select'},
            {label:'排序',value:'is_sort'},
        ]
        const dialogParam = reactive({
            dict:[{name:'pid',url:'/Admin/load_seller_menu?deep=2',addSelect:{name:proxy.$t('btn.default'),id:0}}],
            dictData:{
                is_open:[{label:proxy.$t('menu.table'),value:0},{label:proxy.$t('menu.custom'),value:1}]
            },
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}],
                is_open:[{required:true,message:'不能为空'}]
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