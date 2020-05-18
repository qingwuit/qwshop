<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>商品品牌编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="品牌名" prop="name" :rules="[{required:true,message:'分类名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="缩略图" prop="thumb"><el-upload class="avatar-uploader" :action="$api.goodsClassUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.thumb" :src="info.thumb" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
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
                    let url = _this.$api.addGoodsBrand;
                    if(_this.edit_id>0){
                        url = _this.$api.editGoodsBrand+_this.edit_id;
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
        get_goods_class_info:function(){
            let _this = this;
            this.$get(this.$api.editGoodsBrand+this.edit_id).then(function(res){
                _this.info = res.data;
            })

        },
        selectChange:function(){
            this.$forceUpdate();
        },
        handleAvatarSuccess(res) {
            this.info.thumb = res.data;
            this.$forceUpdate();
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
            this.get_goods_class_info();
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>