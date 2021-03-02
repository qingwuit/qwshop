<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="店铺LOGO">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.sellerConfigs+'/upload/images'"
                :data="{token:$getSession('token_type'),name:'store_logo'}"
                @change="upload"
            >
                <img v-if="info.store_logo" :src="info.store_logo" />
                <div v-else>
                    <a-font v-if="!loading" type='iconplus' />
                    <a-icon v-else type="loading" />
                </div>
            </a-upload>
        </a-form-model-item>
        <a-form-model-item label="店铺名称">
            <a-input v-model="info.store_name"></a-input>
        </a-form-model-item>
        <a-form-model-item label="店铺描述">
            <a-textarea placeholder="店铺描述" v-model="info.store_description" :rows="4" />
        </a-form-model-item>
        <a-form-model-item label="联系电话">
            <a-input v-model="info.store_mobile"></a-input>
        </a-form-model-item>
        <a-form-model-item label="售后服务">
            <wang-editor :contents="info.after_sale_service" @goods_content="goods_content_fun" />
        </a-form-model-item>
        
        <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
            <a-button type="primary" @click="handleSubmit">提交</a-button>
        </a-form-model-item>
    </a-form-model>
</template>

<script>
import wangEditor from "@/components/wangeditor"
export default {
    components: {wangEditor},
    props: {},
    data() {
      return {
          info:{},
          loading:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.info.edit_type = 'base';
            this.$put(this.$api.sellerConfigs,this.info).then(res=>{
                this.get_info();
                return this.$returnInfo(res);
            })
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,'store_logo',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
            
        },
        // 编辑器内容修改
        goods_content_fun(val){
            this.info.after_sale_service = val;
        },
        get_info(){
            this.$get(this.$api.sellerConfigs).then(res=>{
                this.info = res.data;
            })
        }
    },
    created() {
        this.get_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>