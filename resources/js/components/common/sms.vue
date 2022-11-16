<template>
    <div class="sms_block">
        <el-row :gutter="10">
            <el-col :span="16"><el-input v-model="data.code" /></el-col>
            <el-col :span="8"><div :class="data.math<=0?'code_btn':'code_btn2'" @click="sendSms">{{data.code_text}}</div></el-col>
        </el-row>
        
    </div>
</template>

<script>
import {reactive,watch,onBeforeUnmount,getCurrentInstance} from "vue"
export default {
    props:['codeName','phone','code'],
    setup(props,{emit}) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            code_text:'',
            timeObj:null,
            math:0,
            code:props.code,
        })

        data.code_text = proxy.$t('sms.sendCode')

        // 发送短信
        const sendSms = ()=>{
            if(props.phone == ''){
                return proxy.$message.error(proxy.$t('sms.phoneEmpty'));
            }
            if(data.math>0){
                return proxy.$message.error(proxy.$t('manySend'));
            }
            proxy.R.post('/sms',{phone:props.phone,name:props.codeName}).then(res=>{
                if(!res.code){
                    if(data.timeObj != null) clearInterval(data.timeObj)
                    data.math = 60
                    data.timeObj = setInterval(()=>{
                        data.math--
                        data.code_text = data.math+'s'
                        if(data.math<=0){
                            data.code_text = proxy.$t('sms.sendCode')
                            if(data.timeObj != null) clearInterval(data.timeObj)
                        }
                    },1000)
                    return proxy.$message.success(proxy.$t('sms.sendSuc'))
                }
            })
        }

        watch(()=>data.code,(e)=>{
            emit('update:code',e)
        },{deep:true})

        onBeforeUnmount(()=>{
            if(data.timeObj != null) clearInterval(data.timeObj)
        })

        return {data,sendSms}
    }
}
</script>

<style lang="scss" scoped>
.code_btn{
    width: 100%;
    border:1px solid var(--el-color-primary);
    text-align: center;
    box-sizing: border-box;
    color:var(--el-color-primary);
    height: calc(100% - 2px);
    margin-top: 1px;
    line-height: 25px;
    cursor: pointer;
    border-radius: 3px;
    &:hover{
        background: var(--el-color-primary);
        color:#fff;
        border:none;
    }
}
.code_btn2{
    width: 100%;
    border:1px solid #aaa;
    text-align: center;
    box-sizing: border-box;
    color:#999;
    height: calc(100% - 2px);
    margin-top: 1px;
    line-height: 25px;
    border-radius: 3px;
    background: #efefef;
}
</style>