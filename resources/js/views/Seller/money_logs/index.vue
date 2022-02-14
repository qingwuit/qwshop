<template>
    <div class="qwit">
        <table-view :handleWidth="'80px'" :options="options" :searchOption="searchOptions" :params="params" :btnConfig="btnConfigs" :dialogParam="dialogParam" >
            <template #table_top_hook>
                <el-row class="money_log_row">
                    <el-col class="money_log_col" :span="3">店铺余额</el-col>
                    <el-col class="money_log_col" :span="9">{{$t('btn.money')}} {{data.store.store_money??0.00}}</el-col>
                    <el-col class="money_log_col" :span="3">冻结资金</el-col>
                    <el-col class="money_log_col" :span="9">{{$t('btn.money')}} {{data.store.store_frozen_money??0.00}}</el-col>
                </el-row>
            </template>
        </table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const options = reactive([
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
            dictData:{
                is_type:[{label:proxy.$t('user.frozen_money'),value:'1'},{label:proxy.$t('user.money'),value:'0'},{label:proxy.$t('user.integral'),value:'2'}],
                is_belong:[{label:proxy.$t('btn.yes'),value:'1'},{label:proxy.$t('btn.no'),value:'0'}],
            },
            view:{column:addColumn},
        })

        const params = reactive({
            is_belong:'0|gt',
        })

        const data = reactive({
            store:{},
        })

        const loadData = ()=>{
            // 获取店铺信息
            proxy.R.get('/Seller/stores/0').then(res=>{
                if(!res.code) data.store = res
            })
        }

        loadData()

        return {options,searchOptions,btnConfigs,dialogParam,params,data}
    }
}
</script>

<style lang="scss" scoped>
.money_log_row{
    border:1px solid #efefef;
    margin-bottom: 20px;
    text-align: center;
    border-radius: 3px;
}
.money_log_col{
    border-right: 1px solid #efefef;
    padding:20px 0;
    background: #f5f5f5;
    &:last-child{border-right: none;}
    &:nth-child(2n){
        background: none;
    }
}
</style>