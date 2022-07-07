<template>
    <div class="qwit">
        <table-view :handleWidth="'80px'" :options="options" :searchOption="searchOptions" :params="params" :btnConfig="btnConfigs" :dialogParam="dialogParam" >
            <template #lev="{scopeData}">
                <span class="lev_rate">一级佣金比例 {{scopeData.lev_1||0.00}} %</span>
                <span class="lev_rate">二级佣金比例 {{scopeData.lev_2||0.00}} %</span>
                <span class="lev_rate">三级佣金比例 {{scopeData.lev_3||0.00}} %</span>
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
            {label:'商品图片',value:'goods_master_image',type:'avatar'},
            {label:'商品名称',value:'goods_name'},
            {label:'分佣比例',value:'lev',type:'custom'},
            {label:'分销金额',value:'is_type',type:'tags'},
            {label:'分销佣金',value:'is_belong',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'商品名称',value:'goods_name',where:'whereHas|goods'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'商品',value:'goods_id',type:'table_select',params:{},
            pageUrl:'/Seller/goods',
            options:[
                {label:'图片',value:'goods_master_image',type:'avatar'},
                {label:'名称',value:'goods_name'},
                {label:'品牌',value:'brand_name',type:'tags'},
                {label:'分类',value:'class_name',type:'tags'},
                {label:'价格',value:'goods_price'},
                {label:'库存',value:'goods_stock'},
                {label:'销量',value:'goods_sale'},
                {label:'创建时间',value:'created_at'},
            ]},
            {label:'一级佣金',value:'lev_1',type:'number'},
            {label:'二级佣金',value:'lev_2',type:'number'},
            {label:'三级佣金',value:'lev_3',type:'number'},
        ]

 
        
        
        const btnConfigs = reactive({
            show:{show:false},
        })

        const dialogParam = reactive({
            rules:{
                goods_id:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                lev_1:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                lev_2:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                lev_3:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        const params = reactive({
            // is_belong:'0|gt',
        })
        return {options,btnConfigs,dialogParam,params,searchOptions}
    }
}
</script>

<style lang="scss" scoped>
.lev_rate{font-size: 12px;color:#999;display: inline-block;}
</style>