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
                    <a-input v-model="info.goods_name" placeholder="HXC18475456841" />
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
                        <a-button icon="picture" @click="$message.info('暂未开发')">图片空间</a-button>
                    </div>
                </a-form-model-item>
                <template v-if="skuList.length<=0">
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
                </template>
                <a-form-model-item label="规格属性(SKU)">
                    <div class="attr_modal">
                        <div class="attr_item" v-for="(v,k) in goodsAttr" :key="k">
                            <span style="margin-right:10px;margin-left:8px">{{v.name}}：</span>
                            <a-checkbox v-for="(vo,key) in v.specs" :key="key" @change="specChange(v,vo)" :value="vo.check">{{vo.name}}</a-checkbox>
                            <a-tag style="background: #fff; borderStyle: dashed;">
                                <a-icon type="plus" /> 添加
                            </a-tag>
                        </div>
                    </div>
                   <div class="seller_goods_form_btn"><a-button type="primary" icon="plus" @click="open_attr_modal">选择属性</a-button></div>
                   
                   <!-- 规格SKU start -->
                   <div class="goods_specs" v-if="skuList.length>0">
                        <div class="row_th">
                            <a-row>
                                <a-col class="col_th" :span="4">SKU</a-col>
                                <a-col class="col_th" :span="4">市场价</a-col>
                                <a-col class="col_th" :span="4">平台价</a-col>
                                <a-col class="col_th" :span="4">库存</a-col>
                                <a-col class="col_th" :span="4">重量</a-col>
                            </a-row>
                        </div>
                        <div class="row_td">
                            <a-row :gutter="16" v-for="(v,k) in skuList" :key="k">
                                <a-col class="col_th" :span="4">{{v.sku_name.join(' ')}}</a-col>
                                <a-col :span="4"><a-input v-model="v.goods_market_price" type="number" suffix="￥" /></a-col>
                                <a-col :span="4"><a-input v-model="v.goods_price" type="number" suffix="￥" /></a-col>
                                <a-col :span="4"><a-input v-model="v.goods_weight" type="number" suffix="Kg" /></a-col>
                                <a-col :span="4">
                                    <a-input type="number" v-model="v.goods_stock">
                                        <a-icon slot="suffix" type="stock"></a-icon>
                                    </a-input>
                                </a-col>
                            </a-row>
                        </div>
                    </div>
                    <!-- 规格sku end -->
                </a-form-model-item>
                <a-form-model-item label="商品详情">
                    <div>
                        <span :class="platform?'admin_editor_span':'admin_editor_span check'" @click="check_platform(false)">PC端</span>
                        <span :class="platform?'admin_editor_span check':'admin_editor_span'" @click="check_platform(true)">Mobile端</span>
                        <wang-editor :contents="goods_content" @goods_content="goods_content_fun" />
                    </div>
                </a-form-model-item>
                <a-form-model-item label="上架状态">
                    <a-switch  v-model="info.goods_status" />
                </a-form-model-item>
                
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>

            <!-- 属性规格选择 -->
            <goods-attr-modal :attrVisible="attrVisible" @goods_attr_modal_cancel="attrVisible=false" @goods_attr="e=>{goodsAttr=e;attrVisible=false}" />
            
        </div>
    </div>
</template>

<script>
import wangEditor from "@/components/wangeditor"
import GoodsAttrModal from "@/components/seller/goods_attr_modal"
export default {
    components: {wangEditor,GoodsAttrModal},
    props: {},
    data() {
      return {
          info:{
              pid:0,
              goods_images:[],
          },
          list:[],
          platform:false, // 平台PC false 手机 TRUE
          goods_content:'', // 商品详情
          id:0,

          // 规格属性modal
          attrVisible:false,
          goodsAttr:[],

          // 构建sku
          skuList:[],
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
        // 上传图片前
        beforeUpload(file,fileList){
            if(fileList.length+this.info.goods_images.length>5){
                this.$message.error('No more than 5 pictures')
                return false;
            }
        },
        // 编辑器内容修改
        goods_content_fun(val){
            if(!this.platform){
                this.info.goods_content = val;
            }else{
                this.info.goods_content_mobile = val;
            }
            this.goods_content = val;
        },
        // 编辑器切换平台
        check_platform(status){
            this.platform = status;
            if(!status){
                this.goods_content = this.info.goods_content??'';
            }else{
                this.goods_content = this.info.goods_content_mobile??'';
            }
        },
        // 打开属性modal
        open_attr_modal(){
            this.attrVisible = true;
        },
        // 规格选择
        specChange(attrs,specs){
            let index = -1;
            this.goodsAttr.forEach((items,key)=>{
                if(items.id == attrs.id){
                    index = key;
                }
            })
            this.goodsAttr[index].specs.forEach(items=>{
                if(items.id == specs.id){
                    if(this.$isEmpty(items.check)){
                        items.check = true;
                    }
                    items.check = !items.check;
                }
            })
            this.structureSku();
            this.$forceUpdate();
        },
        // 构建SKU
        structureSku(){
            let skuList = [];
            let attrList = [];
            let attrListName = [];
            this.goodsAttr.forEach((items,key)=>{
                items.specs.forEach(specItem=>{
                    if(specItem.check){
                        if(this.$isEmpty(attrList[key])){
                            attrList[key] = [];
                            attrListName[key] = [];
                        }
                        attrList[key].push(specItem.id);
                        attrListName[key].push(specItem.name);
                    }
                })
            })
            if(attrList.length<=0){
                return this.skuList = [];
            }
            let attrName = this.cartesianProduct(attrListName);
            let attrId = this.cartesianProduct(attrList);
            attrId.forEach((items,key)=>{
                skuList.push({spec_id:items,sku_name:attrName[key],goods_market_price:0,goods_price:0,goods_stock:0,goods_weight:0});
            })
            this.skuList = skuList;
        },
        // 多数组求笛卡儿积
        cartesianProduct(array){
            if(array.length==1){
                return array;
            }
            return array.reduce(function(a,b){
                return a.map(function(x){
                    return b.map(function(y){
                        return x.concat(y);
                    })
                }).reduce(function(a,b){ return a.concat(b) },[])
            }, [[]])
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
.row_th{
    background: #efefef;
}
.col_th{
    text-align: center;
}
</style>