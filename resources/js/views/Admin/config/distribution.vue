<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>分销返利设置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="130px" label="返利类型" prop="status"><el-switch v-model="info.status" :active-text="info.status?'余额':'积分'"></el-switch></el-form-item>
                    <el-form-item label-width="130px" label="一级分销商" prop="distribution"><el-input type="number" placeholder="请输入内容" v-model="info.distribution"><template slot="append">%</template></el-input></el-form-item>
                    <el-form-item label-width="130px" label="二级分销商" prop="distribution_1"><el-input type="number" placeholder="请输入内容" v-model="info.distribution_1"><template slot="append">%</template></el-input></el-form-item>
                    <el-form-item label-width="130px" label="三级分销商" prop="distribution_2"><el-input type="number" placeholder="请输入内容" v-model="info.distribution_2"><template slot="append">%</template></el-input></el-form-item>
                    <el-form-item label-width="130px" label="注意："><el-tag type="info">返利为订单百分比，默认返利类型为积分</el-tag></el-form-item>
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
          info:{
              status:false,
              distribution:0,
              distribution_1:0,
              distribution_2:0,
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
                    this.info.status = this.info.status?1:0;
                    this.$post(this.$api.distributionConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_upload_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_upload_config(){
            this.$get(this.$api.distributionConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                    this.info.status = this.info.status==1?true:false;
                }
            });
        },
    },
    created() {
        this.get_upload_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>