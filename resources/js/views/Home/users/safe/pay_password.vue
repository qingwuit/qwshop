<template>
    <div class="user_main table_lists">
        <div class="block_title">
            支付密码
        </div>
        <div class="x20"></div>
        <el-form class="el_form" v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
            <el-row :gutter="20">
                <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                    <el-form-item :label="v.label" :prop="v.value">
                        <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData[v.value]" />
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
import {reactive,ref,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
export default {
    components:{},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const loading = ref(false)
        const formData = reactive({

        })
        // 表单配置 
        const addColumn = [
             {label:'新密码',value:'pay_password',type:'password'},
             {label:'重复密码',value:'re_pay_password',type:'password'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            rules:{
                pay_password:[
                    {required:true,message:proxy.$t('msg.requiredMsg')},
                    {type:'string',pattern: /^[0-9]*$/,message:proxy.$t('msg.payPasswordThr')},
                    {len: 6,message:'6'+proxy.$t('msg.inputLengthThr')}
                ],
                re_pay_password:[
                    {required:true,message:proxy.$t('msg.requiredMsg')},
                    {type:'string',pattern: /^[0-9]*$/,message:proxy.$t('msg.payPasswordThr')},
                    {len: 6,message:'6'+proxy.$t('msg.inputLengthThr')}
                ],
            },
            add:{column:addColumn},
        })

        const editData = ()=>{
            proxy.$refs.addForm.validate( async (valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                if(formData.password != formData.re_password) return proxy.$message.error(proxy.$t('msg.reInputError'))
                loading.value = true
                try {
                    let formDatas = {pay_password:formData.pay_password}
                    const servUser = await store.dispatch('login/editUserSer',formDatas)
                    if(!servUser.code){
                        proxy.$refs.addForm.resetFields()
                        proxy.$message.success(proxy.$t('msg.success'))
                    }else{
                        proxy.$message.error(servUser.msg)
                    }
                    editUserVis.value = false
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }
        return {dialogParams,loading,formData,editData}
    }
}
</script>
<style lang="scss" scoped>
.el_form{
    width: 529px;
}
</style>