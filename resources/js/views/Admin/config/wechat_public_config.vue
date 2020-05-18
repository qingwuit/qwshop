<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>微信公众号</div>
                </div>
                <div class="admin_main_block_right">
                    <div><el-button type="primary" icon="el-icon-menu" >微信菜单</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="130px" label="URL(服务器地址)" prop="url" :rules="[{required:true,message:'服务器地址不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.url"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="Token" prop="token" :rules="[{required:true,message:'Token不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.token"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="公众号名称" prop="name" :rules="[{required:true,message:'公众号名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="AppID" prop="appid" :rules="[{required:true,message:'AppID不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.appid"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="AppSecret" prop="secret" :rules="[{required:true,message:'AppSecret不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.secret"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="小程序AppID" prop="mini_appid" ><el-input placeholder="请输入内容" v-model="info.mini_appid"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="小程序AppSecret" prop="mini_secret" ><el-input placeholder="请输入内容" v-model="info.mini_secret"></el-input></el-form-item>
                    <el-form-item label-width="130px" >
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
                    this.$post(this.$api.wechatPublicConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_wecaht_public_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_wecaht_public_config(){
            this.$get(this.$api.wechatPublicConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_wecaht_public_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>