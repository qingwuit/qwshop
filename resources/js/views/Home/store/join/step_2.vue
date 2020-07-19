<template>
    <div class="store_join width_center_1200">
        <div class="step">
            <div class="item success"><a-icon type="read" />阅读协议</div>
            <div class="item check"><a-icon type="edit" />填写资料</div>
            <div class="item"><a-icon type="coffee" />等待审核</div>
            <div class="item"><a-icon type="check-circle" />审核通过</div>
        </div>
        <a-divider><font style="font-size:20px">入驻资料填写</font></a-divider>
        <div class="store_join_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="店铺名称">
                    <a-input v-model="info.store_name" />
                </a-form-model-item>
                <a-form-model-item label="申请栏目">
                    <a-input v-model="info.store_name" />
                </a-form-model-item>
            </a-form-model>
        </div>
        <!-- <a-divider><font style="font-size:20px">企业信息</font></a-divider> -->
        <div class="store_join_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="公司名称">
                    <a-input v-model="info.store_company_name" />
                </a-form-model-item>
                <a-form-model-item label="公司名称">
                    <a-input v-model="info.store_company_name" />
                </a-form-model-item>
                <a-form-model-item label="公司地址">
                    <a-select>
                        <a-select-option key="" value="">测试</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="详细地址">
                    <a-input v-model="info.store_address" />
                </a-form-model-item>
                <a-form-model-item label="营业执照">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.adminGoodsBrandsUploadThumb"
                        :data="{token:$getSession('token_type')}"
                        @change="upload"
                    >
                        <img v-if="info.business_license" :src="info.business_license" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
                </a-form-model-item>
                <a-form-model-item label="统一社会信用代码">
                    <a-input v-model="info.business_license_no" />
                </a-form-model-item>
                <a-form-model-item label="法人">
                    <a-input v-model="info.legal_person" />
                </a-form-model-item>
                <a-form-model-item label="法人联系方式">
                    <a-input v-model="info.store_phone" />
                </a-form-model-item>
                <a-form-model-item label="身份证号码">
                    <a-input v-model="info.id_card_no" />
                </a-form-model-item>
                <a-form-model-item label="身份证(上)">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.adminGoodsBrandsUploadThumb"
                        :data="{token:$getSession('token_type')}"
                        @change="upload"
                    >
                        <img v-if="info.id_card_t" :src="info.id_card_t" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
                </a-form-model-item>
                <a-form-model-item label="身份证(下)">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.adminGoodsBrandsUploadThumb"
                        :data="{token:$getSession('token_type')}"
                        @change="upload"
                    >
                        <img v-if="info.id_card_b" :src="info.id_card_b" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
                </a-form-model-item>
                <a-form-model-item label="紧急联系人">
                    <a-input v-model="info.emergency_contact" />
                </a-form-model-item>
                <a-form-model-item label="紧急联系人电话">
                    <a-input v-model="info.emergency_contact_phone" />
                </a-form-model-item>
            </a-form-model>
        </div>
        <div class="agreement_btn"><a-button type="primary" @click="next_step">确认资料，提交审核</a-button></div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          loading:false,
          info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        next_step(){
            return this.$router.push('/store/join/step_3')
        },
        store_verify(){
            this.$get(this.$api.homeStoreVerify).then(res=>{
                if(res.code == 200){
                    if(res.data.store_verify == 2){
                        this.$router.push('/store/join/step_3')
                    }
                    else{
                        this.$router.push('/store/join/step_1')
                    }
                }else{
                    this.$router.push('/store/join/step_1')
                }
            })
        }
    },
    created() {
        this.store_verify();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.agreement_btn{
    margin:20px 0 10px 0;
    text-align: center;
}
.store_join_form{
    margin-top: 60px;
}
.step{
    height: 46px;
    line-height: 46px;
    background: #F5F7FA;
    margin-bottom: 50px;
    .item{
        font-size: 16px;
        color:#C0C4CC;
        float: left;
        width: 25%;
        text-align: center;
        border-right: 4px solid #fff;
        i{
            margin-right: 10px;
        }
        &.check{
            color:#333;
            font-weight: bold;
        }
        &.success{
            color:#67C23A;
        }
        &:last-child{
            margin-right: 0px;
        }
    }
}
</style>