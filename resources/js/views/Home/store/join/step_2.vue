<template>
    <div class="store_join width_center_1200">
        <div class="step_bar">
            <div class="step">
                <div class="item success"><a-icon type="read" />阅读协议</div>
                <div class="item check"><a-icon type="edit" />填写资料</div>
                <div class="item"><a-icon type="coffee" />等待审核</div>
                <div class="item"><a-icon type="check-circle" />审核通过</div>
            </div>
        </div>
        <a-divider><font style="font-size:20px">入驻资料填写</font></a-divider>
        <div class="store_join_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="店铺名称">
                    <a-input v-model="info.store_name" />
                </a-form-model-item>
                <a-form-model-item label="店铺LOGO">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.homeStoreJoinUpload"
                        :data="{token:$getSession('token_type'),name:'store_logo'}"
                        @change="upload"
                    >
                        <img height="150px" v-if="info.store_logo" :src="info.store_logo" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
                </a-form-model-item>
                <a-form-model-item label="申请产品栏目">
                    <a-cascader :field-names="{ label: 'name', value: 'id', children: 'children' }" :options="goodsClasses" placeholder="" @change="goods_class_change" />
                    <a-tag v-for="(v,k) in choseClasses" :key="k" closable @close="closeTag(k)">{{v[0].name}} / {{v[1].name}} / {{v[2].name}}</a-tag>
                </a-form-model-item>
            </a-form-model>
        </div>
        <!-- <a-divider><font style="font-size:20px">企业信息</font></a-divider> -->
        <div class="store_join_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="公司名称">
                    <a-input v-model="info.store_company_name" />
                </a-form-model-item>
                <a-form-model-item label="公司地址">
                    <a-cascader v-model="info.area_id" :field-names="{ label: 'name', value: 'id', children: 'children' }" :options="areas" placeholder="" @change="area_change" />
                </a-form-model-item>
                <a-form-model-item label="详细地址">
                    <a-input v-model="info.store_address" />
                </a-form-model-item>
                <a-form-model-item label="营业执照">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.homeStoreJoinUpload"
                        :data="{token:$getSession('token_type'),name:'business_license'}"
                        @change="upload"
                    >
                        <img height="150px" v-if="info.business_license" :src="info.business_license" />
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
                        :action="$api.homeStoreJoinUpload"
                        :data="{token:$getSession('token_type'),name:'id_card_t'}"
                        @change="upload"
                    >
                        <img height="150px" v-if="info.id_card_t" :src="info.id_card_t" />
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
                        :action="$api.homeStoreJoinUpload"
                        :data="{token:$getSession('token_type'),name:'id_card_b'}"
                        @change="upload"
                    >
                        <img height="150px" v-if="info.id_card_b" :src="info.id_card_b" />
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
          goodsClasses:[],
          choseClasses:[],
          areas:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        next_step(){
            console.log(this.choseClasses)
            if(this.$isEmpty(this.info.store_name)){
                return this.$message.error('店铺名称不能为空');
            }
            if(this.$isEmpty(this.info.store_company_name)){
                return this.$message.error('公司名称不能为空');
            }
            if(this.$isEmpty(this.info.area_info)){
                return this.$message.error('地址不能为空');
            }
            if(this.$isEmpty(this.info.store_address)){
                return this.$message.error('详细地址不能为空');
            }
            if(this.$isEmpty(this.info.business_license)){
                return this.$message.error('营业执照不能为空');
            }
            if(this.$isEmpty(this.info.business_license_no)){
                return this.$message.error('统一社会信用代码不能为空');
            }
            if(this.$isEmpty(this.info.legal_person)){
                return this.$message.error('法人不能为空');
            }
            if(this.$isEmpty(this.info.store_phone)){
                return this.$message.error('法人电话不能为空');
            }
            if(this.$isEmpty(this.info.id_card_no)){
                return this.$message.error('身份证号码不能为空');
            }
            if(this.$isEmpty(this.info.id_card_t)){
                return this.$message.error('身份证(上)不能为空');
            }
            if(this.$isEmpty(this.info.id_card_b)){
                return this.$message.error('身份证(下)不能为空');
            }
            if(this.$isEmpty(this.info.emergency_contact)){
                return this.$message.error('紧急联系人不能为空');
            }
            if(this.$isEmpty(this.info.emergency_contact_phone)){
                return this.$message.error('紧急联系人电话不能为空');
            }
            if(this.choseClasses.length<=0){
                return this.$message.error('申请商品栏目不能为空');
            }
            let params = {};
            params = this.info;
            params.store_classes = this.choseClasses;
            this.$post(this.$api.homeStoreJoin,params).then(res=>{
                if(res.code == 200){
                    return this.$router.push('/store/join/step_3')
                }
                return this.$$message.error(res.msg);
            })
            
        },
        store_verify(){
            this.$get(this.$api.homeStoreVerify).then(res=>{
                if(res.code == 200){
                    if(res.data.store_verify == 2 || res.data.store_verify == 3 || res.data.store_verify == 0){
                        this.$router.push('/store/join/step_3')
                    }
                }else{
                    this.$router.push('/store/join/step_1')
                }
            })
        },
        // 获取商品栏目
        goods_classes(){
            this.$get(this.$api.homeGoodsClasses).then(res=>{
                this.goodsClasses = res.data;
            })
        },
        // 商品栏目修改
        goods_class_change(row,info){
            console.log(info)
            if(info != undefined){
                if(this.choseClasses.length>0){
                    let isSame = false;
                    this.choseClasses.forEach(item=>{
                        if((item[0].name+item[1].name+item[2].name)  == (info[0].name+info[1].name+info[2].name) ){
                            isSame = true;
                        }
                    })
                    if(!isSame){
                        this.choseClasses.push(info)
                    }
                }else{
                    this.choseClasses.push(info)
                }
            }
            
        },
        // 删除标签
        closeTag(e){
            this.choseClasses.splice(e,1);
        },
        // 获取地址
        get_areas(){
            this.$get(this.$api.homeAreas).then(res=>{
                this.areas = res.data
            })
        },
        // 获取店铺信息
        get_store(){
            this.$get(this.$api.homeStoreJoin).then(res=>{
                if(res.code == 200){
                    this.choseClasses = res.data.chose_store_classes
                    return this.info = res.data;
                }
                return this.$$message.error(res.msg);
            })
        },
        area_change(row,info){
            this.info.province_id = row[0];
            this.info.city_id = row[1];
            this.info.region_id = row[2];
            this.info.area_info = info[0].name+' '+info[1].name+' '+info[2].name
            console.log(this.info)
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,rs.data.name,rs.data.url);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
        }
    },
    created() {
        this.store_verify();
        this.goods_classes();
        this.get_areas();
        this.get_store();
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

</style>