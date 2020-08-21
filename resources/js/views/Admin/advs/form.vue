<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            广告编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="广告位">
                    <a-select v-model="info.ap_id">
                        <a-select-option v-for="(v,k) in list" :key="k" :value="v.id">{{v.ap_name}}</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="广告名">
                    <a-input v-model="info.name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="广告图片">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.adminAdvsUploadThumb"
                        :data="{token:$getSession('token_type')}"
                        @change="upload"
                    >
                        <img style="max-width:600px" v-if="info.image_url" :src="info.image_url" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
                </a-form-model-item>
                <a-form-model-item label="链接">
                    <a-input v-model="info.url"></a-input>
                </a-form-model-item>
                <a-form-model-item label="时间">
                    <a-range-picker :value="[moment(info.adv_start), moment(info.adv_end)]" @change="time_format" format="YYYY-MM-DD HH:mm:ss" show-time></a-range-picker>
                </a-form-model-item>
                <a-form-model-item label="类型">
                    <a-select v-model="info.adv_type">
                        <a-select-option :value="0">默认</a-select-option>
                    </a-select>
                </a-form-model-item>

                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>
            
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              adv_type:0,
          },
          list:[],
          id:0,
          loading:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        moment,
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.ap_id)){
                return this.$message.error('广告位不能为空');
            }
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('广告名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.adminAdvs,this.id);
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
            this.$get(this.$api.adminAdvs+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取菜单列表
        onload(){
            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }

            // 判断是否从广告位过来
            if(!this.$isEmpty(this.$route.query.ap_id)){
                this.info.ap_id = parseInt(this.$route.query.ap_id);
            }

            this.$get(this.$api.adminAdvPositions,{per_page:100}).then(res=>{
                this.list = res.data.data;
            });

        },
        time_format(val){
            this.info.adv_start = moment(val[0]._d).format("YYYY-MM-DD HH:mm:ss")
            this.info.adv_end = moment(val[1]._d).format("YYYY-MM-DD HH:mm:ss")
            this.$forceUpdate();
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,'image_url',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
            
        }
 
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>