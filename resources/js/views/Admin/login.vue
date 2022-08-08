<template>
    <div class="admin_login">
        <div class="login_block">
            <div class="left_block"><img :src="require('@/assets/Admin/login/login_left_img.png').default" /></div>
            <div class="right_block">
                <div class="title">后台管理系统</div>
                <div class="sub_title">management system</div>
                <el-divider class="login_divder"></el-divider>
                <el-form ref="forms" class="login_form">
                    <el-form-item>
                        <el-input size="medium" :prefix-icon="User" v-model="data.form.username" placeholder="账号" />
                    </el-form-item>
                    <el-form-item>
                        <el-input size="medium" :prefix-icon="Key" v-model="data.form.password" @keyup.enter.native="data.onsubmit" placeholder="密码" show-password />
                    </el-form-item>
                    <el-form-item>
                        <el-button style="width:100%" size="medium" type="primary" @click="data.oncheck">登 录</el-button>
                    </el-form-item>
                    <el-form-item class="disclaimers"><el-checkbox v-model="data.disclaimers" ><span @click="$refs.agreementRef.openDialog" >请先阅读《网站协议》</span></el-checkbox></el-form-item>
                </el-form>
            </div>
        </div>

        <agreementView ref="agreementRef" ename="agreement" /> 
        <div id="captcha"></div>
    </div>
</template>
<script>
import { Key,User } from '@element-plus/icons'
import { useStore } from 'vuex'
import {reactive,onMounted,toRefs,getCurrentInstance} from "vue"
import { useRouter, useRoute } from 'vue-router'
import agreementView from "@/components/common/agreement"
import captcha from '@/plugins/captcha/captcha.js'
export default ({
    components:{agreementView},
    setup() {
        const {ctx,proxy} = getCurrentInstance()
        const store = useStore()
        const router = useRouter()
        const data = reactive({
            disclaimers:true,
            form:{},
            oncheck:async ()=>{
                let {disclaimers,form} = data
                if(!disclaimers){
                    return ElementPlus.ElMessage.error('请先阅读《网站协议》')
                }
                if(proxy.R.isEmpty(form.username) || proxy.R.isEmpty(form.password)) return proxy.$message.error('请输入账户密码')
                const cap = captcha({
                    el: document.getElementById('captcha'),
                    widht: 310,
                    height: 150,
                    onSuccess: async ()=> {
                        let loginData = await proxy.R.post('/captcha',form)
                        form.sign = loginData
                        form.provider = 'admins'
                        loginData = await proxy.R.post('/login',form)
                        loginData.routeUriIndex = store.state.load.routeUriIndex
                        // loginData.isTo = true // 是否跳转
                        loginData.path = '/Admin/login'
                        if(!loginData.code){
                            await store.commit('login/loginAfter',loginData) 
                            const routeUrl = await store.dispatch('load/loadRoute') 
                            window.location.href=routeUrl
                        }else{
                            setTimeout(()=>{
                                cap.closeCaptcha()
                            },500)
                        }
                    },
                    onFail() {
                        data.form.step = this.x
                        setTimeout(()=>{
                            this.closeCaptcha()
                        },500)
                    },
                    onRefresh() {
                        data.form.step = this.x
                    },
                })
            },
            onsubmit:async ()=>{
                form.provider = 'admins'
                let loginData = await proxy.R.post('/login',form)
                loginData.routeUriIndex = store.state.load.routeUriIndex
                // loginData.isTo = true // 是否跳转
                loginData.path = '/Admin/login'
                
                if(!loginData.code){
                    const routeUrl = await store.dispatch('load/loadRoute') 
                    window.location.href=routeUrl
                }
                // console.log(proxy.$i18n.locale)
            }
        })

        const isLogin = async ()=>{
            localStorage.setItem('setToken',localStorage.getItem('admin_token'))
            let loginData = await proxy.R.get('/is_login',{provider:'admins'})
            if(!proxy.R.isEmpty(loginData) && loginData.code != 500 && loginData>0){
                const routeUrl = await store.dispatch('load/loadRoute') 
                window.location.href=routeUrl
            }
        }

        onMounted(()=>{
            
        })
        
        isLogin()
     
        return {data,Key,User}
    },
})
</script>
<style lang="scss" scoped>
.admin_login{
    background: url("@/assets/Admin/login/login_bg.jpg") ;
    background-size: cover;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    .login_block{
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 12px 0 rgba(85, 85, 85, 0.1);
        height: 500px;
        width: 45%;
        display: flex;
        box-sizing: border-box;
        .left_block{
            box-sizing: border-box;
            padding: 20px 20px;
            flex: 1;
            background: linear-gradient(#f6faff, #ecf5ff);
            display: flex;
            align-items: center;
            justify-content: center;
            img{width: 100%;}
        }
        .right_block{
            flex: 1;
            box-sizing: border-box;
            padding: 0 0px;
            text-align: center;
            .title{
                margin-top: 80px;
                font-size: 35px;
            }
            .sub_title{
                font-size: 12px;
                color:#999;
                text-transform:Uppercase ;
                margin-top: 8px;
            }
            .login_form{
                width: 80%;
                margin:0 auto;
            }
            .login_divder{
                width: 80%;
                margin:0 auto;
                margin-top: 30px;
                margin-bottom: 30px;
            }
            .disclaimers{
                margin:0;
            }
            
        }
    }
}
@media  (max-width: 1200px) {
    .admin_login .login_block .left_block{
        display: none;
    }
}
@media (max-width: 767px) {
    .admin_login .login_block{
        width: 80%;
    }
}
</style>
<style>
.admin_login .el-input--medium .el-input__icon{
    line-height: 40px;
}
</style>