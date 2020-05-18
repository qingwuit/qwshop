<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>栏目编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="栏目类型" prop="is_type">
                        <el-select v-model="info.is_type" placeholder="请选择栏目" @change="selectChangeAdmin()">
                            <el-option label="后台" :value="0"></el-option>
                            <el-option label="商家" :value="1"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="栏目名" prop="name" :rules="[{required:true,message:'栏目名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="父栏目" prop="pid">
                        <el-select v-model="info.pid" placeholder="请选择栏目" @change="selectChange()">
                            <el-option label="顶级栏目" :value="0"></el-option>
                            <el-option v-for="(v,k) in menus" :label="v.name" :key="k" :value="v.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="图标" prop="icon" :rules="[{required:true,message:'Icon不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.icon"></el-input></el-form-item>
                    <el-form-item label="链接" prop="url" :rules="[{required:true,message:'链接不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.url"></el-input></el-form-item>
                    <el-form-item label="排序" prop="is_sort"><el-input placeholder="请输入内容" type="number"  v-model="info.is_sort"></el-input></el-form-item>
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
          menus_list:[],
          menus:[],
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
                    let url = _this.$api.addMenus;
                    if(_this.edit_id>0){
                        url = _this.$api.editMenus+_this.edit_id;
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
        get_menus_info:function(){
            let _this = this;
            this.$get(this.$api.editMenus+this.edit_id).then(function(res){
                _this.info = res.data.info;
                _this.menus = res.data.list;
            })

        },
        get_menus_list:function(){
            let _this = this;
            this.$get(this.$api.addMenus).then(function(res){
                _this.menus_list = res.data;
                _this.menus = res.data['admin'];
            })
        },
        selectChange:function(){
            this.$forceUpdate();
        },
        selectChangeAdmin:function(){
            if(this.info.is_type == 0){
                this.menus = this.menus_list['admin'];
            }else{
                this.menus = this.menus_list['seller'];
            }
            this.$forceUpdate();
        }
    },
    created() {

        // 判断是否是编辑
        this.info.pid = 0;
        this.info.is_type = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }else{
            return this.get_menus_list();
        }

        if(this.edit_id>0){
            this.get_menus_info();
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>