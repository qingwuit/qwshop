<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>自动执行任务配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <!-- <el-form-item label-width="130px" label="返利类型" prop="status"><el-switch v-model="info.status" :active-text="info.status?'余额':'积分'"></el-switch></el-form-item> -->
                    <el-form-item label-width="130px" label="自动确认收货" prop="auto_confirm"><el-input type="number" placeholder="请输入内容" v-model="info.auto_confirm"><template slot="append">天</template></el-input></el-form-item>
                    <el-form-item label-width="130px" label="自动评价" prop="auto_comment"><el-input type="number" placeholder="请输入内容" v-model="info.auto_comment"><template slot="append">天</template></el-input></el-form-item>
                    <el-form-item label-width="130px" label="取消未支付" prop="auto_cancel"><el-input type="number" placeholder="请输入内容" v-model="info.auto_cancel"><template slot="append">天</template></el-input></el-form-item>
                    <!-- <el-form-item label-width="130px" label="允许售后时间" prop="after_sale"><el-input type="number" placeholder="请输入内容" v-model="info.after_sale"><template slot="append">天</template></el-input></el-form-item> -->
                    <el-form-item label-width="130px" label="注意："><el-tag type="info">定时任务，自动评价为 确认收货未评价自动执行|允许售后时间 为用户确认收货前后申请售后的时间</el-tag></el-form-item>
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
              auto_confirm:0,
              auto_comment:0,
              auto_cancel:0,
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
                    this.$post(this.$api.TaskTimeConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_task_time_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_task_time_config(){
            this.$get(this.$api.TaskTimeConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_task_time_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>