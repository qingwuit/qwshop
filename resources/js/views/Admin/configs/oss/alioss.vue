<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            上传配置
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="存储方式(默认本地)">
                    <a-switch checked-children="OSS" un-checked-children="Local" v-model="info.status" />
                </a-form-model-item>
                <a-form-model-item label="OssAccessKeyId">
                    <a-input v-model="info.access_id"></a-input>
                </a-form-model-item>
                <a-form-model-item label="OssSecretAccess">
                    <a-input v-model="info.access_key"></a-input>
                </a-form-model-item>
                <a-form-model-item label="OssBucket">
                    <a-input v-model="info.bucket"></a-input>
                </a-form-model-item>
                <a-form-model-item label="OssEndpoint">
                    <a-input v-model="info.endpoint"></a-input>
                </a-form-model-item>
                <a-form-model-item label="CDN域名">
                    <a-input v-model="info.cdnDomain"></a-input>
                </a-form-model-item>
                <a-form-model-item label="是否SSL">
                    <a-switch  v-model="info.ssl" />
                </a-form-model-item>
                <a-form-model-item label="内部节点">
                    <a-switch  v-model="info.isCName" />
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
            this.$post(this.$api.adminConfigs,{alioss:info}).then(res=>{
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
                this.info = res.data.alioss;
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