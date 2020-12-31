<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                申请提现{{userInfo.nickname}}
            </div>
            <div class="x20"></div>
            <div class="uif_block">
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="当前余额">
                        <a-input disabled v-model="userInfo.money" />
                    </a-form-model-item>
                    <a-form-model-item label="真实姓名">
                        <a-input disabled v-model="params.name" />
                    </a-form-model-item>
                    <a-form-model-item label="银行卡号">
                        <a-input disabled v-model="params.bank_no" />
                    </a-form-model-item>
                    <a-form-model-item label="银行名称">
                        <a-input disabled v-model="params.bank_name" />
                    </a-form-model-item>

                    <a-form-model-item label="提现金额">
                        <a-input v-model="money" />
                    </a-form-model-item>
                    
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                        <div class="submit_btn" @click="handleSubmit">确定提交</div>
                    </a-form-model-item>
                </a-form-model>
            </div>


        </div>

    </div>
</template>

<script>
import {mapState} from 'vuex'
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              
          },
          money:100,
          params:{
     
          },
      };
    },
    watch: {},
    computed: {...mapState('homeLogin',['userInfo'])},
    methods: {
        get_user_check(){
            this.$get(this.$api.homeUser+'/user_check').then(res=>{
                if(res.code == 200){
                    this.params = res.data;
                }else{
                    this.$message.error(res.msg)
                    setTimeout(()=>{
                        this.$router.go(-1);
                    },1000);
                }
                
            })
        },
        handleSubmit(){
            this.$post(this.$api.homeCash,{money:this.money}).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg)
                    setTimeout(()=>{
                        this.$router.go(-1);
                    },1000);
                }else{
                    return this.$message.error(res.msg)
                }
            })
        },
       
      
    },
    created() {
        this.get_user_check()
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>