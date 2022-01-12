<template>
    <div class="qwit seller_goods_attr">
        <table-view :params="{isWith:'specs'}" :options="options" :dialogParam="dialogParam">
            <template #table_add_hook="{dialogParams}">
                <div class="table_add_hook_block">
                    <el-form ref="addForm" label-position="right" :model="dialogParam.addForm" :rules="dialogParam.rules" label-width="120px">
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <el-form-item :label="'属性名称'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                            </el-col>
                            <el-col :span="24">
                                <el-form-item :label="'属性规格'" prop="specs">
                                    <el-tag size="medium" v-for="(v,k) in dialogParam.addForm.specs||[]" :key="k" closable @close="handleClose(v)">{{v}}</el-tag>
                                    <el-input
                                        v-if="inputVisible"
                                        ref="saveTagInput"
                                        v-model="inputValue"
                                        class="input-new-tag"
                                        size="mini"
                                        @keyup.enter="handleInputConfirm"
                                        @blur="handleInputConfirm"
                                    >
                                    </el-input>
                                    <el-button v-else class="button-new-tag" size="small" @click="showInput"
                                        >+ New</el-button
                                    >
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item >
                                    <div class="table_btn">
                                        <el-button :loading="loading" type="primary" @click="storeData(dialogParams)">{{$t('btn.determine')}}</el-button>
                                        <el-button @click="dialogParams.closeDialog()">{{$t('btn.cancel')}}</el-button>
                                    </div>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        
                    </el-form>
                </div>
            </template>
            <template #table_edit_hook="{dialogParams,formData}">
                <div class="table_add_hook_block">
                    {{formDataChange(formData)}}
                    <el-form ref="editForm" label-position="right" :model="dialogParam.addForm" :rules="dialogParam.rules" label-width="120px">
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <el-form-item :label="'属性名称'" prop="name"><el-input v-model="dialogParam.addForm.name" /></el-form-item>
                            </el-col>
                            <el-col :span="24">
                                <el-form-item :label="'属性规格'" prop="specs">
                                    <el-tag size="medium" v-for="(v,k) in dialogParam.addForm.specs||[]" :key="k" closable @close="handleClose(v)">{{v}}</el-tag>
                                    <el-input
                                        v-if="inputVisible"
                                        ref="saveTagInput"
                                        v-model="inputValue"
                                        class="input-new-tag"
                                        size="mini"
                                        @keyup.enter="handleInputConfirm"
                                        @blur="handleInputConfirm"
                                    >
                                    </el-input>
                                    <el-button v-else class="button-new-tag" size="small" @click="showInput"
                                        >+ New</el-button
                                    >
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item >
                                    <div class="table_btn">
                                        <el-button :loading="loading" type="primary" @click="updateData(dialogParams)">{{$t('btn.determine')}}</el-button>
                                        <el-button @click="dialogParams.closeDialog()">{{$t('btn.cancel')}}</el-button>
                                    </div>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        
                    </el-form>
                </div>
            </template>
            
        </table-view>
    </div>
</template>

<script>
import {reactive,ref,nextTick,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const inputVisible = ref(false)
        const loading = ref(false)
        const inputValue = ref('')
        // 配置字段
        const options = reactive([
            {label:'属性名称',value:'name'},
            {label:'规格名称',value:'specs',type:"tags_array"},
            {label:'创建时间',value:'created_at'},
        ]);
        // 表单配置 
        const addColumn = [
             {label:'属性名称',value:'name'},
             {label:'规格名称',value:'specs',viewType:"tags_array",span:24},
        ]
      
        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                specs:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
            addForm:{},
            editForm:{},
        })

        const handleClose = (e)=>{
            dialogParam.addForm.specs.splice(dialogParam.addForm.specs.indexOf(e), 1)
        }
        const handleInputConfirm = (e)=>{
            let inputValues = inputValue.value
            if (inputValues) {
                if(!dialogParam.addForm.specs) dialogParam.addForm.specs = []
                dialogParam.addForm.specs.push(inputValues)
                console.log(dialogParam.addForm.specs)
                
            }
            inputVisible.value = false
            inputValue.value = ''
        }
        const showInput = ()=>{
            inputVisible.value = true
        }

        const storeData = (dialogParams)=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.post('/Seller/goods_attrs',dialogParam.addForm).then(res=>{
                        if(!res.code){
                            dialogParams.reloadData()
                            dialogParams.closeDialog()
                            proxy.$message.success(proxy.$t('msg.success'))
                        }
                    }).catch((err)=>{
                        console.log(err)
                    }).finally(()=>{
                        loading.value = false
                    })
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const updateData = (dialogParams)=>{
            proxy.$refs.editForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/Seller/goods_attrs/'+dialogParam.addForm.id,dialogParam.addForm).then(res=>{
                        if(!res.code){
                            dialogParams.reloadData()
                            dialogParams.closeDialog()
                            proxy.$message.success(proxy.$t('msg.success'))
                        }
                    }).catch((err)=>{
                        console.log(err)
                    }).finally(()=>{
                        loading.value = false
                    })
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const formDataChange = (e)=>{
            // console.log(e)
            dialogParam.addForm = e.edit
        }


        return {
            options,dialogParam,inputVisible,inputValue,loading,
            handleClose,handleInputConfirm,showInput,
            formDataChange,storeData,updateData
        }
    }
}
</script>

<style lang="scss">
.seller_goods_attr{
    .el-tag--medium {
        margin-right: 10px;
    }
    .button-new-tag {
        // margin-left: 10px;
        height: 32px;
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
    }
    .input-new-tag {
        width: 90px;
        // margin-left: 10px;
        vertical-align: bottom;
    }
}
</style>