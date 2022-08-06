<template>
    <div class="qwit">
        <table-view :handleWidth="'80px'" :options="options" :searchOption="searchOptions" :btnConfig="btnConfigs" :dialogParam="dialogParam" ></table-view>
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
            {label:'用户',value:'user_id',type:'dict_tags',labelName:'nickname',valueName:'id'},
            {label:'名称',value:'name'},
            {label:'资金',value:'money',type:'money'},
            {label:'类型',value:'is_type',type:'dict_tags'},
            {label:'商家',value:'is_belong',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'名称',value:'name',where:'likeRight'},
            {label:'资金',value:'money'},
            {label:'类型',value:'is_type',type:'select'},
            {label:'是否商家',value:'is_belong',type:'select'},
            {label:'时间',value:'created_at',type:'daterange'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'名称',value:'name'},
            {label:'资金',value:'money'},
            {label:'类型',value:'is_type',type:'dict_tags'},
            {label:'商家',value:'is_belong',type:'dict_tags'},
        ]
        
        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            destroy:{show:false},
        })

        const dialogParam = reactive({
            dict:[
                {name:'user_id',url:'/Admin/users',selectDictByColumId:true,isPageDict:true}
            ],
            dictData:{
                is_type:[{label:proxy.$t('user.frozen_money'),value:'1'},{label:proxy.$t('user.money'),value:'0'},{label:proxy.$t('user.integral'),value:'2'}],
                is_belong:[{label:proxy.$t('btn.yes'),value:'1'},{label:proxy.$t('btn.no'),value:'0'}],
            },
            view:{column:addColumn},
        })
        return {options,searchOptions,btnConfigs,dialogParam}
    }
}
</script>

<style>

</style>