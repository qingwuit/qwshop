<template>
    <div class="qwcfg">
        <el-tabs tab-position="left" style="height:500px" @tab-click="handleClick" v-model="tabsIndex">
            <el-tab-pane :label="$t('config.configWechatPayWap')" name="weixin">
                <el-form style="width:60%;margin-top:8px;" :model="form.weixin" ref="weixin" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configOauth.client_id')" prop="client_id">
                        <el-input v-model="form.weixin.client_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configOauth.key')" prop="key">
                        <el-input v-model="form.weixin.key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configOauth.return_url')" prop="return_url">
                        <el-input v-model="form.weixin.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('weixin')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['weixin'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configWechatPayScan')" name="weixinweb">
                <el-form style="width:60%;margin-top:8px;" :model="form.weixinweb" ref="weixinweb" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configOauth.client_id')" prop="client_id">
                        <el-input v-model="form.weixinweb.client_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configOauth.key')" prop="key">
                        <el-input v-model="form.weixinweb.key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configOauth.return_url')" prop="return_url">
                        <el-input v-model="form.weixinweb.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('weixinweb')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['weixinweb'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>


            
        </el-tabs>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import {getToken} from '@/plugins/config'
export default {
    components:{},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            weixinweb:{},
            weixin:{},
        })
        const tabsIndex = ref('weixin')
        const loadConfig = async (e) => {
            let names = 'oauth';
            form[e] = await proxy.R.get('/Admin/configs',{name:names,many_name:e,many:true})
        }

        // 切换
        const handleClick = (e)=>{
            loadConfig(e.paneName)
        }

        loadConfig('weixin')
        
        const onSubmit = (e)=>{
            proxy.$refs[e].validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/Admin/configs/update',{oauth:form[e],many:true,many_name:e}).then(res=>{
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
       
        const Token = getToken()
        return {loading,tabsIndex,form,handleClick,onSubmit,Token}
    }
}
</script>

<style lang="scss" scope>
.qwcfg{
 
}
</style>