<template>
    <div class="user_main table_lists">
        <div class="block_title">
            优惠劵
        </div>
        <div class="x20 clear_line"></div>
        <table-view :handleWidth="80" :options="options" :handleHide="false" pageUrl="/user/coupons"  :btnConfig="btnConfigs"></table-view>
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
            {label:'名称',value:'name'},
            {label:'折扣',value:'money',type:'tags'},
            {label:'消费',value:'use_money',type:'tags'},
            {label:'有效时间',value:'end_time',overFlow:true},
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

        return {options,btnConfigs}
    }
}
</script>
<style lang="scss" scoped>

</style>