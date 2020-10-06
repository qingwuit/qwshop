<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="店铺门面">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.sellerConfigs+'/upload/images'"
                :data="{token:$getSession('token_type'),name:'store_face_image'}"
                @change="upload"
            >
                <img v-if="info.store_face_image" width="600px" height="220px" :src="info.store_face_image" />
                <div v-else>
                    <a-font v-if="!loading" type='iconplus' />
                    <a-icon v-else type="loading" />
                </div>
            </a-upload>
        </a-form-model-item>
        
        <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
            <a-button type="primary" @click="handleSubmit">提交</a-button>
        </a-form-model-item>
    </a-form-model>
</template>

<script>
export default {
    components: {},
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
            this.info.edit_type = 'face';
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
                    this.$set(this.info,'store_face_image',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
            
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