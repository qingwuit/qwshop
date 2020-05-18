<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            用户资料
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_form_main">
            <el-form  label-width="100px" ref="info" :model="info">
                <el-form-item label="真名" prop="real_name" :rules="[{required:true,message:'真名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.real_name"></el-input></el-form-item>
                <el-form-item label="身份证号" prop="card_no" :rules="[{required:true,message:'身份证号不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.card_no"></el-input></el-form-item>
                <el-form-item label="身份证（头像）" prop="card_top"  :rules="[{required:true,message:'身份证（头像）不能为空',trigger: 'blur' }]"><el-upload class="avatar-uploader" :action="$api.homeUserCard" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.card_top" :src="info.card_top" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload></el-form-item>
                <el-form-item label="身份证（国徽）" prop="card_bottom"  :rules="[{required:true,message:'身份证（国徽）不能为空',trigger: 'blur' }]"><el-upload class="avatar-uploader" :action="$api.homeUserCard" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess2" >
                        <img v-if="info.card_bottom" :src="info.card_bottom" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload></el-form-item>
                <el-form-item label="银行卡号" prop="bank_card" :rules="[{required:true,message:'银行卡号不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.bank_card"></el-input></el-form-item>
                <el-form-item label="银行名" prop="bank_name" :rules="[{required:true,message:'银行名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.bank_name"></el-input></el-form-item>
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
              real_name:'',
              card_no:'',
              card_top:'',
              card_bottom:'',
              bank_card:'',
              bank_name:'',
          },
          upload_headers:{},
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
                    _this.edit_user_check_info();
                }
            });
        },
        handleAvatarSuccess(res) {
            this.info.card_top = res.data;
            this.$forceUpdate();
        },
        handleAvatarSuccess2(res) {
            this.info.card_bottom = res.data;
            this.$forceUpdate();
        },
        get_user_check_info:function(){
            this.$get(this.$api.homeEditUserCheckInfo).then(res=>{
                if(this.$isEmpty(res.data)){
                   return; 
                }
                this.info = res.data;
            });
        },
        edit_user_check_info:function(){
            this.$post(this.$api.homeEditUserCheckInfo,this.info).then(res=>{
                this.$message.success('修改成功');
                this.get_user_check_info();
            });
        }
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        this.get_user_check_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>