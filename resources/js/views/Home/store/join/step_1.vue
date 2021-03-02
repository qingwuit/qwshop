<template>
    <div class="store_join width_center_1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><a-icon type="read" />阅读协议</div>
                <div class="item"><a-icon type="edit" />填写资料</div>
                <div class="item"><a-icon type="coffee" />等待审核</div>
                <div class="item"><a-icon type="check-circle" />审核通过</div>
            </div>
        </div>
        <a-divider><font style="font-size:20px">{{info.title|'-'}}</font></a-divider>
        <div class="agreement_content" v-html="info.content"></div>
        <div class="agreement_btn"><a-checkbox v-model="checked">是否了解且同意商家入驻协议</a-checkbox></div>
        <div class="agreement_btn"><a-button type="primary" @click="next_step">同意协议，下一步</a-button></div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          checked:false,
          info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        next_step(){
            if(!this.checked){
                return this.$message.error('请勾选同意协议.');
            }

            this.$post(this.$api.homeStoreJoin).then(res=>{
                if(res.code == 200){
                    return this.$router.push('/store/join/step_2')
                }
                return this.$message.error(res.msg);
            })

            
        },
        get_agreement(){
            this.$get(this.$api.homeAgreement+'/store_join').then(res=>{
                if(res.code == 200){
                    this.info = res.data;
                }else{
                    this.$message.error(res.msg);
                }
            })
        },
        store_verify(){
            this.$get(this.$api.homeStoreVerify).then(res=>{
                if(res.code == 200){
                    if(res.data.store_verify == 1){
                        this.$router.push('/store/join/step_2')
                    }
                    else if(res.data.store_verify == 2 || res.data.store_verify == 3 || res.data.store_verify == 0){
                        this.$router.push('/store/join/step_3')
                    }
                }
            })
        }
    },
    created() {
        this.store_verify();
        this.get_agreement();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.agreement_btn{
    margin:20px 0 10px 0;
    text-align: center;
}
.agreement_content{
    overflow-y: scroll;
    padding:20px;
    height: 600px;
}

</style>