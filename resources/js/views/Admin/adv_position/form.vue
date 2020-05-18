<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>广告位编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="广告位" prop="ap_name" :rules="[{required:true,message:'广告位不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.ap_name"></el-input></el-form-item>
                    <el-form-item label="宽度" prop="ap_width" :rules="[{required:true,message:'宽度不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.ap_width"></el-input></el-form-item>
                    <el-form-item label="高度" prop="ap_height" :rules="[{required:true,message:'高度不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.ap_height"></el-input></el-form-item>
                    <el-form-item label="状态" prop="ap_isuse">
                        <el-switch v-model="info.ap_isuse" :active-text="info.ap_isuse==1?'开启':'关闭'"></el-switch>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <el-button @click="resetForm('info')">重置</el-button>
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
          edit_id:0,
          info:{
              ap_isuse:true,
          },
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
        },
        submitForm:function(e){
            let _this = this;
            this.info.ap_isuse = this.info.ap_isuse?1:0;
            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addAdvPosition;
                    if(_this.edit_id>0){
                        url = _this.$api.editAdvPosition+_this.edit_id;
                    }
                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("编辑成功");
                            _this.$router.go(-1);
                        }else{
                            _this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_adv_position_info:function(){
            let _this = this;
            this.$get(this.$api.editAdvPosition+this.edit_id).then(function(res){
                res.data.ap_isuse = res.data.ap_isuse==1?true:false;
                _this.info = res.data;
            })

        },
       
    },
    created() {

        // 判断是否是编辑
        this.info.pid = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_adv_position_info();
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>