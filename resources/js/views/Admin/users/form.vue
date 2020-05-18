<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>用户编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="用户名" prop="username" :rules="[{required:true,message:'用户名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.username"></el-input></el-form-item>
                    <el-form-item v-if="edit_id==0" label="密码" prop="password" :rules="[{required:true,message:'密码不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.password"></el-input></el-form-item>
                    <el-form-item v-else label="密码" prop="password" ><el-input placeholder="不修改则不填" v-model="info.password"></el-input></el-form-item>
                    <el-form-item label="角色" class="width_auto">
                        <el-checkbox style="margin-bottom:10px;" v-model="roles_list" v-for="(v,k) in roles" :key="k" :label="v.id" border>{{v.name}}</el-checkbox>
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
          info:{},
          roles_list:[],
          roles:[],
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
                    let url = _this.$api.addUsers;
                    if(_this.edit_id>0){
                        url = _this.$api.editUsers+_this.edit_id;
                        delete _this.info.roles
                    }
                    _this.info['roles_list'] = _this.roles_list;

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
        get_users_info:function(){
            let _this = this;
            this.$get(this.$api.editUsers+this.edit_id).then(function(res){
                _this.info = res.data['info'];

                if(res.data.info.roles.length>0){
                    _this.roles_list = [];
                    res.data.info.roles.forEach(roleRes => {
                        _this.roles_list.push(roleRes.id);
                    });
                }
                _this.info.password = '';
                _this.roles = res.data['roles_list'];
            })

        },
        get_add_roles_info:function(){
            let _this = this;
            this.$get(this.$api.addUsers).then(function(res){
                _this.roles = res.data['roles_list'];
            })
        }
    },
    created() {

        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }else{
            this.get_add_roles_info();
        }

        if(this.edit_id>0){
            this.get_users_info();
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>