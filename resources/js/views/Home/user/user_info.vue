<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            用户资料
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_form_main">
            <el-form  label-width="100px" ref="info" :model="info">
                <el-form-item label="昵称" prop="nickname" :rules="[{required:true,message:'昵称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.nickname"></el-input></el-form-item>
                <el-form-item label="头像" prop="avatar"><el-upload class="avatar-uploader" :action="$api.homeAvatar" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.avatar" :src="info.avatar" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload></el-form-item>
                <!-- <el-form-item label="手机" prop="phone" ><el-input placeholder="请输入内容" v-model="info.phone"></el-input></el-form-item>
                <el-form-item label="邮箱" prop="email" :rules="[{type:'email',message:'邮箱格式非法',trigger: 'blur' }]"><el-input type="email" placeholder="xxx@qq.com" v-model="info.email"></el-input></el-form-item> -->
                <el-form-item label="性别" prop="gender" class="red_radio">
                    <el-radio-group v-model="info.gender">
                        <!-- <el-radio label="2">未知</el-radio> -->
                        <el-radio :label="1">男</el-radio>
                        <el-radio :label="0">女</el-radio>
                    </el-radio-group>
                </el-form-item>
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
              nickname:'',
              avatar:'',
              email:'',
              phone:'',
              gender:1,
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
                    _this.edit_user_info();
                }
            });
        },
        handleAvatarSuccess(res) {
            this.info.avatar = res.data;
            this.$forceUpdate();
        },
        get_user_info:function(){
            this.$get(this.$api.homeEditUserInfo).then(res=>{
                this.info = res.data;
            });
        },
        edit_user_info:function(){
            this.$post(this.$api.homeEditUserInfo,this.info).then(res=>{
                this.$message.success('修改成功');
                this.get_user_info();
            });
        }
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        this.get_user_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>