<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            申请提现
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="当前余额">
                        <a-input disabled v-model="store_info.store_money" />
                    </a-form-model-item>
                    <a-form-model-item label="真实姓名">
                        <a-input  v-model="params.name" />
                    </a-form-model-item>
                    <a-form-model-item label="银行卡号">
                        <a-input  v-model="params.bank_no" />
                    </a-form-model-item>
                    <a-form-model-item label="银行名称">
                        <a-input  v-model="params.bank_name" />
                    </a-form-model-item>

                    <a-form-model-item label="提现金额">
                        <a-input v-model="params.money" />
                    </a-form-model-item>
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>
            
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          params:{
              money:0,
          },
          store_info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.params.name)){
                return this.$message.error('真实姓名不能为空');
            }
            if(this.$isEmpty(this.params.bank_no)){
                return this.$message.error('银行卡号不能为空');
            }
            if(this.$isEmpty(this.params.bank_name)){
                return this.$message.error('银行名称不能为空');
            }
            if(this.$isEmpty(this.params.money)){
                return this.$message.error('提现金额不能为空');
            }

            this.$post(this.$api.sellerCashes,this.params).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg)
                    return this.$router.back();
                }else{
                    return this.$message.error(res.msg)
                }
            })
   
            
        },
 
        // 获取列表
        onload(){
            this.$get(this.$api.sellerConfigs).then(res=>{
                this.store_info = res.data;
            })
        },
  
        // 输入框修改
        handleInputChange(e) {
            this.inputValue = e.target.value;
        },
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>