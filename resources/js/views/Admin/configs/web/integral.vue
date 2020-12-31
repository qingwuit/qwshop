<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="类型">
            <a-tag color="blue">登录送积分</a-tag>
        </a-form-model-item>
        <a-form-model-item label="是否开启">
            <a-switch  v-model="info.login.status" />
        </a-form-model-item>
        <a-form-model-item label="积分">
            <a-input type="number" v-model="info.login.integral" suffix="积分"></a-input>
        </a-form-model-item>

        <a-form-model-item label="类型">
            <a-tag color="blue">注册送积分</a-tag>
        </a-form-model-item>
        <a-form-model-item label="是否开启">
            <a-switch  v-model="info.register.status" />
        </a-form-model-item>
        <a-form-model-item label="积分">
            <a-input type="number" v-model="info.register.integral" suffix="积分"></a-input>
        </a-form-model-item>

        <a-form-model-item label="类型">
            <a-tag color="blue">邀请送积分</a-tag>
        </a-form-model-item>
        <a-form-model-item label="是否开启">
            <a-switch  v-model="info.inviter.status" />
        </a-form-model-item>
        <a-form-model-item label="积分">
            <a-input type="number" v-model="info.inviter.integral" suffix="积分"></a-input>
        </a-form-model-item>

        <a-form-model-item label="类型">
            <a-tag color="blue">支付订单送积分</a-tag>
        </a-form-model-item>
        <a-form-model-item label="是否开启">
            <a-switch  v-model="info.order.status" />
        </a-form-model-item>
        <a-form-model-item label="积分">
            <a-input type="number" v-model="info.order.integral" suffix="积分"></a-input>
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
          info:{
          },
      };
    },
    methods: {
        handleSubmit(){
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{integral:info}).then(res=>{
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
                this.info = res.data.integral;
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
