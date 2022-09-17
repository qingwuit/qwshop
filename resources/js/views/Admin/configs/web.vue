<template>
    <div class="qwcfg">
        <el-tabs tab-position="left" style="height:780px" @tab-click="handleClick" v-model="tabsIndex">
            <el-tab-pane :label="$t('config.configWeb')" name="cfgForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgForm" ref="cfgForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.web.webName')" prop="web_name">
                        <el-input v-model="form.cfgForm.web_name"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.indexName')" prop="index_name">
                        <el-input v-model="form.cfgForm.index_name"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.keyword')" prop="keyword">
                        <el-input type="textarea" v-model="form.cfgForm.keyword" show-word-limit maxlength="100"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.description')" prop="description">
                        <el-input type="textarea" v-model="form.cfgForm.description" show-word-limit maxlength="100"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.logo')" prop="logo">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Admin/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'logo',option:{width:242,height:74}}"
                            :on-success="handleAvatarSuccess"
                        >
                            <img v-if="form.cfgForm.logo" style="width:100%;height:100%" :src="form.cfgForm.logo" class="avatar" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.web.tel')" prop="tel">
                        <el-input v-model="form.cfgForm.tel"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.mobile')" prop="mobile">
                        <el-input v-model="form.cfgForm.mobile"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.email')" prop="email">
                        <el-input v-model="form.cfgForm.email"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.icp')" prop="icp">
                        <el-input v-model="form.cfgForm.icp"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.web.closeStatus')" prop="close_status">
                        <el-radio-group v-model="form.cfgForm.close_status">
                            <el-radio label="1">{{$t('config.web.open')}}</el-radio>
                            <el-radio label="0">{{$t('config.web.close')}}</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item :label="$t('config.web.closeReason')" prop="close_reason">
                        <el-input type="textarea" v-model="form.cfgForm.close_reason" show-word-limit maxlength="100"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('cfgForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <!-- 上传配置 -->
            <el-tab-pane :label="$t('config.configUpload')" name="cfgUploadForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgUploadForm" ref="cfgUploadForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.upload.saveType')" prop="save_type">
                        <el-switch v-model="form.cfgUploadForm.save_type" inline-prompt :active-text="$t('btn.yes')" :inactive-text="$t('btn.no')" />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.OssAccessKeyId')" prop="key">
                        <el-input v-model="form.cfgUploadForm.key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.OssSecretAccess')" prop="access">
                        <el-input v-model="form.cfgUploadForm.access"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.OssBucket')" prop="bucket">
                        <el-input v-model="form.cfgUploadForm.bucket"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.OssEndpoint')" prop="endpoint">
                        <el-input v-model="form.cfgUploadForm.endpoint"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.cdn')" prop="cdn">
                        <el-input v-model="form.cfgUploadForm.cdn"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.upload.ssl')" prop="ssl">
                        <el-switch v-model="form.cfgUploadForm.ssl" inline-prompt :active-text="$t('btn.yes')" :inactive-text="$t('btn.no')" />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" :loading="loading" @click="onSubmit('cfgUploadForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgUploadForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <!-- 地图配置 -->
            <el-tab-pane :label="$t('config.configAmap')" name="cfgAmapForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgAmapForm" ref="cfgAmapForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.amap.key')" prop="key">
                        <el-input v-model="form.cfgAmapForm.key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.amap.jsapi')" prop="jsapi">
                        <el-input v-model="form.cfgAmapForm.jsapi"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.amap.security')" prop="security">
                        <el-input v-model="form.cfgAmapForm.security"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" :loading="loading" @click="onSubmit('cfgAmapForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgAmapForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <!-- 快宝配置 -->
            <el-tab-pane :label="$t('config.configKuaibao')" name="cfgKuaiBaoForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgKuaiBaoForm" ref="cfgKuaiBaoForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.kuaibao.appid')" prop="appid">
                        <el-input v-model="form.cfgKuaiBaoForm.appid"  /> <el-tag style="margin-top:10px" type="info">{{$t('config.configKuaibaoDesc')}}</el-tag>
                    </el-form-item>
                    <el-form-item :label="$t('config.kuaibao.appkey')" prop="appkey">
                        <el-input v-model="form.cfgKuaiBaoForm.appkey"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.kuaibao.agentId')" prop="agent_id">
                        <el-input v-model="form.cfgKuaiBaoForm.agent_id"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" :loading="loading" @click="onSubmit('cfgKuaiBaoForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgKuaiBaoForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <!-- 定时任务配置 -->
            <el-tab-pane :label="$t('config.configTask')" name="cfgTaskForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgTaskForm" ref="cfgTaskForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.task.cancel')" prop="cancel">
                        <el-input type="number" v-model="form.cfgTaskForm.cancel"  >
                            <template #suffix>{{$t('config.task.day')}}</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item :label="$t('config.task.confirm')" prop="confirm">
                        <el-input type="number" v-model="form.cfgTaskForm.confirm"  >
                            <template #suffix>{{$t('config.task.day')}}</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item :label="$t('config.task.settlement')" prop="settlement">
                        <el-input type="number" v-model="form.cfgTaskForm.settlement"  >
                            <template #suffix>{{$t('config.task.day')}}</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" :loading="loading" @click="onSubmit('cfgTaskForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgTaskForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import {getToken} from '@/plugins/config'
import { Plus } from '@element-plus/icons'
export default {
    components:{Plus},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            cfgForm:{},
            cfgUploadForm:{},
            cfgAmapForm:{},
            cfgKuaiBaoForm:{},
            cfgTaskForm:{}
        })
        const tabsIndex = ref('cfgForm')
        const loadConfig = async (e) => {
            if(e == 'cfgForm'){
                let names = ['web_name','index_name','keyword','description','logo','tel','mobile','email','icp','close_status','close_reason']
                form.cfgForm = await proxy.R.get('/Admin/configs',{name:names})
            }

            if(e == 'cfgUploadForm'){
                let names = 'upload';
                form.cfgUploadForm = await proxy.R.get('/Admin/configs',{name:names})
            }

            if(e == 'cfgAmapForm'){
                let names = 'amap';
                form.cfgAmapForm = await proxy.R.get('/Admin/configs',{name:names})
            }

            if(e == 'cfgKuaiBaoForm'){
                let names = 'kuaibao';
                form.cfgKuaiBaoForm = await proxy.R.get('/Admin/configs',{name:names})
            }
            
            if(e == 'cfgTaskForm'){
                let names = 'task';
                form.cfgTaskForm = await proxy.R.get('/Admin/configs',{name:names})
            }
        }

        // 切换
        const handleClick = (e)=>{
            loadConfig(e.paneName)
        }

        loadConfig('cfgForm')
        
        const onSubmit = (e)=>{
            if(e == 'cfgForm'){
                proxy.$refs[e].validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',form.cfgForm).then(res=>{
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

            if(e == 'cfgUploadForm'){
                proxy.$refs[e].validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',{upload:form.cfgUploadForm}).then(res=>{
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

            if(e == 'cfgAmapForm'){
                proxy.$refs[e].validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',{amap:form.cfgAmapForm}).then(res=>{
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

            if(e == 'cfgTaskForm'){
                proxy.$refs[e].validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',{task:form.cfgTaskForm}).then(res=>{
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

            if(e == 'cfgKuaiBaoForm'){
                proxy.$refs[e].validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        proxy.R.put('/Admin/configs/update',{kuaibao:form.cfgKuaiBaoForm}).then(res=>{
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
        const handleAvatarSuccess = (e)=>{
            if(e.code != 200) return ElementPlus.ElMessage.error(e.msg)
            form.cfgForm.logo = e.data
        }

        const Token = getToken()
        return {loading,tabsIndex,form,handleClick,handleAvatarSuccess,onSubmit,Token}
    }
}
</script>

<style lang="scss" scope>
.qwcfg{
    .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 78px;
    height: 78px;
    }
    .avatar-uploader .el-upload:hover {
    border-color: #409eff;
    }
    .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 78px;
    height: 78px;
    text-align: center;
    }
    .avatar-uploader-icon svg {
    // margin-top: 26px; /* (178px - 28px) / 2 - 1px */
    }
    .avatar {
    width: 178px;
    height: 178px;
    display: block;
    }
}
</style>