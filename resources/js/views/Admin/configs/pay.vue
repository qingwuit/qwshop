<template>
    <div class="qwcfg">
        <el-tabs tab-position="left" style="height:500px" @tab-click="handleClick" v-model="tabsIndex">
            <el-tab-pane :label="$t('config.configWechatPayWap')" name="wechatwap">
                <el-form style="width:60%;margin-top:8px;" :model="form.wechatwap" ref="wechatwap" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="mp_app_id">
                        <el-input v-model="form.wechatwap.mp_app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_id')" prop="mch_id">
                        <el-input v-model="form.wechatwap.mch_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.secret')" prop="mch_secret_key">
                        <el-input v-model="form.wechatwap.mch_secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_secret_cert')" prop="mch_secret_cert">
                        <q-input v-model:formData="form.wechatwap.mch_secret_cert" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_public_cert_path')" prop="mch_public_cert_path">
                        <q-input v-model:formData="form.wechatwap.mch_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.wechatwap.notify_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('wechatwap')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['wechatwap'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configWechatPayPublic')" name="wechatmp">
                <el-form style="width:60%;margin-top:8px;" :model="form.wechatmp" ref="wechatmp" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="mp_app_id">
                        <el-input v-model="form.wechatmp.mp_app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret')" prop="secret_key">
                        <el-input v-model="form.wechatmp.secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_id')" prop="mch_id">
                        <el-input v-model="form.wechatmp.mch_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.secret')" prop="mch_secret_key">
                        <el-input v-model="form.wechatmp.mch_secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_secret_cert')" prop="mch_secret_cert">
                        <q-input v-model:formData="form.wechatmp.mch_secret_cert" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_public_cert_path')" prop="mch_public_cert_path">
                        <q-input v-model:formData="form.wechatmp.mch_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.wechatmp.notify_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('wechatmp')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['wechatmp'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configWechatPayMini')" name="wechatmini">
                <el-form style="width:60%;margin-top:8px;" :model="form.wechatmini" ref="wechatmini" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="mini_app_id">
                        <el-input v-model="form.wechatmini.mini_app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret')" prop="secret_key">
                        <el-input v-model="form.wechatmini.secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_id')" prop="mch_id">
                        <el-input v-model="form.wechatmini.mch_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.secret')" prop="mch_secret_key">
                        <el-input v-model="form.wechatmini.mch_secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_secret_cert')" prop="mch_secret_cert">
                        <q-input v-model:formData="form.wechatmini.mch_secret_cert" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_public_cert_path')" prop="mch_public_cert_path">
                        <q-input v-model:formData="form.wechatmini.mch_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.wechatmini.notify_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('wechatmini')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['wechatmini'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configWechatPayApp')" name="wechatapp">
                <el-form style="width:60%;margin-top:8px;" :model="form.wechatapp" ref="wechatapp" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="app_id">
                        <el-input v-model="form.wechatapp.app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_id')" prop="mch_id">
                        <el-input v-model="form.wechatapp.mch_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.secret')" prop="mch_secret_key">
                        <el-input v-model="form.wechatapp.mch_secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_secret_cert')" prop="mch_secret_cert">
                        <q-input v-model:formData="form.wechatapp.mch_secret_cert" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_public_cert_path')" prop="mch_public_cert_path">
                        <q-input v-model:formData="form.wechatapp.mch_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.wechatapp.notify_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('wechatapp')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['wechatapp'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configWechatPayScan')" name="wechatscan">
                <el-form style="width:60%;margin-top:8px;" :model="form.wechatscan" ref="wechatscan" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="mp_app_id">
                        <el-input v-model="form.wechatscan.mp_app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_id')" prop="mch_id">
                        <el-input v-model="form.wechatscan.mch_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.secret')" prop="mch_secret_key">
                        <el-input v-model="form.wechatscan.mch_secret_key"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_secret_cert')" prop="mch_secret_cert">
                        <q-input v-model:formData="form.wechatscan.mch_secret_cert" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.mch_public_cert_path')" prop="mch_public_cert_path">
                        <q-input v-model:formData="form.wechatscan.mch_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.wechatscan.notify_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('wechatscan')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['wechatscan'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configAliWap')" name="alipaywap">
                <el-form style="width:60%;margin-top:8px;" :model="form.alipaywap" ref="alipaywap" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="app_id">
                        <el-input v-model="form.alipaywap.app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret_cert')" prop="app_secret_cert">
                        <el-input type="textarea" v-model="form.alipaywap.app_secret_cert"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_public_cert_path')" prop="app_public_cert_path">
                        <q-input v-model:formData="form.alipaywap.app_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_public_cert_path')" prop="alipay_public_cert_path">
                        <q-input v-model:formData="form.alipaywap.alipay_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_root_cert_path')" prop="alipay_root_cert_path">
                        <q-input v-model:formData="form.alipaywap.alipay_root_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.alipaywap.notify_url"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.return_url')" prop="return_url">
                        <el-input v-model="form.alipaywap.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('alipaywap')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['alipaywap'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configAliApp')" name="alipayapp">
                <el-form style="width:60%;margin-top:8px;" :model="form.alipayapp" ref="alipayapp" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="app_id">
                        <el-input v-model="form.alipayapp.app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret_cert')" prop="app_secret_cert">
                        <el-input type="textarea" v-model="form.alipayapp.app_secret_cert"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_public_cert_path')" prop="app_public_cert_path">
                        <q-input v-model:formData="form.alipayapp.app_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_public_cert_path')" prop="alipay_public_cert_path">
                        <q-input v-model:formData="form.alipayapp.alipay_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_root_cert_path')" prop="alipay_root_cert_path">
                        <q-input v-model:formData="form.alipayapp.alipay_root_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.alipayapp.notify_url"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.return_url')" prop="return_url">
                        <el-input v-model="form.alipayapp.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('alipayapp')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['alipayapp'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configAliScan')" name="alipayscan">
                <el-form style="width:60%;margin-top:8px;" :model="form.alipayscan" ref="alipayscan" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="app_id">
                        <el-input v-model="form.alipayscan.app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret_cert')" prop="app_secret_cert">
                        <el-input type="textarea" v-model="form.alipayscan.app_secret_cert"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_public_cert_path')" prop="app_public_cert_path">
                        <q-input v-model:formData="form.alipayscan.app_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_public_cert_path')" prop="alipay_public_cert_path">
                        <q-input v-model:formData="form.alipayscan.alipay_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_root_cert_path')" prop="alipay_root_cert_path">
                        <q-input v-model:formData="form.alipayscan.alipay_root_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.alipayscan.notify_url"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.return_url')" prop="return_url">
                        <el-input v-model="form.alipayscan.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('alipayscan')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['alipayscan'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configAliMini')" name="alipaymini">
                <el-form style="width:60%;margin-top:8px;" :model="form.alipaymini" ref="alipaymini" label-position="right" label-width="180px">
                    <el-form-item :label="$t('config.configPay.appid')" prop="app_id">
                        <el-input v-model="form.alipaymini.app_id"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_secret_cert')" prop="app_secret_cert">
                        <el-input type="textarea" v-model="form.alipaymini.app_secret_cert"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.app_public_cert_path')" prop="app_public_cert_path">
                        <q-input v-model:formData="form.alipaymini.app_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_public_cert_path')" prop="alipay_public_cert_path">
                        <q-input v-model:formData="form.alipaymini.alipay_public_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.alipay_root_cert_path')" prop="alipay_root_cert_path">
                        <q-input v-model:formData="form.alipaymini.alipay_root_cert_path" :params="{type:'file',value:'crt' }" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.notify_url')" prop="notify_url">
                        <el-input v-model="form.alipaymini.notify_url"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configPay.return_url')" prop="return_url">
                        <el-input v-model="form.alipaymini.return_url"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('alipaymini')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['alipaymini'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            
        </el-tabs>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import {getToken} from '@/plugins/config'
import qInput from '@/components/common/input'
export default {
    components:{qInput},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            wechatwap:{},
            wechatmp:{},
            wechatmini:{},
            wechatapp:{},
            wechatscan:{},
            alipaywap:{},
            alipayapp:{},
            alipayscan:{},
            alipaymini:{},
        })
        const tabsIndex = ref('wechatwap')
        const loadConfig = async (e) => {
            let names = 'pay';
            form[e] = await proxy.R.get('/Admin/configs',{name:names,many_name:e,many:true})
        }

        // 切换
        const handleClick = (e)=>{
            loadConfig(e.paneName)
        }

        loadConfig('wechatwap')
        
        const onSubmit = (e)=>{
            proxy.$refs[e].validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/Admin/configs/update',{pay:form[e],many:true,many_name:e}).then(res=>{
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