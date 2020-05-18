<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>积分产品添加</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="商品名" prop="goods_name" :rules="[{required:true,message:'商品名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.goods_name"></el-input></el-form-item>
                    <el-form-item label="商品分类">
                        <el-select v-model="info.cid" placeholder="请选择">
                            <el-option label="请选择分类" :value="0"></el-option>
                            <el-option v-for="(v,k) in class_list" :key="k" :label="v.name" :value="v.id"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="商品主图" prop="thumb" class="width_auto">
                        <el-upload :action="$api.integralUpload" :headers="upload_headers" list-type="picture-card" ref="upload" :file-list="file_list2" :limit="5" :multiple="true"  :on-success="handleAvatarSuccess" :on-exceed="onExceed" >
                            <!-- <i  class="el-icon-plus avatar-uploader-icon"></i> -->
                            <i slot="default" class="el-icon-plus"></i>
                            <div slot="file" slot-scope="{file}">
                                <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" >
                                <div class="is_master" :style="get_master_image(file)"><i class="el-icon-finished"> 主图</i></div>
                                <span class="el-upload-list__item-actions">
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="setMaster(file)" >
                                        <i class="el-icon-finished"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handlePictureCardPreview(file)" >
                                        <i class="el-icon-zoom-in"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handleRemove(file)" >
                                        <i class="el-icon-delete"></i>
                                    </span>
                                </span>
                            </div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="商品积分" prop="goods_price">
                        <el-input placeholder="0.00" type="number"  v-model="info.goods_price">
                            <template slot="append"><i class="el-icon-coin"></i></template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="市场价格" prop="goods_market_price"><el-input type="number" placeholder="0.00" v-model="info.goods_market_price"></el-input></el-form-item>
                    <el-form-item label="商品库存" prop="goods_num"><el-input  placeholder="0" type="age" v-model.number="info.goods_num"></el-input></el-form-item>
                    
                    <el-form-item label="商品详情" class="width_auto_70">
                        <wangeditor @goods_content="goods_content"></wangeditor>
                    </el-form-item>
                    
                    <el-form-item label="是否上架" prop="goods_status">
                        <el-switch v-model="info.goods_status" active-color="#13ce66" :active-value="1" :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="是否推荐" prop="is_hot">
                        <el-switch v-model="info.is_hot" active-color="#13ce66" :active-value="1" :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">发布积分产品</el-button>
                    </el-form-item>
                </el-form>
                   
            </div>
        </div>



        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>


    </div>

</template>

<script>

import wangeditor from "@/components/seller/wangeditor.vue"
export default {
    components: {
        wangeditor,
    },
    props: {},
    data() {
      return {
          info:{
              cid:0,
              goods_status:1,
              is_hot:0,
              goods_market_price:0.00,
              goods_price:0.00,
              goods_num:0,
              goods_master_image:'',
          },
          class_list:[],
          upload_headers:{},
          file_list:[],
          file_list2:[], // element 内置file_list
          dialogVisible:false,// 是否打开预览
          dialogImageUrl:'', // 预览
          disabled:false,
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
        },
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    // 图片 验证
                    if(_this.file_list.length<=0){
                        return _this.$message.error('商品主图至少上传一张');
                    }

                    if(_this.info.goods_price<=0 || _this.info.goods_market_price<=0 || _this.info.goods_num<=0){
                        return _this.$message.error('价格或库存没填写，或者填写错误！');
                    }else{
                        _this.add_goods();
                    }

                }
            });
        },
        
        selectChange:function(){
            this.$forceUpdate();
        },
        handleAvatarSuccess(res) {
            if(this.file_list.length==0){
                this.info.goods_master_image = res.data;
            }
            this.file_list.push(res.data);
            this.$forceUpdate();
        },
        handlePictureCardPreview:function(file){
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        // 文件删除
        handleRemove:function(file){
            // console.log(file,this.$refs.upload);
            this.$refs.upload.handleRemove(file);
            let index = this.file_list.indexOf(file.url);
            this.file_list.splice(index,1);
        },
        // 设置主图
        setMaster:function(file){
            this.info.goods_master_image = file.response.data;
        },
        // 上传超过限制
        onExceed:function(){
            this.$message.error('商品展示图片不能超过5个');
        },
        
        get_master_image:function(file){
            if(file.response != undefined){
                if(file.response.data == this.info.goods_master_image){
                    return 'display:block';
                }else{
                    return 'display:none';
                }
            }
        },
        
        // 富文本编辑内容变化
        goods_content:function(data){
            this.info.content = data;
        },
        // 添加商品
        add_goods:function(){
            let info = this.info;
            info.file_list = this.file_list; // 图片

            // 请求
            this.$post(this.$api.addIntegral,info).then(res=>{
                if(res.code == 200){
                    this.$message.success('添加成功');
                    this.$router.go(-2);
                }else{
                    this.$message.error(res.msg);
                }
            });
        },
        
        
        // 获取添加初始化信息
        get_goods_add_info:function(){
            this.$get(this.$api.addIntegral).then(res=>{
                if(res.code == 500){
                    this.$message.error(res.msg);
                    this.$router.go(-1);
                }else{
                    this.class_list = res.data.integral_class;
                }
            });
        }
    },
    created() {
        this.get_goods_add_info();
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
     
        
    },
    mounted() {
        
    }
};
</script>
<style lang="scss" scoped>
.el-breadcrumb{
    line-height: 32px;
}
.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 146px;
    height: 146px;
    line-height: 146px;
    text-align: center;
}
.avatar-upload{
    width: 146px;
    height: 146px;
}

.is_master{
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(0,0,0,0.5);
    color:#fff;
    width: 164px;
    text-align: center;
    display: none;
}
.goods_class_add_left{
    float: left;
    margin-right: 15px;
    // border:1px solid #e1e1e1;
    padding:0 20px;
    border-radius: 5px;
    background: #f1f1f1;
}
</style>