<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>售后内容配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="售后设置" class="width_auto_70">
                        <wangeditor @goods_content="goods_content" :contents="info.after_sale_content"></wangeditor>
                    </el-form-item>
                    
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">保存</el-button>
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
      let _this = this;
      return {
          info:{
              after_sale_content:'',
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
                    this.$post(this.$api.storeAfterSaleContent,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_store_info();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        handleAvatarSuccess(res) {
            this.info.store_logo = res.data;
            this.$forceUpdate();
        },
        get_store_info(){
            this.$get(this.$api.storeAfterSaleContent).then(res=>{
                this.info.after_sale_content  = res.data;
            });
        },
        // 富文本编辑内容变化
        goods_content:function(data){
            this.info.after_sale_content = data;
        },
        
        
    },
    created() {
        this.get_store_info();
    },
    mounted() {
    }
};
</script>
<style lang="scss" scoped>

</style>