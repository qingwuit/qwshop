<template>
    <div class="qingwu">
        <el-form  label-width="100px" ref="info" :model="info">
            <el-form-item label-width="160px" label="APPID" prop="appid" :rules="[{required:true,message:'APPID不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.appid"></el-input></el-form-item>
            <el-form-item label-width="160px" label="支付宝公钥" prop="public_key" :rules="[{required:true,message:'支付宝公钥不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" v-model="info.public_key"></el-input></el-form-item>
            <el-form-item label-width="160px" label="私钥" prop="private_key" :rules="[{required:true,message:'私钥不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" v-model="info.private_key"></el-input></el-form-item>
            <el-form-item label-width="160px" label="notify_url" prop="notify_url" :rules="[{required:true,message:'异步回调不能为空',trigger: 'blur' }]"><el-input  placeholder="请输入内容" v-model="info.notify_url"></el-input></el-form-item>
            <el-form-item label-width="160px" label="return_url" prop="return_url" :rules="[{required:true,message:'同步回调不能为空',trigger: 'blur' }]"><el-input  placeholder="请输入内容" v-model="info.return_url"></el-input></el-form-item>
            <el-form-item label-width="160px" label="备注："><el-tag type="info">支付宝 PC</el-tag></el-form-item>
            <el-form-item label-width="160px">
                <el-button type="primary" @click="submitForm('info')">提交</el-button>
            </el-form-item>
        </el-form>
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
                    this.$post(this.$api.aliPayPcConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_alipaypc_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_alipaypc_config(){
            this.$get(this.$api.aliPayPcConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_alipaypc_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>