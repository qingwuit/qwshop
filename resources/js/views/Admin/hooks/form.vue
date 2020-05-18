<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>接口编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="钩子名" prop="is_type">
                        <el-select v-model="info.is_type" placeholder="请选择类型">
                            <el-option label="后台" :value="0"></el-option>
                            <el-option label="商家" :value="1"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="接口名" prop="name" :rules="[{required:true,message:'接口名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="控制器" prop="controller_action" :rules="[{required:true,message:'控制器不能为空',trigger: 'blur' }]"><el-input placeholder="\Admin\IndexController@get_statistics" v-model="info.controller_action"></el-input></el-form-item>
                    <el-form-item label="接口" prop="apis" :rules="[{required:true,message:'接口不能为空',trigger: 'blur' }]"><el-input placeholder="/index/get_statistics" v-model="info.apis"></el-input></el-form-item>
                    <el-form-item label="描述" prop="content" :rules="[{required:true,message:'描述不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" maxlength="30" show-word-limit v-model="info.content"></el-input></el-form-item>
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
              is_type:0,
          },
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

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addHook;
                    if(_this.edit_id>0){
                        url = _this.$api.editHook+_this.edit_id;
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
        get_hooks_info:function(){
            let _this = this;
            this.$get(this.$api.editHook+this.edit_id).then(function(res){
                _this.info = res.data;
            })

        }
    },
    created() {

        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }
        if(this.edit_id>0){
            this.get_hooks_info();
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>