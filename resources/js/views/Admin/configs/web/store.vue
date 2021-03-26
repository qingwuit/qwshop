<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="商品审核">
            <a-switch  v-model="info.goods_verify" />
        </a-form-model-item>
        <a-form-model-item label="提现手续费">
            <a-input type="number" v-model="info.cash_rate" suffix="%"></a-input>
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
            this.info.goods_verify = this.info.goods_verify?1:0;
            this.$post(this.$api.adminConfigs,this.info).then(res=>{
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
                res.data.goods_verify = res.data.goods_verify==0?false:true;
                let data = {
                    goods_verify:res.data.goods_verify,
                    cash_rate:res.data.cash_rate
                }
                this.info = data
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
