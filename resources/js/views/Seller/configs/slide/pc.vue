<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="幻灯片一">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.sellerConfigs+'/upload/images'"
                :data="{token:$getSession('token_type'),name:'slide'}"
                @change="upload"
            >
                <img v-if="info.store_slide[0]" width="600px" height="220px" :src="info.store_slide[0]" />
                <div v-else>
                    <a-font v-if="!loading" type='iconplus' />
                    <a-icon v-else type="loading" />
                </div>
            </a-upload>
        </a-form-model-item>
        <a-form-model-item label="幻灯片二">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.sellerConfigs+'/upload/images'"
                :data="{token:$getSession('token_type'),name:'slide'}"
                @change="upload2"
            >
                <img v-if="info.store_slide[1]" width="600px" height="220px" :src="info.store_slide[1]" />
                <div v-else>
                    <a-font v-if="!loading1" type='iconplus' />
                    <a-icon v-else type="loading" />
                </div>
            </a-upload>
        </a-form-model-item>
        <a-form-model-item label="幻灯片三">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.sellerConfigs+'/upload/images'"
                :data="{token:$getSession('token_type'),name:'slide'}"
                @change="upload3"
            >
                <img v-if="info.store_slide[2]" width="600px" height="220px" :src="info.store_slide[2]" />
                <div v-else>
                    <a-font v-if="!loading2" type='iconplus' />
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
          info:{
              store_slide:['','','']
          },
          loading:false,
          loading1:false,
          loading2:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.info.edit_type = 'pc_slide';
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
                    this.$set(this.info.store_slide,'0',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
            
        },
        upload2(e){
            if(e.file.status == 'done'){
                this.loading1 = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info.store_slide,'1',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading1 = true;
            }
            
        },
        upload3(e){
            if(e.file.status == 'done'){
                this.loading2 = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info.store_slide,'2',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading2 = true;
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