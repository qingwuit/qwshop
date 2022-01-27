<template>
    <div class="user_main table_lists">
        <div class="block_title">
            用户资料
        </div>
        <div class="x20"></div>
        <el-form class="el_form" v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="data.formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
            <el-row :gutter="20">
                <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                    <el-form-item :label="v.label" :prop="v.value">
                        <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="data.formData[v.value]" />
                    </el-form-item>
                </div></el-col>
            </el-row>

            <!-- 按钮处理 -->
            <el-row :gutter="20">
                <el-col :span="24">
                    <el-form-item>
                        <el-button color="#e50e19" :loading="loading" type="primary" @click="editData">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['addForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-col>
            </el-row>

        </el-form>
        <el-empty v-else />
    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
export default {
    components:{},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const loading = ref(false)
        const data = reactive({
            formData:{}
        })
        // 表单配置 
        const addColumn = [
             {label:'真实姓名',value:'name'},
             {label:'身份证号',value:'card_no'},
             {label:'身份证(上)',value:'card_t',type:'image'},
             {label:'身份证(上)',value:'card_b',type:'image'},
             {label:'银行卡号',value:'bank_no'},
             {label:'银行名称',value:'bank_name'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                card_no:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                card_t:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                card_b:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                bank_no:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                bank_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            add:{column:addColumn},
        })

        const editData = ()=>{
            proxy.$refs.addForm.validate( async (valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    const resp = await proxy.R.post('/user/check',data.formData)
                    if(!resp.code){
                        loadData()
                        proxy.$message.success(proxy.$t('msg.success'))
                    }
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const loadData = async ()=>{
            data.formData = await proxy.R.get('/user/check')
        }

        onMounted( async ()=>{
            loadData()
        })

        return {dialogParams,loading,data,editData}
    }
}
</script>
<style lang="scss" scoped>
.el_form{
    width: 529px;
}
</style>