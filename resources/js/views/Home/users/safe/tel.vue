<template>
    <div class="user_main table_lists">
        <div class="block_title">
            更换手机
        </div>
        <div class="x20"></div>
        <el-form class="el_form" v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
            <el-row :gutter="20">
                <el-col :span="24">
                    <el-form-item :label="'手机号码'" >
                        <span class="old_phone">{{data.userInfo.phone||'-'}}</span>
                    </el-form-item>
                </el-col>
                <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                    <el-form-item :label="v.label" :prop="v.value">
                        <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData[v.value]" />
                    </el-form-item>
                </div></el-col>
                <el-col :span="24">
                    <el-form-item :label="'验证码'"  :prop="'code'">
                        <sms :phone="formData.phone" codeName="user" v-model:code="formData.code" />
                    </el-form-item>
                </el-col>
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
import { useStore } from 'vuex'
import sms from "@/components/common/sms"
export default {
    components:{sms},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const loading = ref(false)
        const formData = reactive({

        })
        const data = reactive({
            userInfo:{}
        })
        // 表单配置 
        const addColumn = [
             {label:'新手机号码',value:'phone'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            rules:{
                phone:[
                    {required:true,message:proxy.$t('msg.requiredMsg')},
                ],
                code:[
                    {required:true,message:proxy.$t('msg.requiredMsg')},
                ],
            },
            add:{column:addColumn},
        })

        const editData = ()=>{
            proxy.$refs.addForm.validate( async (valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    const servUser = await store.dispatch('login/editUserSer',formData)
                    if(!servUser.code){
                        proxy.$refs.addForm.resetFields()
                        proxy.$message.success(proxy.$t('msg.success'))
                    }
                    editUserVis.value = false
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }

        onMounted( async ()=>{
            loadData()
        })

        const loadData = async ()=>{
            let user = await store.dispatch('login/getUserSer')
            data.userInfo = user
        }
        return {dialogParams,loading,formData,editData,data}
    }
}
</script>
<style lang="scss" scoped>
.el_form{
    width: 529px;
}
.old_phone{
    background:#efefef;padding:3px 15px;border-radius:3px;
}
</style>