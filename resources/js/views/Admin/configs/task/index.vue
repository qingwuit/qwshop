<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            自动任务
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="完成订单">
                    <a-input type="number" v-model="info.confirm" suffix="天"></a-input>
                </a-form-model-item>
                <a-form-model-item label="取消订单">
                    <a-input type="number" v-model="info.cancel" suffix="天"></a-input>
                </a-form-model-item>
                <a-form-model-item label="结算订单">
                    <a-input type="number" v-model="info.settlement" suffix="天"></a-input>
                </a-form-model-item>

                <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>
        </div>
    </div>
</template>

<script>

export default {
    data() {
      return {
          info:{},
      };
    },
    methods: {
        handleSubmit(){
            console.log(this.info)
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{task:info}).then(res=>{
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
                this.info = res.data.task;
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