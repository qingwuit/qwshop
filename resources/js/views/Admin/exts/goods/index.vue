<template>
    <table-view :options="options" :searchOption="searchOptions" :btnConfig="btnConfigs" :params="params" :dialogParam="dialogParam"></table-view>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const params = {
            isWith:'goods_class,goods_brand'
        }
        const options = reactive([
            {label:'图片',value:'goods_master_image',type:'avatar'},
            {label:'名称',value:'goods_name'},
            {label:'品牌',value:'brand_name',type:'tags'},
            {label:'分类',value:'class_name',type:'tags'},
            {label:'价格',value:'goods_price'},
            {label:'库存',value:'goods_stock'},
            {label:'销量',value:'goods_sale'},
            // {label:'排序',value:'is_sort'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'名称',value:'goods_name',where:'likeRight'},
        ])

        const dialogParam = reactive({
            // dict:[{name:'pid',url:'/load_goods_classes?deep=2'}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}]
            },
            addForm:{},
        })
        const btnConfigs = reactive({
            store:{show:false},
        })
        return {options,searchOptions,dialogParam,btnConfigs,params}
    }
}
</script>

<style>

</style>