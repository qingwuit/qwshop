<template>
    <div class="admin_goods_form">
        <table-view v-if="!tableVis" :options="options" :searchOption="searchOptions" :btnConfig="btnConfigs" :params="params" :dialogParam="dialogParam" handleWidth='80px'>
            <template #table_handleright_hook="row">
                <el-button :title="$t('btn.edit')"  type="primary" @click="editGoods(row)" :icon="Edit" />
            </template>
            <template #goods_status="{scopeData}">
                <el-switch v-model="scopeData.goods_status" :active-value="1" @change="(e)=>{goodsStatusChange(e,scopeData)}" :inactive-value="0" />
            </template>
        </table-view>

        <div v-if="tableVis" class="goods_form">
            <goods-form ref="goods_form"  />
        </div>
            
    </div>
    
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import { Edit } from '@element-plus/icons'
import tableView from "@/components/common/table"
import goodsForm from "./form"
export default {
    components:{tableView,goodsForm},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const tableVis = ref(false)
        const params = {
            isWith:'goods_class,goods_brand'
        }
        const options = reactive([
            {label:'图片',value:'goods_master_image',type:'avatar',perView:true},
            {label:'名称',value:'goods_name'},
            {label:'品牌',value:'brand_name',type:'tags'},
            {label:'分类',value:'class_name',type:'tags'},
            {label:'价格',value:'goods_price'},
            {label:'库存',value:'goods_stock'},
            {label:'销量',value:'goods_sale'},
            {label:'上架状态',value:'goods_status',type:'custom'},
            {label:'审核状态',value:'goods_verify',type:'dict_tags'},
            // {label:'排序',value:'is_sort'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'名称',value:'goods_name',where:'likeRight'},
            {label:'上架状态',value:'goods_status',type:'select',data:{goods_status:[{label:proxy.$t('btn.yes'),value:1},{label:proxy.$t('btn.no'),value:0}]}},
            {label:'审核状态',value:'goods_verify',type:'select',data:{goods_verify:[{label:proxy.$t('btn.waitExamine'),value:2},{label:proxy.$t('btn.passExamine'),value:1},{label:proxy.$t('btn.rejected'),value:0}]}},
        ])

        const dialogParam = reactive({
            // dict:[{name:'pid',url:'/load_goods_classes?deep=2'}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}]
            },
            dictData:{
                goods_status:[{label:proxy.$t('btn.yes'),value:1},{label:proxy.$t('btn.no'),value:0}],
                goods_verify:[{label:proxy.$t('btn.waitExamine'),value:2},{label:proxy.$t('btn.passExamine'),value:1},{label:proxy.$t('btn.rejected'),value:0}]
            },
            addForm:{},
        })
        const btnConfigs = reactive({
            show:{show:false},
            store:{show:false},
            update:{show:false},
        })

        const editGoods = async (e)=>{
            tableVis.value = true
            const editForm = await proxy.R.get('/Admin/goods/'+e.rows.id)
            proxy.$refs.goods_form.editGoods(editForm)
        }

        const goodsStatusChange = async (e,scopeData)=>{
            let res = await proxy.R.put('/Admin/goods/'+scopeData.id,{goods_status:e})
            if(!res.code){
                proxy.$message.success(proxy.$t('msg.success'))
            }
        }
        return {options,searchOptions,dialogParam,btnConfigs,params,tableVis,Edit,editGoods,goodsStatusChange}
    }
}
</script>

<style>

</style>