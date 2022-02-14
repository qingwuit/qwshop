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
        // 配置字段
        const options = reactive([
            {label:'头像',value:'avatar',type:'avatar',perView:true},
            {label:'昵称',value:'nickname'},
            {label:'用户名',value:'username',type:'tags'},
            {label:'余额',value:'money',type:'tags'},
            {label:'冻结资金',value:'frozen_money',type:'tags'},
            {label:'积分',value:'integral',type:'tags'},
            {label:'登陆时间',value:'last_login_time'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'昵称',value:'nickname',where:'likeRight'},
            {label:'用户名',value:'username',where:'likeRight'}
        ])

        // 表单配置 
        const addColumn = [
             {label:'昵称',value:'nickname'},
             {label:'用户名',value:'username'},
             {label:'密码',value:'password',type:'password'},
             {label:'头像',value:'avatar',type:'avatar',perView:true,option:JSON.stringify({width:150,height:150})},
        ]
        let viewColumn = _.cloneDeep(addColumn)
        viewColumn = viewColumn.filter(item=>!item.type || item.type.indexOf('password') == -1)
        const dialogParam = reactive({
            rules:{
                username:[{required:true,message:proxy.$t('msg.requiredMsg')}],
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