<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="APPID">
            <a-input v-model="ali.app_id"></a-input>
        </a-form-model-item>
        <a-form-model-item label="公钥(public_key)">
            <a-input v-model="ali.public_key"></a-input>
        </a-form-model-item>
        <a-form-model-item label="私钥(private_key)">
            <a-input v-model="ali.private_key"></a-input>
        </a-form-model-item>
        <a-form-model-item label="异步回调(notify_url)">
            <a-input v-model="ali.notify_url"></a-input>
        </a-form-model-item>
        <a-form-model-item label="同步回调(return_url)">
            <a-input v-model="ali.return_url"></a-input>
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
          ali:{},
      };
    },
    methods: {
        handleSubmit(){
            this.info.ali_mini = this.ali;
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{ali_pay:info}).then(res=>{
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
                this.info = res.data.ali_pay;
                this.ali = res.data.ali_pay.ali_mini;
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
