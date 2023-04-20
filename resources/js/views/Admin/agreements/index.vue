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
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'协议名称',value:'name'},
            {label:'调用名称',value:'ename',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'协议名称',value:'name',where:'likeRight'},
            {label:'调用名称',value:'ename',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'协议名称',value:'name'},
            {label:'调用名称',value:'ename'},
            {label:'发送内容',value:'content',type:'editor',viewType:'html',span:24},
        ]

        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                ename:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            destroyOnClose:true,
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