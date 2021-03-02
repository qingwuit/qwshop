<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                用户资料
            </div>
            <div class="x20"></div>
            <div class="uif_block" >
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="用户名">
                        <a-input v-model="info.nickname" />
                    </a-form-model-item>
                    <a-form-model-item label="用户头像">
                        <a-upload
                            list-type="picture-card"
                            class="avatar-uploader"
                            :show-upload-list="false"
                            :action="$api.homeUser+'/avatar_upload'"
                            :data="{token:$getSession('token_type')}"
                            @change="upload"
                        >
                            <img height="150px" v-if="info.avatar" :src="info.avatar" />
                            <div v-else>
                                <a-font v-if="!loading" type='iconplus' />
                                <a-icon v-else type="loading" />
                            </div>
                        </a-upload>
                    </a-form-model-item>
                    <a-form-model-item label="性别">
                        <a-radio-group name="radioGroup" :default-value="info.sex" @change="sexChange">
                            <a-radio :value="1">男</a-radio>
                            <a-radio :value="0">女</a-radio>
                        </a-radio-group>
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
              sex:1,
              avatar:'',
          },
          loading:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_user_info(){
            this.$get(this.$api.homeUser+'/edit_user').then(res=>{
                this.info = res.data;
            })
        },
        handleSubmit(){
            this.$put(this.$api.homeUser+'/edit_user',this.info).then(res=>{
                this.$returnInfo(res);
            })
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.info.avatar = rs.data;
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
        },
        sexChange(e){
            this.info.sex = e.target.value;
        }
      
    },
    created() {
        this.get_user_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>