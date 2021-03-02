<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            阿里短信
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="其他配置">
                    <a-button @click="$router.push('/Admin/sms_signs/index')" style="margin-right:15px"><a-font type="iconshuxie" />签名配置</a-button>
                    <a-button @click="$router.push('/Admin/sms_logs/index')"><a-font type="iconemail" />短信日志</a-button>
                </a-form-model-item>
                <a-form-model-item label="KEY">
                    <a-input v-model="info.key"></a-input>
                </a-form-model-item>
                <a-form-model-item label="SECRET">
                    <a-input v-model="info.secret"></a-input>
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
            let info = JSON.stringify(this.info);
            this.$post(this.$api.adminConfigs,{alisms:info}).then(res=>{
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
                this.info = res.data.alisms;
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