<template>
    <div class="seller_goods">
        <table-view v-if="tableVis" :options="options" :btnConfig="btnConfigs" :searchOption="searchOptions" :params="params" :dialogParam="dialogParam" >
            <template #table_topleft_hook>
                <el-button type="primary" :icon="Plus" @click="addGoods">{{$t('btn.add')}}</el-button>
            </template>
            <template #table_handleright_hook="row">
                <el-button :title="$t('btn.edit')"  type="primary" @click="editGoods(row)" :icon="Edit" />
            </template>
        </table-view>
        <div  v-if="!tableVis" class="goods_form">
            <goods-form ref="goods_form"  />
        </div>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import { Plus,Edit } from '@element-plus/icons'
import tableView from "@/components/common/table"
import goodsForm from "./form"
export default {
    components:{tableView,goodsForm},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const tableVis = ref(true)
        const formData = reactive({
            form:null,
        })
        const params = {
        }
        const options = reactive([
            {label:'图片',value:'goods_master_image',type:'avatar'},
            {label:'名称',value:'goods_name'},
            {label:'分类',value:'class_name',type:'tags'},
            {label:'价格',value:'goods_price'},
            {label:'库存',value:'goods_stock'},
            {label:'销量',value:'goods_sale'},
            {label:'上架',value:'goods_status',type:'dict_tags'},
            {label:'推荐',value:'is_recommend',type:'dict_tags'},
            // {label:'排序',value:'is_sort'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'商品名称',value:'goods_name',where:'like'},
        ])
        const dialogParam = reactive({
            dictData:{
                goods_status:[{label:proxy.$t('btn.offTheShelf'),value:0},{label:proxy.$t('btn.putOnTheShelf'),value:1}],
                is_recommend:[{label:proxy.$t('btn.no'),value:0},{label:proxy.$t('btn.yes'),value:1}],
            },
            addForm:{},
        })
        const btnConfigs = reactive({
            show:{show:false},
            store:{show:false},
            update:{show:false},
        })
        const addGoods = ()=>{
            tableVis.value = false
        }

        const editGoods = async (e)=>{
            tableVis.value = false
            const editForm = await proxy.R.get('/Admin/integral_goods/'+e.rows.id)
            proxy.$refs.goods_form.editGoods(editForm)
        }

        return {
            options,dialogParam,btnConfigs,tableVis,params,searchOptions,
            Plus,Edit,
            addGoods,editGoods,
        }
    }
}
</script>

<style lang="scss" scoped>
.goods_form{
    width: 90%;
    margin:0 auto;
}
</style>