<template>
    <div class="user_main table_lists">
        <div class="block_title">
            分销佣金
        </div>
        <div class="x20 clear_line"></div>
        <table-view :handleWidth="80" :options="options" :handleHide="false" :dialogParams="dialogParam" pageUrl="/user/distribution_logs"  :btnConfig="btnConfigs"></table-view>
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
            {label:'买家昵称',value:'nickname'},
            {label:'商品价格',value:'goods_price'},
            {label:'佣金比例',value:'lev'},
            {label:'佣金',value:'commission'},
            {label:'结算状态',value:'status',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);


        const btnConfigs = reactive({
            store:{show:false},
            show:{show:false},
            search:{show:false},
            export:{show:false},
            destroy:{show:false},
            update:{show:false},
        })

        const dialogParam = reactive({
            dictData:{
                status:[{label:proxy.$t('btn.waitExamine'),value:0},{label:proxy.$t('btn.success'),value:1}]
            },
        })

        return {options,dialogParam,btnConfigs}
    }
}
</script>
<style lang="scss" scoped>

</style>