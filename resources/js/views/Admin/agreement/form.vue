<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>协议编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="标题" prop="name" :rules="[{required:true,message:'标题不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="英文标题" prop="ename" :rules="[{required:true,message:'英文标题不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.ename"></el-input></el-form-item>
                    <el-form-item label="内容" class="width_auto_70">
                        <wangeditor @goods_content="goods_content" :contents="info.content"></wangeditor>
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
import wangeditor from "@/components/seller/wangeditor.vue"
export default {
    components: {
        wangeditor,
    },
    props: {},
    data() {
      return {
          edit_id:0,
          info:{
              content:'',
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
            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addAgreement;
                    if(_this.edit_id>0){
                        url = _this.$api.editAgreement+_this.edit_id;
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
        get_agreement_info:function(){
            let _this = this;
            this.$get(this.$api.editAgreement+this.edit_id).then(function(res){
                _this.info = res.data;
            })

        },
        // 富文本编辑内容变化
        goods_content:function(data){
            this.info.content = data;
        },
       
    },
    created() {

        // 判断是否是编辑
        this.info.pid = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_agreement_info();
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>