<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            店铺信息
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-descriptions bordered layout="vertical" :column="{ xxl: 4, xl: 4, lg: 2, md: 2, sm: 2, xs: 1 }">
                <a-descriptions-item label="店铺名">{{info.store_name}}</a-descriptions-item>
                <a-descriptions-item label="店铺Logo" >
                    <div v-viewer>
                        <img height="120px" :src="info.store_logo" alt="">
                    </div>
                </a-descriptions-item>
                <a-descriptions-item label="店铺状态">
                    <a-tag v-if="info.store_status==1" color="green">正常</a-tag>
                    <a-tag v-if="info.store_status==0" color="red">关闭</a-tag>
                </a-descriptions-item>
                <a-descriptions-item label="审核状态">
                    <a-tooltip v-if="info.store_verify==0" placement="topLeft" :title="info.store_refuse_info"><a-tag color="red">{{info.store_verify_cn}}</a-tag></a-tooltip>
                    <a-tag v-if="info.store_verify==1" color="orange">{{info.store_verify_cn}}</a-tag>
                    <a-tag v-if="info.store_verify==2" color="blue">{{info.store_verify_cn}}</a-tag>
                    <a-tag v-if="info.store_verify==3" color="green">{{info.store_verify_cn}}</a-tag>
                </a-descriptions-item>
                <a-descriptions-item label="店铺描述" :span="4">{{info.store_description}}</a-descriptions-item>
                <a-descriptions-item label="企业名称">{{info.store_company_name}}</a-descriptions-item>
                <a-descriptions-item label="企业电话">{{info.store_mobile}}</a-descriptions-item>
                <a-descriptions-item label="所在地区">{{info.area_info}}</a-descriptions-item>
                <a-descriptions-item label="地址详情">{{info.store_address}}</a-descriptions-item>
                <a-descriptions-item label="营业执照" :span="1">
                    <div v-viewer>
                        <img height="250px" :src="info.business_license" alt="">
                    </div>
                </a-descriptions-item>
                <a-descriptions-item label="身份证" :span="3">
                    <div v-viewer>
                        <img height="250px" :src="info.id_card_t" alt="">-
                        <img height="250px" :src="info.id_card_b" alt="">
                    </div>
                </a-descriptions-item>
                <a-descriptions-item label="营业执照号码">{{info.business_license_no}}</a-descriptions-item>
                <a-descriptions-item label="身份证号码">{{info.id_card_no}}</a-descriptions-item>
                <a-descriptions-item label="法人姓名">{{info.legal_person}}</a-descriptions-item>
                <a-descriptions-item label="法人电话">{{info.store_phone}}</a-descriptions-item>
                <a-descriptions-item label="紧急联系人">{{info.emergency_contact}}</a-descriptions-item>
                <a-descriptions-item label="紧急联系人电话">{{info.emergency_contact_phone}}</a-descriptions-item>
                <a-descriptions-item label="申请时间">{{info.created_at}}</a-descriptions-item>
                <a-descriptions-item label="修改时间">{{info.updated_at}}</a-descriptions-item>
                <a-descriptions-item label="店铺余额" :span="4"><font style="font-size:18px;font-weight:bold;color:red;">{{info.store_money}}</font></a-descriptions-item>
            </a-descriptions>
            
            <div class="verify_btn">
                <a-form-model :label-col="{ span: 2 }" :wrapper-col="{ span: 18 }">
                    <a-form-model-item label="店铺状态">
                        <a-select v-model="info.store_status">
                            <a-select-option :value="1">开启</a-select-option>
                            <a-select-option :value="0">关闭</a-select-option>
                        </a-select>
                    </a-form-model-item>
                    <a-form-model-item label="审核状态">
                        <a-select v-model="info.store_verify" @change="store_verify_change">
                            <a-select-option :value="3">通过</a-select-option>
                            <a-select-option :value="1">重新填写资料</a-select-option>
                            <a-select-option :value="2">等待审核</a-select-option>
                            <a-select-option :value="0">拒绝</a-select-option>
                        </a-select>
                    </a-form-model-item>
                    <a-form-model-item label="拒绝原因" v-if="info.store_verify==0">
                        <a-textarea placeholder="输入拒绝的原因" allow-clear v-model="info.store_refuse_info" />
                    </a-form-model-item>
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 2 }">
                        <a-button type="primary" @click="handleSubmit">提交状态</a-button>
                    </a-form-model-item>
                </a-form-model>
                
            </div>
        </div>
    </div>
</template>

<script>
import Viewer from 'v-viewer'
import 'viewerjs/dist/viewer.css'
Vue.use(Viewer)
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{},
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            let api = this.$apiHandle(this.$api.adminStores,this.id);
            if(api.status){
                this.$put(api.url,this.info).then(res=>{
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
            this.$get(this.$api.adminStores+'/'+this.id).then(res=>{
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
        },
        
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

.verify_btn{
    margin-top: 30px;
}
</style>