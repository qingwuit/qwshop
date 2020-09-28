<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                申请售后
            </div>
            <div class="x20"></div>
            <div class="uif_block" >
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="售后类型">
                        <a-radio-group :default-value="info.refund_type" @change="typeChange">
                            <a-radio :value="0">退款</a-radio>
                            <a-radio :value="1">退换货</a-radio>
                        </a-radio-group>
                    </a-form-model-item>
                    <a-form-model-item label="售后原因">
                        <a-textarea v-model="info.refund_remark" :rows="4" />
                        <a-tag color="red" v-for="(v,k) in error_list" :key="k" @click="changeTag(v)">{{v}}</a-tag>
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
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              order_id:0,
              refund_type:0,
              refund_remark:'',
          },
          params:{},
          error_list:[
              '物品破损',
              '尺码错误',
              '颜色不对',
              '其他原因',
          ],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.$post(this.$api.homeRefunds,this.info).then(res=>{
                this.$returnInfo(res);
                setTimeout(()=>{
                    return this.$router.go(-1);
                },1000)
                
            })
        },
        changeTag(v){
            this.info.refund_remark = v;
        },
        typeChange(e){
            this.info.refund_type = e.target.value;
        }
      
    },
    created() {
        this.info.order_id= this.$route.params.id;
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>