<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                身份认证
            </div>
            <div class="x20"></div>
            <div class="uif_block" v-if="edit">
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="真实姓名">
                        <a-input v-model="params.name" />
                    </a-form-model-item>
                    <a-form-model-item label="身份证号">
                        <a-input v-model="params.card_no" />
                    </a-form-model-item>
                    <a-form-model-item label="身份证(上)">
                        <a-upload
                            list-type="picture-card"
                            class="avatar-uploader"
                            :show-upload-list="false"
                            :action="$api.homeUser+'/user_check_upload'"
                            :data="{token:$getSession('token_type'),name:'card_t'}"
                            @change="upload"
                        >
                            <img height="150px" v-if="params.card_t" :src="params.card_t" />
                            <div v-else>
                                <a-font type='iconplus' />
                            </div>
                        </a-upload>
                    </a-form-model-item>
                    <a-form-model-item label="身份证(下)">
                        <a-upload
                            list-type="picture-card"
                            class="avatar-uploader"
                            :show-upload-list="false"
                            :action="$api.homeUser+'/user_check_upload'"
                            :data="{token:$getSession('token_type'),name:'card_b'}"
                            @change="upload"
                        >
                            <img height="150px" v-if="params.card_b" :src="params.card_b" />
                            <div v-else>
                                <a-font type='iconplus' />
                            </div>
                        </a-upload>
                    </a-form-model-item>
                    <a-form-model-item label="银行卡号">
                        <a-input v-model="params.bank_no" />
                    </a-form-model-item>
                    <a-form-model-item label="银行名称">
                        <a-input v-model="params.bank_name" />
                    </a-form-model-item>
                    
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                        <div class="submit_btn" @click="handleSubmit">确定提交</div>
                    </a-form-model-item>
                </a-form-model>
            </div>

            <div class="uif_block" v-if="!edit">
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="真实姓名">{{params.name}}</a-form-model-item>
                    <a-form-model-item label="身份证号">{{params.card_no}}</a-form-model-item>
                    <a-form-model-item label="银行卡号">{{params.bank_no}}</a-form-model-item>
                    <a-form-model-item label="银行名称">{{params.bank_name}}</a-form-model-item>
                    
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                        <div class="submit_btn" @click="edit=true;params = {};">前往修改</div>
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
              
          },
          params:{
     
          },
          edit:true,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_user_check(){
            this.$get(this.$api.homeUser+'/user_check').then(res=>{
                if(res.code == 200){
                    this.edit = false;
                    this.params = res.data;
                }else{
                    this.params = {};
                    this.edit = true;
                }
                
            })
        },
        handleSubmit(){
      
            this.$post(this.$api.homeUser+'/edit_user_check',this.params).then(res=>{
                this.edit = false;
                this.get_user_check();
                this.$returnInfo(res);
            })
        },
        upload(e){
            if(e.file.status == 'done'){
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.params,rs.data.name,rs.data.url);
                }else{
                    return this.$message.error(rs.msg);
                }
            }
        }
      
    },
    created() {
        this.get_user_check()
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.yzmbtn{
    cursor: pointer;
    width: 140px;
    height: 30px;
    line-height: 30px;
    display: inline-block;
    text-align: center;
    background: #333;
    margin-left: 20px;
    color:#fff;
    &.dis{
        background: #ccc;
        color:#666;
    }
}
</style>