<template>
    <table-view :options="options" :dialogParam="dialogParam" :searchOption="searchOptions" :tableCfg="{lazy:true}"></table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'分类名称',value:'name'},
            {label:'图标',value:'thumb',type:'avatar'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'分类名称',value:'name',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'上级菜单',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'id'}},
            {label:'分类名称',value:'name'},
            {label:'缩略图',value:'thumb',type:'avatar'},
            {label:'排序',value:'is_sort'},
        ]
        const dialogParam = reactive({
            dict:[{name:'pid',url:'/load_goods_classes?deep=2',addSelect:{name:proxy.$t('btn.default'),id:0}}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}]
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
