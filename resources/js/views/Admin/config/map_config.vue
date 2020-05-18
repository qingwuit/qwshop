<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>地图配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label-width="130px" label="百度AK" prop="baidu_ak" :rules="[{required:true,message:'百度AK不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.baidu_ak"></el-input></el-form-item>
                    <el-form-item label-width="130px" label="高德AK" prop="amap_ak" :rules="[{required:true,message:'高德AK不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.amap_ak"></el-input></el-form-item>
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
              baidu_ak:'',
              amap_ak:'',
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
                    this.$post(this.$api.mapConfig,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_map_config();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_map_config(){
            this.$get(this.$api.mapConfig).then(res=>{
                if(res.data != null){
                    this.info  = res.data;
                }
            });
        },
    },
    created() {
        this.get_map_config();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>