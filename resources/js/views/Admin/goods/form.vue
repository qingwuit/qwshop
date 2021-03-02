<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            商品详情
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="商品标题" :rules="{ required: true}">{{info.goods_name}}</a-form-model-item>
                <a-form-model-item label="商品副标题">{{info.goods_subname}}</a-form-model-item>
                <a-form-model-item label="商品编号">{{info.goods_no||'-'}}</a-form-model-item>
                <a-form-model-item label="商品品牌" >{{goods_brand.name||'-'}}</a-form-model-item>
                <a-form-model-item label="商品分类" :rules="{ required: true}">
                    <a-tag><span v-for="(v,k) in info.goods_class" :key="k" style="font-size:14px">{{k==0?'&nbsp;&nbsp;':''}}{{v.name}}{{k==2?'&nbsp;&nbsp;':'&nbsp;&nbsp;/&nbsp;&nbsp;'}}</span></a-tag>
                </a-form-model-item>
                <a-form-model-item label="商品图片" :rules="{ required: true}">
                    <div class="goods_image">
                        <div class="item" v-if="info.goods_images.length>0">
                            <div class="item_img" v-for="(v,k) in info.goods_images" :key="k" >
                                <div class="item_master" v-if="info.goods_master_image==v"><a-icon type="check-circle" />&nbsp;主图展示</div>
                                <img :src="v" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="item noimg" v-else><a-font type="iconphoto" /></div>
                    </div>
                </a-form-model-item>
                <template v-if="skuList.length<=0">
                    <a-form-model-item label="平台价格" :rules="{ required: true}">￥{{info.goods_price}}</a-form-model-item>
                    <a-form-model-item label="市场价格" :rules="{ required: true}">￥{{info.goods_market_price}}</a-form-model-item>
                    <a-form-model-item label="商品重量" :rules="{ required: true}">{{info.goods_weight}}kg</a-form-model-item>
                    <a-form-model-item label="商品库存" :rules="{ required: true}">x {{info.goods_stock}} </a-form-model-item>
                </template>
                <a-form-model-item label="规格属性(SKU)">
                    
                   <!-- 规格SKU start -->
                   <div class="goods_specs" v-if="skuList.length>0">
                        <div class="row_th">
                            <a-row>
                                <a-col class="col_th" :span="4">SKU</a-col>
                                <a-col class="col_th" :span="4">市场价</a-col>
                                <a-col class="col_th" :span="4">平台价</a-col>
                                <a-col class="col_th" :span="4">重量</a-col>
                                <a-col class="col_th" :span="4">库存</a-col>
                                <a-col class="col_th" :span="4">图片</a-col>
                            </a-row>
                        </div>
                        <div class="row_td">
                            <a-row :gutter="16" v-for="(v,k) in skuList" :key="k">
                                <a-col class="col_th" :span="4">{{v.sku_name.join(' ')}}</a-col>
                                <a-col class="col_th" :span="4">￥{{v.goods_market_price}}</a-col>
                                <a-col class="col_th" :span="4">￥{{v.goods_price}}</a-col>
                                <a-col class="col_th" :span="4">{{v.goods_weight}}kg</a-col>
                                <a-col class="col_th" :span="4">x {{v.goods_stock}}</a-col>
                                <a-col class="col_th" :span="4">-</a-col>
                            </a-row>
                        </div>
                    </div>
                    <!-- 规格sku end -->
                </a-form-model-item>
                <a-form-model-item label="商品详情">
                    <div>
                        <span :class="platform?'admin_editor_span':'admin_editor_span check'" @click="check_platform(false)">PC端</span>
                        <span :class="platform?'admin_editor_span check':'admin_editor_span'" @click="check_platform(true)">Mobile端</span>
                        <div v-if="platform" class="goods_content" v-html="info.goods_content"></div>
                        <div v-else class="goods_content" v-html="info.goods_content_mobile"></div>
                    </div>
                </a-form-model-item>
                <a-form-model-item label="上架状态">{{info.goods_status==1?'是':'否'}}</a-form-model-item>
                <a-form-model-item label="审核状态">{{info.goods_verify==1?'审核通过':(info.goods_verify==0?'审核失败':'等待审核')}}</a-form-model-item>

                <a-form-model-item label="拒绝原因" v-if="info.goods_verify == 2 || info.goods_verify == 0"><a-textarea :disabled="info.goods_verify==0" v-model="info.refuse_info" /></a-form-model-item>
                
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }" v-if="info.goods_verify == 2 ">
                    <a-button type="primary" @click="handleSubmit(true)" style="margin-right:15px;">同意</a-button>
                    <a-button type="danger" @click="handleSubmit(false)">拒绝</a-button>
                </a-form-model-item>
            </a-form-model>

            
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              goods_images:[],
          },
          list:[],
          platform:false, // 平台PC false 手机 TRUE
          id:0,
          goods_brand:{
              name:'',
          },

          // 构建sku
          skuList:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(e){

            // 验证代码处
            // if(this.$isEmpty(this.info.name)){
            //     return this.$message.error('分类名不能为空');
            // }

            let params = {
                goods_verify:e?1:0,
                refuse_info:e?'':this.info.refuse_info,
            }
            this.$put(this.$api.adminGoods+'/'+this.info.id,params).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg)
                    return this.$router.back();
                }else{
                    return this.$message.error(res.msg)
                }
            })
     
   
        },
        get_info(){
            this.$get(this.$api.adminGoods+'/'+this.id).then(res=>{
                this.skuList = res.data.skuList||[];
                this.goods_brand = res.data.goods_brand;
                res.data.goods_status = res.data.goods_status==0?false:true;
                this.info = res.data;
                this.$forceUpdate();
            })
        },

        onload(){

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }
        },
        // 编辑器切换平台
        check_platform(status){
            this.platform = status;
        },
        
        
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.goods_content{
    border:1px solid #efefef;
    padding:20px;
    margin-top: 20px;
    margin-bottom: 15px;
}
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
.goods_specs{
    border:1px solid #efefef;
    margin-top: 10px;
}
.row_td{
    padding:8px;
}
.goods_image{
    .item{
        &.noimg{
            width:160px;
            height: 160px;
            background: #efefef;
            text-align: center;
            border-radius: 4px;
            i{
                font-size: 40px;
                line-height: 160px;
                color:#999;
            }
        }
        .item_img{
            width: 160px;
            height: 160px;
            display: block;
            float: left;
            box-sizing: border-box;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            border:1px solid #efefef;
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
.row_th{
    background: #efefef;
}
.col_th{
    text-align: center;
}
</style>