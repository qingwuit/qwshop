<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" :params="{isWith:'permission_groups'}"></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const options = reactive([
            {label:'接口分组',value:'group_name',params:{}},
            {label:'权限名称',value:'name'},
            {label:'接口路由',value:'apis',type:'tags'},
            {label:'接口描述',value:'content'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'权限名称',value:'name',where:'likeRight'},
            {label:'接口分组',value:'pid',type:'select',labelName:'name',valueName:'id'},
            {label:'接口路由',value:'apis',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'接口分组',value:'pid',type:'select',labelName:'name',valueName:'id'},
            {label:'权限名称',value:'name'},
            {label:'接口路由',value:'apis',type:'autocomplete'},
            {label:'接口描述',value:'content',type:'textarea',span:24,maxlength:35},
        ]

        let viewColumn = _.cloneDeep(addColumn)
        viewColumn.forEach(item=>{
            if(item.value == 'content'){
                item.type = 'text'
            }else{
                item.type = undefined
            }
        })
        viewColumn[0]['value'] = 'group_name'
        
        const dialogParam = reactive({
            dict:[
                {name:'pid',url:'/Admin/permission_groups?isAll=true'},
                {name:'apis',url:'/Admin/load_permission'}
            ],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}],
                apis:[{required:true,message:'不能为空'}]
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