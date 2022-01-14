<template>
    <div class="qwcfg">
        <el-tabs tab-position="left" style="height:780px" @tab-click="handleClick" v-model="tabsIndex">

            <!-- 地图配置 -->
            <el-tab-pane :label="$t('config.configStore.storeConfig')" name="cfgForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgForm" ref="cfgForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.goodsVerify')" prop="goods_verify">
                        <el-switch v-model="form.cfgForm.goods_verify"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.cashMoney')" prop="cash">
                        <el-input type="number" v-model="form.cfgForm.cash"  ><template #append>%</template></el-input>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" :loading="loading" @click="onSubmit('cfgForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import { Plus } from '@element-plus/icons'
export default {
    components:{Plus},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            cfgForm:{},
        })
        const tabsIndex = ref('cfgForm')
        const loadConfig = async (e) => {
            if(e == 'cfgForm'){
                let names = ['store']
                form.cfgForm = (await proxy.R.get('/Admin/configs',{name:names})).store
            }
        }

        // 切换
        const handleClick = (e)=>{
            loadConfig(e.paneName)
        }

        loadConfig('cfgForm')
        
        const onSubmit = (e)=>{
            if(e == 'cfgForm'){
                proxy.$refs.cfgForm.validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',{store:form.cfgForm}).then(res=>{
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
        }
   

        return {loading,tabsIndex,form,handleClick,onSubmit}
    }
}
</script>

<style lang="scss" scope>

</style>