<template>
    <div class="user_main table_lists">
        <div class="block_title">
            用户资料
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
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
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
             {label:'昵称',value:'nickname'},
             {label:'用户头像',value:'avatar',type:'avatar',perView:true,option:JSON.stringify({width:150,height:150})},
             {label:'性别',value:'sex',type:'radio'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            rules:{
                nickname:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            dictData:{
                sex:[{label:'男',value:1},{label:'女',value:0}]
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
                        await store.dispatch('login/getUserSer')
                        proxy.$message.success(proxy.$t('msg.success'))
                    }
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const loadData = async ()=>{
            let user = await store.dispatch('load/getUser')
            formData.nickname = user.nickname
            formData.avatar = user.avatar
            formData.sex = user.sex
        }

        onMounted( async ()=>{
            loadData()
        })

        return {dialogParams,loading,formData,editData}
    }
}
</script>
<style lang="scss" scoped>
.el_form{
    width: 529px;
}
</style>