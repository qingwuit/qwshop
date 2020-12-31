<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            签名编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="签名名称(英文)">
                    <a-input v-model="info.name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="签名(sign_name)">
                    <a-input v-model="info.val"></a-input>
                </a-form-model-item>
                <a-form-model-item label="模版(template)">
                    <a-input v-model="info.code"></a-input>
                </a-form-model-item>
                <a-form-model-item label="描述">
                    <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.content" />
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
          info:{
          },
          list:[],
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('签名名称不能为空');
            }
            if(this.$isEmpty(this.info.val)){
                return this.$message.error('签名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.adminSmsSigns,this.id);
            if(api.status){
                this.$put(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }else{
                this.$post(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }
   
            
        },
        get_info(){
            this.$get(this.$api.adminSmsSigns+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取列表
        onload(){

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }
        
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