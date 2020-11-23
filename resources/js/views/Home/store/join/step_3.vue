<template>
    <div class="store_join width_center_1200">
        <div class="step_bar">
            <div class="step">
                <div class="item success"><a-icon type="read" />阅读协议</div>
                <div class="item success"><a-icon type="edit" />填写资料</div>
                <div :class="info.store_verify==2?'item check':(info.store_verify==3?'item success':'item error')"><a-icon :type="info.store_verify==0?'close-circle':'coffee'" />{{info.store_verify==0?'审核失败':'等待审核'}}</div>
                <div :class="info.store_verify==3?'item success':'item'"><a-icon type="check-circle" />审核通过</div>
            </div>
        </div>
        <a-result :title="info.store_verify==2?'已提交资料，等待管理员审核':(info.store_verify==3?'审核成功，前往店铺设置':'审核失败，是否重新填写资料')"  :status="info.store_verify==2?'info':(info.store_verify==3?'success':'error')">
            <template #extra>
                <a-button key="console" type="primary" @click="next_step">{{info.store_verify==2?'返回首页':(info.store_verify==3?'店铺配置':'重新提交')}}</a-button>
            </template>
            <div class="desc" v-if="info.store_verify==0">
                <p style="font-size: 16px;" v-if="info.store_refuse_info !=''">
                    <a-icon :style="{ color: 'red' }" type="close-circle" /> {{info.store_refuse_info}}
                </p>
            </div>
        </a-result>
     
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          loading:false,
          info:{
              store_verify:2,
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        next_step(){
            if(this.info.store_verify == 2 || this.info.store_verify == 3){
                return this.$router.push(this.info.store_verify==2?'/':'/Seller/login')
            }
            if(this.info.store_verify == 0){
                this.$post(this.$api.homeStoreJoin).then(res=>{
                    if(res.code == 200){
                        return this.$router.push('/store/join/step_2')
                    }
                    return this.$$message.error(res.msg);
                })
            }
        },
        store_verify(){
            this.$get(this.$api.homeStoreVerify).then(res=>{
                if(res.code == 200){
                    this.info = res.data
                    if(res.data.store_verify == 1){
                        this.$router.push('/store/join/step_2')
                    }
                }else{
                    this.$router.push('/store/join/step_1')
                }
            })
        }
        
    },
    created() {
        this.store_verify();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>