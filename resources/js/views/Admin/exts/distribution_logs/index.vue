<template>
    <div class="qwit">
        <table-view :handleHide="false" :options="options" :searchOption="searchOptions" :params="params" :btnConfig="btnConfigs" :dialogParam="dialogParam" >
            <template #lev="{scopeData}">
                <span class="lev_rate">{{scopeData.lev||0.00}} %</span>
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
            {label:'分销名称',value:'name'},
            {label:'店铺',value:'store_name'},
            {label:'商品价格',value:'goods_price'},
            {label:'佣金比例',value:'lev',type:'custom'},
            {label:'佣金',value:'commission'},
            {label:'结算状态',value:'status',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'分销名称',value:'name',where:'likeRight'},
            {label:'结算状态',value:'status',where:'select'}
        ])
        
        const btnConfigs = reactive({
            show:{show:false},
            store:{show:false},
            destroy:{show:false},
        })

        const dialogParam = reactive({
            dictData:{
                status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1}]
            },
        })

        const params = reactive({
            // is_belong:'0|gt',
        })
        return {options,searchOptions,btnConfigs,dialogParam,params}
    }
}
</script>

<style lang="scss" scoped>
.lev_rate{font-size: 12px;color:#999;display: inline-block;}
</style>