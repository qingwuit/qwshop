<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        <a-form-model-item label="网站名称">
            <a-input v-model="info.web_name"></a-input>
        </a-form-model-item>
        <a-form-model-item label="首页标题">
            <a-input v-model="info.title"></a-input>
        </a-form-model-item>
        <a-form-model-item label="关键词">
            <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.keywords" />
        </a-form-model-item>
        <a-form-model-item label="网站描述">
            <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.description" />
        </a-form-model-item>
        <a-form-model-item label="网站LOGO">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.adminConfigsUploadLogo"
                :data="{token:$getSession('token_type')}"
                @change="logo"
            >
                <img height="90px" v-if="info.logo" :src="info.logo" />
                <div v-else>
                    <a-font type="iconplus" />
                </div>
            </a-upload>
        </a-form-model-item>
        <a-form-model-item label="游览器ICON">
            <a-upload
                list-type="picture-card"
                class="avatar-uploader"
                :show-upload-list="false"
                :action="$api.adminConfigsUploadIcon"
                :data="{token:$getSession('token_type')}"
                @change="icon"
            >
                <img width="60px" v-if="info.icon" :src="info.icon" />
                <div v-else>
                    <a-font type="iconplus" />
                </div>
            </a-upload>
        </a-form-model-item>
        <a-form-model-item label="电话">
            <a-input v-model="info.mobile"></a-input>
        </a-form-model-item>
        <a-form-model-item label="邮箱">
            <a-input v-model="info.email"></a-input>
        </a-form-model-item>
        <a-form-model-item label="备案信息">
            <a-input v-model="info.icp"></a-input>
        </a-form-model-item>
        <a-form-model-item label="关闭网站">
            <a-radio-group name="web_status" v-model="info.web_status">
                <a-radio value="1">开启</a-radio>
                <a-radio value="0">关闭</a-radio>
            </a-radio-group>
        </a-form-model-item>
        <a-form-model-item label="关闭原因">
            <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.web_close_info" />
        </a-form-model-item>
        <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
            <a-button type="primary" @click="handleSubmit">提交</a-button>
        </a-form-model-item>
    </a-form-model>
</template>

<script>
export default {
    data() {
      return {
          info:{
          },
      };
    },
    methods: {
        handleSubmit(){
            this.$post(this.$api.adminConfigs,this.info).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg)
                    return this.onload();
                }else{
                    return this.$message.error(res.msg)
                }
            })
        },
        get_info(){
            this.$get(this.$api.adminConfigs).then(res=>{
                let data = {
                    web_name:res.data.web_name,
                    title:res.data.title,
                    keywords:res.data.keywords,
                    description:res.data.description,
                    logo:res.data.logo,
                    icon:res.data.icon,
                    mobile:res.data.mobile,
                    email:res.data.email,
                    icp:res.data.icp,
                    web_status:res.data.web_status,
                    web_close_info:res.data.web_close_info
                }
                this.info = data;
            })
        },
        // 获取列表
        onload(){
            this.get_info();
        },
        logo(e){
            this.upload(e,'logo');
        },
        icon(e){
            this.upload(e,'icon');
        },
        upload(e,name){
            if(e.file.status == 'done'){
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,name,rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }
        }
        
    },
    created() {
        this.onload();
    },
};
</script>
