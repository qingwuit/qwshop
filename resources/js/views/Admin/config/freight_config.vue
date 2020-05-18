<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>物流配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="160px" label="AppCode" prop="freight_key" :rules="[{required:true,message:'AppCode不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.freight_key"></el-input></el-form-item>
                    <el-form-item label-width="160px" label="备注："><el-tag type="info">快递物流信息 阿里云  全国快递物流查询-快递查询接口</el-tag></el-form-item>
                    <el-form-item label-width="160px">
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
          info:{
              freight_key:'',
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    this.$post(this.$api.freightKeyConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_freight_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_freight_config(){
            this.$get(this.$api.freightKeyConfig).then(res=>{
                if(res.data != null){
                    this.info.freight_key  = res.data;
                }
            });
        },
    },
    created() {
        this.get_freight_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>