<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>免运费设置 - 满多少包邮</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="额度" prop="free_freight"><el-input placeholder="请输入内容" v-model="info.free_freight"></el-input></el-form-item>
                    <el-form-item ><el-tag type="info">默认为 0 则商品设置运费或运费模版为准。</el-tag></el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">保存</el-button>
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
              free_freight:0,
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
                    this.$post(this.$api.storeFreeFreightSetting,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_free_freight_info();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_free_freight_info(){
            this.$get(this.$api.storeFreeFreightSetting).then(res=>{
                this.info.free_freight  = res.data;
            });
        },
        
    },
    created() {
        this.get_free_freight_info();
    },
    mounted() {
    }
};
</script>
<style lang="scss" scoped>

</style>