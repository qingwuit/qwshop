<template>
    <div class="qwcfg">
        <el-form style="width:60%;margin-top:8px;" :model="form.cfgForm" ref="cfgForm" label-position="right" label-width="140px">
            <el-form-item :label="$t('config.sms.other')">
               <el-button :icon="Edit" @click="$router.push('/Admin/sms')">{{$t('config.sms.sign')}}</el-button>
               <el-button :icon="Document" @click="$router.push('/Admin/sms_logs')">{{$t('config.sms.log')}}</el-button>
            </el-form-item>
            <el-form-item :label="$t('config.sms.key')" prop="key">
                <el-input v-model="form.cfgForm.key"  />
            </el-form-item>
            <el-form-item :label="$t('config.sms.secret')" prop="secret">
                <el-input v-model="form.cfgForm.secret"  />
            </el-form-item>
            <el-form-item >
                <el-button type="primary" @click="onSubmit('cfgForm')">{{$t('btn.determine')}}</el-button>
                <el-button @click="$refs['cfgForm'].resetFields()">{{$t('btn.reset')}}</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import {Edit ,Document } from '@element-plus/icons'
import {reactive,ref,getCurrentInstance} from "vue"
export default {
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            cfgForm:{},
        })
        const loadConfig = async (e) => {
            if(e == 'cfgForm'){
                let names = 'sms'
                form.cfgForm = await proxy.R.get('/Admin/configs',{name:names})
            }
        }

        loadConfig('cfgForm')
        
        const onSubmit = (e)=>{
            proxy.$refs.cfgForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/Admin/configs/update',{sms:form.cfgForm}).then(res=>{
                        loading.value = false
                        if(!res.code){
                            loadConfig(e)
                            proxy.$message.success(proxy.$t('msg.success'))
                        }
                    }).catch((err)=>{
                        console.error(err)
                    }).finally(()=>{
                        loading.value = false
                    })
                } catch (error) {
                    loading.value = false
                }
            })

        }
        return {loading,form,onSubmit,Edit,Document}
    }
}
</script>

<style>

</style>