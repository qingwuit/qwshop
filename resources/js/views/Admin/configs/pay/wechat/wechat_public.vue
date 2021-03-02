<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="APPID">
            <a-input v-model="wechat.app_id"></a-input>
        </a-form-model-item>
        <a-form-model-item label="APPSECRET">
            <a-input v-model="wechat.app_secret"></a-input>
        </a-form-model-item>
        <a-form-model-item label="商户ID(MCH_ID)">
            <a-input v-model="wechat.mch_id"></a-input>
        </a-form-model-item>
        <a-form-model-item label="KEY">
            <a-input v-model="wechat.key"></a-input>
        </a-form-model-item>
        <a-form-model-item label="异步回调(notify_url)">
            <a-input v-model="wechat.notify_url"></a-input>
        </a-form-model-item>
        <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
            <a-button type="primary" @click="handleSubmit">提交</a-button>
        </a-form-model-item>
    </a-form-model>
</template>

<script>
export default {
    data() {
      return {
          info:{},
          wechat:{},
      };
    },
    methods: {
        handleSubmit(){
            this.info.wechat_public = this.wechat;
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{wechat_pay:info}).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg)
                    return this.onload();
                }else{
                    return this.$message.error(res.msg)
                }
            })
        },
        get_info(){
            this.$get(this.$api.adminConfigs).then(res=>{
                this.info = res.data.wechat_pay;
                this.wechat = res.data.wechat_pay.wechat_public;
            })
        },
        // 获取列表
        onload(){
            this.get_info();
        },
    },
    created() {
        this.onload();
    },
};
</script>
