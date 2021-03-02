<template>
    
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="ClientID">
            <a-input v-model="weixinweb.client_id" ></a-input>
        </a-form-model-item>
        <a-form-model-item label="密钥">
            <a-input v-model="weixinweb.client_secret" ></a-input>
        </a-form-model-item>
        <a-form-model-item label="回调地址">
            <a-input v-model="weixinweb.redirect" ></a-input>
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
          weixinweb:{
              client_id:'',
              client_secret:'',
              redirect:'',
          },
      };
    },
    methods: {
        handleSubmit(){
            this.info.weixinweb = this.weixinweb
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{oauth:info}).then(res=>{
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
                this.info = res.data.oauth;
                this.weixinweb = res.data.oauth.weixinweb;
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
<style lang="scss" scoped>

</style>