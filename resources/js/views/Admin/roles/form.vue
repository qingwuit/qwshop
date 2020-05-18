<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>角色编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="角色名" prop="name" :rules="[{required:true,message:'角色名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="描述" prop="content" :rules="[{required:true,message:'描述不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" maxlength="30" show-word-limit v-model="info.content"></el-input></el-form-item>
                    <el-form-item class="width_auto"><el-divider content-position="left">菜单</el-divider></el-form-item>
                    <el-form-item class="width_auto">
                        <el-checkbox-group v-model="menus" >
                            <div class="menus_list" v-for="(v,k) in info.menus_list" :key="k">
                                <el-checkbox  :label="v.id" border>{{v.name}}</el-checkbox>
                                <!-- <el-checkbox  :label="v.id+',index'">列表</el-checkbox>
                                <el-checkbox  :label="v.id+',add'">添加</el-checkbox>
                                <el-checkbox  :label="v.id+',edit'">编辑</el-checkbox>
                                <el-checkbox  :label="v.id+',del'">删除</el-checkbox> -->
                                <el-tag type="success" style="margin-left:15px;" v-show="v.is_type==1">商家栏目</el-tag>
                            </div>
                        </el-checkbox-group>
                        
                    </el-form-item>
                    <el-form-item class="width_auto"><el-divider content-position="left">接口权限</el-divider></el-form-item>
                    <el-form-item class="width_auto">
                        <div class="div_apis" v-for="(v,k) in info.hooks_list" :key="k" >
                            <el-checkbox style="float:left" v-model="hooks" :label="v.id" border>{{v.name}}</el-checkbox>
                            <div class="apis"><el-tag type="info">{{v.apis}}</el-tag></div>
                            <div class="content"><el-tag type="info">{{v.content}}</el-tag></div>
                            <el-tag type="success" style="margin-left:15px;" v-if="v.is_type==1">商家接口</el-tag>
                        </div>
                        
                        
                        <!-- <el-tag type="success" style="margin-left:15px;" v-show="v.is_type==1">商家接口</el-tag> -->
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
          menus:[],
          hooks:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
            this.menus = [];
            this.hooks = [];
        },
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addRoles;
                    if(_this.edit_id>0){
                        url = _this.$api.editRoles+_this.edit_id;
                    }

                    _this.info['menus'] = _this.menus;
                    _this.info['hooks'] = _this.hooks;
                    delete _this.info['menus_list'];
                    delete _this.info['hooks_list'];

                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("编辑成功");
                            _this.$router.go(-1);
                        }else{
                            _this.$message.error("编辑失败");
                            _this.$router.go(0);
                        }
                    });
                }
            });
        },
        get_roles_info:function(){
            let _this = this;
            this.$get(this.$api.editRoles+this.edit_id).then(function(res){
                _this.info = res.data;
                let menus = [];
                let hooks = [];
                if(res.data.hooks.length>0){
                    res.data.hooks.forEach(hooksRes => {
                        hooks.push(hooksRes['id']);
                    });
                    _this.hooks = hooks;
                }

                if(res.data.menus.length>0){
                    res.data.menus.forEach(menusRes => {
                        menus.push(menusRes['id']);
                    });
                    _this.menus = menus;
                }
            })
        },
        add_role_info:function(){
            let _this = this;
            this.$get(this.$api.addRoles).then(function(res){
                _this.info = res.data;
            })
        },
        
    },
    created() {

        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }
        if(this.edit_id>0){
            this.get_roles_info();
        }else{
            this.add_role_info();
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.menus_list{
    float: left;
    margin-right: 15px;
    margin-bottom: 15px;
}
.apis{
    margin-left: 15px;
    float: left;
}
.content{
    float: left;
    margin-left: 15px;
}
.div_apis{
    margin-bottom: 15px;
    float: left;
    width: 50%;
}
.div_apis:after{
    content:'';
    display: block;
    clear:both;
}
</style>