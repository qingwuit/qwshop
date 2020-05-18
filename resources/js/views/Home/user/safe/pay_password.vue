<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title">
            支付密码编辑
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_form_main">
            <el-form  label-width="100px" ref="info" :model="info">
                <el-form-item label="旧密码" prop="old_pay_password" :rules="[{required:true,message:'旧密码不能为空',trigger: 'blur' },{len:6,message:'密码为6位数字',trigger: 'blur' }]"><el-input placeholder="请输入内容" type="password" v-model="info.old_pay_password"></el-input></el-form-item>
                <el-form-item label="新密码" prop="pay_password" :rules="[{required:true,message:'新密码不能为空',trigger: 'blur' },{len:6,message:'密码为6位数字',trigger: 'blur' }]"><el-input placeholder="请输入内容" type="password" v-model="info.pay_password"></el-input></el-form-item>
                <el-form-item label="确认密码" prop="repay_password" :rules="[{required:true,message:'确认密码不能为空',trigger: 'blur' },{len:6,message:'密码为6位数字',trigger: 'blur' }]"><el-input placeholder="请输入内容" type="password" v-model="info.repay_password"></el-input></el-form-item>
                <el-form-item>
                    <el-button type="danger" @click="submitForm('info')">提交</el-button>
                    <!-- <el-button @click="resetForm('info')">重置</el-button> -->
                </el-form-item>
            </el-form>
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
              old_pay_password:'',
              pay_password:'',
              repay_password:'',
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    if(_this.info.pay_password != _this.info.repay_password){
                        return _this.$message.error('两次密码不一致');
                    }
                    _this.edit_user_info();
                }
            });
        },
        
        
        edit_user_info:function(){
            this.$post(this.$api.homeEditUserInfo,this.info).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }
                this.$message.success('修改成功');
                this.$router.go(-1);
                
            });
        }
    },
    created() {
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>