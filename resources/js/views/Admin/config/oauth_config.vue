<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>OAuth配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="160px" label="appId" prop="appid" :rules="[{required:true,message:'appId不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.appid"></el-input></el-form-item>
                    <el-form-item label-width="160px" label="AppSecret" prop="app_secret" :rules="[{required:true,message:'AppSecret不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.app_secret"></el-input></el-form-item>
                    <el-form-item label-width="160px" label="备注："><el-tag type="info">第三方登录 Oauth  上面是微信的</el-tag></el-form-item>
                    <el-form-item label-width="160px">
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              appid:'',
              app_secret:'',
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    console.log(this.$api.oauthConfig);
                    this.$post(this.$api.oauthConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_oauth_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_oauth_config(){
            this.$get(this.$api.oauthConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_oauth_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>