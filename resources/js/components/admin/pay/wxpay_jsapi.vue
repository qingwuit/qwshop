<template>
    <div class="qingwu">
        <el-form  label-width="100px" ref="info" :model="info">
            <el-form-item label-width="160px" label="APPID" prop="appid" :rules="[{required:true,message:'APPID不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.appid"></el-input></el-form-item>
            <el-form-item label-width="160px" label="APPSECRET" prop="appsecret" :rules="[{required:true,message:'APPSECRET不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.appsecret"></el-input></el-form-item>
            <el-form-item label-width="160px" label="MCH_ID" prop="mchid" :rules="[{required:true,message:'MCH_ID不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.mchid"></el-input></el-form-item>
            <el-form-item label-width="160px" label="KEY" prop="key" :rules="[{required:true,message:'KEY不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.key"></el-input></el-form-item>
            <el-form-item label-width="160px" label="notify_url" prop="notify_url" :rules="[{required:true,message:'通知地址不能为空',trigger: 'blur' }]"><el-input placeholder="请输入通知地址" v-model="info.notify_url"></el-input></el-form-item>
            <el-form-item label-width="160px" label="备注："><el-tag type="info">微信支付JSAPI/Native</el-tag></el-form-item>
            <el-form-item label-width="160px">
                <el-button type="primary" @click="submitForm('info')">提交</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    this.$post(this.$api.wxPayJsApiConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_wxpayjsapi_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_wxpayjsapi_config(){
            this.$get(this.$api.wxPayJsApiConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_wxpayjsapi_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>