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
                        {{info.refund_type==0?'退款':(info.refund_type==1?'退换货':'售后结束')}}
                    </a-form-model-item>
                    <a-form-model-item label="售后原因">
                        {{info.refund_remark}}
                    </a-form-model-item>

                    <a-form-model-item label="申请售后状态">
                        <a-tag v-if="info.refund_verify==0">等待审核</a-tag>
                        <a-tag color="green" v-if="info.refund_verify==1">审核成功</a-tag>
                        <a-tag color="red" v-if="info.refund_verify==2">申请拒绝</a-tag>
                    </a-form-model-item>

                    <a-form-model-item label="退换货进度" v-if="info.refund_type==1">
                        <a-tag color="red" v-if="info.refund_verify==0">等待审核</a-tag>
                        <a-tag color="red" v-if="info.refund_verify==2">拒绝申请</a-tag>
                        <a-tag color="orange" v-if="info.refund_step==0 && refund_info.refund_verify==1">等待用户发货</a-tag>
                        <a-tag color="orange" v-if="info.refund_step==1">等待确认发货</a-tag>
                        <a-tag color="blue" v-if="info.refund_step==2">等待用户确认</a-tag>
                        <a-tag color="green" v-if="info.refund_step==3">售后处理完成</a-tag>
                    </a-form-model-item>

                    <a-form-model-item label="填写寄回物流" v-if="info.refund_type==1 && info.refund_verify==1 ">
                        <a-input style="width: 75%" placeholder="输入快递单号发货" v-model="info.delivery_no" />
                    </a-form-model-item>

                    <a-form-model-item label="拒绝原因" v-if="info.refund_verify==2">
                        {{info.refuse_remark}}
                    </a-form-model-item>

                    <!-- <a-form-model-item label="用户寄回单号" v-if="info.delivery_no !=''">
                        {{info.delivery_no}}
                    </a-form-model-item> -->

                    <a-form-model-item label="重新发货单号" v-if="info.re_delivery_no !=''">
                        {{info.re_delivery_no}}
                    </a-form-model-item>
                    
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }"  v-if="info.refund_step==0 && info.refund_type==1 && info.refund_verify==1">
                        <div class="submit_btn" @click="handleSubmit">确定提交</div>
                    </a-form-model-item>

                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }" v-if="info.refund_step==2">
                        <div class="submit_btn" @click="handleSubmit2">完成售后</div>
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
              refund_type:0,
              order_id:0,
              delivery_no:'',
              delivery_code:'yd',
          },
          params:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.$put(this.$api.homeRefunds+'/'+this.info.order_id,{delivery_no:this.info.delivery_no}).then(res=>{
                this.$returnInfo(res)
                return this.get_info();
            })
        },
        handleSubmit2(){
            this.$put(this.$api.homeRefunds+'/'+this.info.order_id,{refund_step:3}).then(res=>{
                this.$returnInfo(res)
                return this.get_info();
            })
        },
        get_info(){
            this.$get(this.$api.homeRefunds+'/'+this.info.order_id).then(res=>{
                this.info = res.data;
            })
        }
      
    },
    created() {
        this.info.order_id= this.$route.params.id;
        this.get_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>