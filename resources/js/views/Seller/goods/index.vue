<template>
    <div class="seller_goods">
        <table-view v-if="tableVis" :options="options" :btnConfig="btnConfigs" :params="params" :dialogParam="dialogParam" >
            <template #table_topleft_hook>
                <el-button type="primary" :icon="Plus" @click="addGoods">{{$t('btn.add')}}</el-button>
            </template>
            <template #table_handleright_hook="row">
                <el-button :title="$t('btn.edit')"  type="primary" @click="editGoods(row)" :icon="Edit" />
            </template>
        </table-view>
        <div  v-if="!tableVis" class="goods_form">
            <goods-form ref="goods_form"  />
            <!-- <el-form ref="addForm" label-position="right" :model="dialogParam.addForm" :rules="dialogParam.rules" label-width="120px">
                <el-form-item :label="'商品标题'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'副标题'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品编号'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品品牌'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品分类'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品图片'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'平台价格'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'市场价格'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品重量'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品库存'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'规格属性(SKU)'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'运费模版'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                <el-form-item :label="'商品详情'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
            </el-form> -->
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


        const dialogParam = reactive({
            // dict:[{name:'pid',url:'/load_goods_classes?deep=2'}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}]
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
            const editForm = await proxy.R.get('/Seller/goods/'+e.rows.id)
            proxy.$refs.goods_form.editGoods(editForm)
        }

        return {
            options,dialogParam,btnConfigs,tableVis,params,
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