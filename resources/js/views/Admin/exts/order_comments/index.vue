<template>
    <div class="qwit">
        <table-view  :options="options" :params="params" :searchOption="searchOptions" :btnConfig="btnConfigs" :dialogParam="dialogParam" ></table-view>
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
            {label:'头像',value:'avatar',type:'avatar'},
            {label:'买家',value:'nickname'},
            {label:'商品',value:'goods_master_image',type:'avatar'},
            {label:'商品名称',value:'goods_name'},
            {label:'评分',value:'score',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'评论商品',value:'goods_name',where:'whereHas|goods'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'回复内容',value:'reply',type:'textarea',span:24},
        ]
        const viewColumn = [
            {label:'头像',value:'avatar',type:'avatar'},
            {label:'买家',value:'nickname'},
            {label:'商品',value:'goods_master_image',type:'avatar'},
            {label:'商品名称',value:'goods_name'},
            {label:'评分',value:'score'},
            {label:'图片',value:'image',viewType:'images',perView:true,span:24},
            {label:'评论内容',value:'content',viewType:'html',span:24},
            {label:'回复内容',value:'reply',viewType:'html',span:24},
        ]
        
        const btnConfigs = reactive({
            store:{show:false},
        })

        const dialogParam = reactive({
            view:{column:viewColumn},
            edit:{column:addColumn},
        })

        const params = reactive({
            'isWith':'goods'
        })
        return {options,btnConfigs,searchOptions,dialogParam,params}
    }
}
</script>

<style>

</style>