<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            商品编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="商品标题">
                    <a-input v-model="info.goods_name" />
                </a-form-model-item>
                <a-form-model-item label="商品副标题">
                    <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.description" />
                </a-form-model-item>
                <a-form-model-item label="商品编号">
                    <a-input v-model="info.goods_name" />
                </a-form-model-item>
                <a-form-model-item label="商品品牌">
                    <a-select>
                        <a-select-option key="" value="">测试</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="商品分类">
                    <a-select>
                        <a-select-option key="" value="">测试</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="商品图片">
                    <div class="goods_image">
                        <div class="item" v-if="info.goods_images.length>0">
                            <div class="item_img" v-for="(v,k) in info.goods_images" :key="k" >
                                <div class="item_bg"><a-icon @click="setMaster(k)" type="check" /><a-icon @click="deleteImg(k)" type="delete" /></div>
                                <div class="item_master" v-if="info.goods_master_image==v"><a-icon type="check-circle" />&nbsp;主图展示</div>
                                <img :src="v" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="item noimg" v-else><a-font type="iconphoto" /></div>
                    </div>
                    <div class="goods_upload_btn">
                        <a-upload
                            :action="$api.sellerGoodsUpload"
                            :data="{token:$getSession('token_type')}"
                            :multiple="true"
                            :show-upload-list="false"
                            :before-upload="beforeUpload"
                            @change="upload"
                        >
                            <a-button type="primary">上传图片</a-button>
                        </a-upload>
                        <a-button icon="picture" @click="$message.info('暂未开通')">图片空间</a-button>
                    </div>
                </a-form-model-item>
                <a-form-model-item label="平台价格">
                    <a-input v-model="info.goods_price" type="number" suffix="￥" />
                </a-form-model-item>
                <a-form-model-item label="市场价格">
                    <a-input v-model="info.goods_market_price" type="number" suffix="￥" />
                </a-form-model-item>
                <a-form-model-item label="商品重量">
                    <a-input v-model="info.goods_weight" type="number" suffix="Kg" />
                </a-form-model-item>
                <a-form-model-item label="商品库存">
                    <a-input v-model="info.goods_stock" type="number">
                        <a-icon slot="suffix" type="stock"></a-icon>
                    </a-input>
                </a-form-model-item>
                <a-form-model-item label="规格属性(SKU)">
                   
                </a-form-model-item>
                <a-form-model-item label="商品详情">
                    <div>
                        <span class="admin_editor_span check">PC端</span>
                        <span class="admin_editor_span">Mobile端</span>
                        <wang-editor />
                    </div>
                </a-form-model-item>
                <a-form-model-item label="上架状态">
                    <a-switch  v-model="info.goods_status" />
                </a-form-model-item>
                
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>
            
        </div>
    </div>
</template>

<script>
import wangEditor from "@/components/seller/wangeditor"
export default {
    components: {wangEditor},
    props: {},
    data() {
      return {
          info:{
              pid:0,
              goods_images:[],
          },
          list:[],
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('分类名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.adminGoods,this.id);
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
            this.$get(this.$api.adminGoods+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取菜单列表
        onload(){
            let is_type = this.$route.query.is_type;
            let params = {};
            if(!this.$isEmpty(is_type)){
                params.is_type = is_type;
                this.info.is_type = parseInt(is_type);
            }

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }

            // this.$get(this.$api.adminGoodsBrands,params).then(res=>{
            //     this.list = res.data;
            // });
        },
        // 删除图片
        deleteImg(e){
            if(this.info.goods_images.length>1 && this.info.goods_images[e] == this.info.goods_master_image){
                this.setMaster(0);
            }
            if(this.info.goods_images.length==1){
                this.info.goods_master_image = '';
            }
            this.info.goods_images.splice(e,1);
        },
        // 设置主图
        setMaster(e){
            this.$set(this.info,'goods_master_image',this.info.goods_images[e]);
        },
        upload(e){
            if(e.file.status == 'done'){
                let rs = e.file.response;
                let imgs = this.info.goods_images;
                let allowSetMaster = false;
                if(rs.code == 200){
                    if(imgs.length==0){
                        allowSetMaster = true;
                    }
                    imgs.push(rs.data);
                    this.$set(this.info,'goods_images',imgs);
                    this.setMaster(0);
                }else{
                    return this.$message.error(rs.msg);
                }
            }
            
        },
        beforeUpload(file,fileList){
            if(fileList.length+this.info.goods_images.length>5){
                this.$message.error('No more than 5 pictures')
                return false;
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
.admin_editor_span{
    margin-right: 10px;
    border:1px solid #efefef;
    line-height: 30px;
    padding: 4px 10px;
    border-radius: 3px;
    margin-bottom: 10px;
    cursor: pointer;
    &:hover{
        border-color: #ccc;
    }
    &.check{
        border-color: #ccc;
    }
}
.goods_upload_btn{
    margin-top: 10px;
}
.goods_image{
    .item{
        &.noimg{
            width:110px;
            height: 110px;
            background: #efefef;
            text-align: center;
            border-radius: 4px;
            i{
                font-size: 30px;
                line-height: 110px;
                color:#999;
            }
        }
        .item_img{
            width: 110px;
            height: 110px;
            display: block;
            float: left;
            box-sizing: border-box;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            .item_bg{
                border-radius: 4px;
                text-align: center;
                line-height: 110px;
                display: none;
                width: 100%;
                height: 100%;
                position: absolute;
                color:#fff;
                top:0;
                left:0;
                background: rgba(0,0,0,0.5);/* IE9、标准浏览器、IE6和部分IE7内核的浏览器(如QQ浏览器)会读懂 */
                i{
                    padding: 0 5px;
                    font-size: 16px;
                    cursor: pointer;
                }
            }
            @media \0screen\,screen\9 {/* 只支持IE6、7、8 */
                .item_bg{
                    background-color:#000;
                    filter:Alpha(opacity=50);
                    position:static; /* IE6、7、8只能设置position:static(默认属性) ，否则会导致子元素继承Alpha值 */
                    *zoom:1; /* 激活IE6、7的haslayout属性，让它读懂Alpha */
                }
                .item_bg span{
                    position: relative;/* 设置子元素为相对定位，可让子元素不继承Alpha值 */
                }  
            }
            img{
                width: 100%;
                height: 100%;
                border-radius: 4px;
            }
            .item_master{
                position: absolute;
                left:0;
                bottom: 0;
                border-radius: 0 0 4px 4px;
                background: #000;
                line-height: 26px;
                height: 26px;
                width: 100%;
                z-index: 3;
                background: rgba(0,0,0,0.5);
                color:#fff;
                text-align: center;
                font-size: 12px;
            }
            &:hover .item_bg{
                display: block;
            }
        }
    }
}
</style>