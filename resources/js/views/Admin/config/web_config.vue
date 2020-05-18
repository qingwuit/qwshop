<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>站点配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="站点名称" prop="web_name" :rules="[{required:true,message:'站点名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.web_name"></el-input></el-form-item>
                    <el-form-item label="域名" prop="web_url" :rules="[{required:true,message:'站点链接不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.web_url"></el-input></el-form-item>
                    <el-form-item label="站点LOGO" prop="logo"><el-upload class="avatar-uploader" :action="$api.logoUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.logo" :src="info.logo" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
                    <el-form-item label="网站关键字" prop="keywords" :rules="[{required:true,message:'关键字不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" maxlength="200" show-word-limit v-model="info.keywords"></el-input></el-form-item>
                    <el-form-item label="网站描述" prop="description" :rules="[{required:true,message:'描述不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" maxlength="200" show-word-limit v-model="info.description"></el-input></el-form-item>
                    <el-form-item label="联系电话" prop="mobile"><el-input placeholder="请输入内容" v-model="info.mobile"></el-input></el-form-item>
                    <el-form-item label="邮件地址" prop="email"><el-input placeholder="请输入内容" v-model="info.email"></el-input></el-form-item>
                    <el-form-item label="备案号" prop="icp"><el-input placeholder="请输入内容" v-model="info.icp"></el-input></el-form-item>
                    <el-form-item label="提现手续" prop="cash_rate"><el-input placeholder="请输入内容" v-model="info.cash_rate"><template slot="append">%</template></el-input></el-form-item>
                    <el-form-item label="商品审核" prop="goods_verify"><el-switch v-model="info.goods_verify" :active-text="info.goods_verify?'开启':'关闭'"></el-switch></el-form-item>
                    <el-form-item label="网站状态" prop="web_status"><el-switch v-model="info.web_status" :active-text="info.web_status?'开启':'关闭'"></el-switch></el-form-item>
                    <el-form-item label="网址关闭原因" prop="web_close_info"><el-input type="textarea" placeholder="网站关闭原因" maxlength="200" show-word-limit v-model="info.web_close_info"></el-input></el-form-item>
                    <el-form-item>
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
          upload_headers:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    this.info.web_status = this.info.web_status?1:0;
                    this.info.goods_verify = this.info.goods_verify?1:0;
                    this.$post(this.$api.webConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_web_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        handleAvatarSuccess(res) {
            this.info.logo = res.data;
            this.$forceUpdate();
        },
        get_web_config(){
            this.$get(this.$api.webConfig).then(res=>{
                this.info  = res.data;
                this.info.web_status = this.info.web_status==1?true:false;
                this.info.goods_verify = this.info.goods_verify==1?true:false;
            });
        },
        
    },
    created() {
        this.get_web_config();
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>