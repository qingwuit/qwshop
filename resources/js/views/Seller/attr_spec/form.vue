<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>规格属性编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="规格名" prop="spec_name" :rules="[{required:true,message:'规格名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.spec_name"></el-input></el-form-item>
                    <el-form-item label="属性" prop="attr_name"  :rules="[{required:true,message:'属性不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容,用英文逗号隔开 如：红色,黑色"  v-model="info.attr_name"></el-input></el-form-item>
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
              is_sort:0,
          },
          list:[],
          upload_headers:{},
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
                    let url = _this.$api.addAttrSpec;
                    if(_this.edit_id>0){
                        url = _this.$api.editAttrSpec+_this.edit_id;
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
        get_attr_spec_info:function(){
            let _this = this;
            this.$get(this.$api.editAttrSpec+this.edit_id).then(function(res){
                _this.info = res.data;
            })

        },
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到

        // 判断是否是编辑
        this.info.pid = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_attr_spec_info();
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>