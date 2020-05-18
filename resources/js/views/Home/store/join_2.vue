<template>
    <div class="join_1">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        
        <div class="join_over width_center_1200">
            <div class="join_bzt">
                <el-steps :active="0" simple>
                    <el-step title="提交入驻资料" icon="el-icon-edit"></el-step>
                    <el-step title="商家等待审核" icon="el-icon-unlock"></el-step>
                    <el-step title="完善店铺信息" icon="el-icon-tickets"></el-step>
                    <el-step title="店铺上线" icon="el-icon-goods"></el-step>
                </el-steps>
            </div>
        </div>
        <div class="join_over width_center_1200">
            <div class="join_over_title"><el-divider>公司信息</el-divider><p>COMPANY INFORMATION SUBMISSION</p></div>
            <div class="company_info">
                <el-form  label-width="140px" ref="info" :model="info">
                    <el-form-item label="店铺名称" prop="store_name" :rules="[{required:true,message:'店铺名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.store_name"></el-input></el-form-item>
                    <el-form-item label="店铺介绍" prop="store_description"><el-input type="textarea" placeholder="请输入内容" v-model="info.store_description"></el-input></el-form-item>
                    <el-form-item label="公司名称" prop="store_company_name" :rules="[{required:true,message:'公司名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.store_company_name"></el-input></el-form-item>
                    <el-form-item label="营业执照注册号" prop="business_license_no" :rules="[{required:true,message:'营业执照注册号不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.business_license_no"></el-input></el-form-item>
                    <el-form-item label="法定代表人姓名" prop="legal_person" :rules="[{required:true,message:'法定代表人姓名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.legal_person"></el-input></el-form-item>
                    <el-form-item label="身份证号" prop="id_card_no" :rules="[{required:true,message:'身份证号不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.id_card_no"></el-input></el-form-item>
                    <el-form-item label="身份证(正)" prop="id_card"  :rules="[{required:true,message:'身份证不能为空',trigger: 'blur' }]"><el-upload class="avatar-uploader" :action="$api.homeAutoUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.id_card" :src="info.id_card" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
                    <el-form-item label="营业执照副本" prop="business_license"  :rules="[{required:true,message:'营业执照副本不能为空',trigger: 'blur' }]"><el-upload class="avatar-uploader" :action="$api.homeAutoUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess2" >
                        <img v-if="info.business_license" :src="info.business_license" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
                    <el-form-item label="公司地址" prop="area_info" :rules="[{required:true,message:'公司地址不能为空',trigger: 'blur' }]"><el-cascader v-model="info.area_info" :props="props" :options="area_list"></el-cascader></el-form-item>
                    <el-form-item label="详细地址" prop="store_address" :rules="[{required:true,message:'公司详细地址不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容"  v-model="info.store_address"></el-input></el-form-item>
                    <el-form-item label="公司电话" prop="store_mobile" :rules="[{required:true,message:'公司电话不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.store_mobile"></el-input></el-form-item>
                    <el-form-item label="紧急联系人" prop="emergency_contact" :rules="[{required:true,message:'紧急联系人不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容"  v-model="info.emergency_contact"></el-input></el-form-item>
                    <el-form-item label="紧急联系人电话" prop="emergency_contact_phone" :rules="[{required:true,message:'紧急联系人电话不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容"  v-model="info.emergency_contact_phone"></el-input></el-form-item>
                    <el-form-item label="申请商品分类" prop="class_info" :rules="[{required:true,message:'申请商品分类不能为空',trigger: 'blur' }]"><el-cascader v-model="info.class_info" :props="props2" :options="class_list"></el-cascader></el-form-item>
                </el-form>
            </div> 
            <div class="join_btn_next" @click="submitForm('info')">
                下一步
            </div>
        </div>
        <shop-foot></shop-foot>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    props: {
    },
    data() {
      return {
          info:{
              area_info:[],
              store_description:'',
          },
          upload_headers:{},

          area_list:[],
          area_info:[],
          class_list:[],
          // 省市区动态加载
          props:{
              value:'id',
              label:'name',
          },
          props2:{
              multiple:true,
              value:'id',
              label:'name',
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            let _this = this;
            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    let url = _this.$api.homeStoreJoin;
                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("申请入驻成功");
                            _this.$router.push('/store/join_4');
                        }else{
                            _this.$message.error("申请入驻失败");
                        }
                    });
                }
            });
        },
        // 获取当前入驻状态
        get_store_info:function(){
            let url = this.$api.homeStoreJoin;
            this.$get(url).then(res=>{
                if(res.code == 500){
                    return;
                }
                this.info = res.data.store_info;
                if(!this.$isEmpty(this.info)){
                    if(this.info.store_verify == 2){  // 申请失败
                        this.$message.error('申请失败，请询问工作人员再次申请！');
                        return this.$router.push('/store/join_2');
                    }else if(this.info.store_verify == 1){ // 申请成功
                        return this.$router.push('/store/join_3');
                    }else if(this.info.store_verify == 0){
                        return this.$router.push('/store/join_4');
                    }
                }
            });
        },
        // 身份证
        handleAvatarSuccess(res) {
            this.info.id_card = res.data;
            this.$forceUpdate();
        },
        // 营业执照副本
        handleAvatarSuccess2(res) {
            this.info.business_license = res.data;
            this.$forceUpdate();
        },
        get_area_list:function(){
            this.$get(this.$api.homeGetAreaList).then(res=>{
                this.area_list = res.data;
            });
        },
        get_goods_class_list:function(){
            this.$get(this.$api.homeGetGoodsClassList).then(res=>{
                this.class_list = res.data;
            });
        },
        
        
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        this.get_store_info();
        this.get_area_list();
        this.get_goods_class_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>


.join_over_title{
    padding-top:40px;
    width: 300px;
    text-align: center;
    margin:0 auto;
    .el-divider__text{
        font-size: 22px;
    }
    p{
        font-size: 12px;
        color:#999;
    }
}
.join_bzt{
    margin-top:50px;
}
.company_info{
    padding-top: 40px;
    width: 600px;
    margin:0 auto;
}
.join_btn_next{
    margin:0 auto;
    margin-bottom: 40px;
    margin-top:30px;
    line-height: 40px;
    background: #ca151e;
    width: 120px;
    text-align: center;
    color:#fff;
    font-size: 14px;
}
</style>