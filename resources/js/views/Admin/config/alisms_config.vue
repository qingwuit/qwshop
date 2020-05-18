<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>短信配置</div>
                </div>
                <div class="admin_main_block_right">
                    <div><el-button type="primary" icon="el-icon-message" @click="$router.push('/Admin/Config/alisms_list')">短信日志</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="130px" label="KEY" prop="key" ><el-input placeholder="请输入内容" v-model="info.key"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="SECRET" prop="secret" ><el-input placeholder="请输入内容" v-model="info.secret"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="sign_name" prop="sign_name" ><el-input placeholder="请输入内容" v-model="info.sign_name"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="注意："><el-tag type="info">SMS为阿里云平台</el-tag></el-form-item>
                    <el-form-item label-width="130px" >
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
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
          info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    this.$post(this.$api.aliSmsConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_alisms_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_alisms_config(){
            this.$get(this.$api.aliSmsConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_alisms_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>