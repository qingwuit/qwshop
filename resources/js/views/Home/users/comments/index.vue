<template>
    <div class="user_main table_lists">
        <div class="block_title">
            我的评论
        </div>
        <div class="x20 clear_line"></div>
        <table-view :handleWidth="80" :options="options" :params="{isResource:'HomeUser',isWith:'goods,user'}" :btnConfig="btnConfigs" :dialogParam="dialogParam"></table-view>
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
            {label:'商品图片',value:'goods_master_image',type:'avatar'},
            {label:'商品名称',value:'goods_name',overFlow:true},
            {label:'综合评分',value:'score',overFlow:true},
            {label:'描述相符',value:'agree',overFlow:true},
            {label:'服务态度',value:'service',overFlow:true},
            {label:'发货速度',value:'speed',overFlow:true},
            {label:'内容',value:'content',overFlow:true},
            {label:'创建时间',value:'created_at'},
        ]);
        // 表单配置 
        const addColumn = [
             {label:'昵称',value:'nickname'},
             {label:'用户名',value:'username'},
             {label:'密码',value:'password',type:'password'},
             {label:'角色',value:'role_id',type:'select',multiple:true,labelName:'name',valueName:'id',viewType:'tags_array'},
             {label:'头像',value:'avatar',type:'avatar',perView:true,option:JSON.stringify({width:150,height:150})},
        ]

        const btnConfigs = reactive({
            store:{show:false},
            show:{show:false},
            search:{show:false},
            export:{show:false},
            destroy:{show:false},
            update:{show:false},
        })
        const dialogParam = reactive({
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        return {options,dialogParam,btnConfigs}
    }
}
</script>
<style lang="scss" scoped>

</style>