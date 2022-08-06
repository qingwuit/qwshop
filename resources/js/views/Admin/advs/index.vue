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
            {label:'广告位',value:'pid',type:'dict_tags',labelName:'name',valueName:'id'},
            {label:'图片',value:'image',type:'avatar',perView:true},
            {label:'名称',value:'name'},
            {label:'开始时间',value:'adv_start'},
            {label:'结束时间',value:'adv_end'},
            {label:'状态',value:'status',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'广告位',value:'pid',type:'select',labelName:'name',valueName:'id'},
            {label:'名称',value:'name',where:'likeRight'},
            {label:'开始时间',value:'adv_start',where:'ngt',type:'datetime'},
            {label:'结束时间',value:'adv_end',where:'nlt',type:'datetime'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'广告位',value:'pid',type:'select',labelName:'name',valueName:'id',viewType:'dict_tags'},
            {label:'名称',value:'name'},
            {label:'链接',value:'url'},
            {label:'类型',value:'is_type'},
            {label:'图片',value:'image',span:24,type:'image',perView:true,name:'adv'},
            {label:'开始时间',value:'adv_start',type:'datetime'},
            {label:'结束时间',value:'adv_end',type:'datetime'},
            {label:'状态',value:'status',type:'select',viewType:'dict_tags'},
            {label:'排序',value:'is_sort',type:'number'},
            // {label:'内容',value:'content',type:'editor',span:24,viewType:'html'},
        ]
        const dialogParam = reactive({
            dict:[
                {name:'pid',url:'/Admin/adv_spaces?per_page=100',isPageDict:true}

            ],
            dictData:{
                status:[{label:proxy.$t('config.web.open'),value:1},{label:proxy.$t('config.web.close'),value:0}],
            },
            rules:{
                pid:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                status:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                adv_start:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                adv_end:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                status:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}]
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