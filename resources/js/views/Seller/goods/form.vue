<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            商品编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="商品标题" :rules="{ required: true}">
                    <a-input v-model="info.goods_name" />
                </a-form-model-item>
                <a-form-model-item label="商品副标题">
                    <a-textarea :auto-size="{ minRows: 2, maxRows: 6 }" v-model="info.goods_subname" />
                </a-form-model-item>
                <a-form-model-item label="商品编号">
                    <a-input v-model="info.goods_no" placeholder="HXC18475456841" />
                </a-form-model-item>
                <a-form-model-item label="商品品牌" :rules="{ required: true}">
                    <a-select show-search @search="goodsBrandHandleSearch" v-model="info.brand_id" :filter-option="false">
                        <a-select-option v-for="(v,k) in brandList" :key="k" :value="v.id">{{v.name}}</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="商品分类" :rules="{ required: true}">
                    <a-tag><span v-for="(v,k) in classInfo" :key="k" style="font-size:14px">{{k==0?'&nbsp;&nbsp;':''}}{{v.name}}{{k==2?'&nbsp;&nbsp;':'&nbsp;&nbsp;/&nbsp;&nbsp;'}}</span></a-tag>
                    <a-button @click="to_chose_class" type="primary" size="small">编辑</a-button>
                </a-form-model-item>
                <a-form-model-item label="商品图片" :rules="{ required: true}">
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
                    <a-form-model-item label="平台价格" :rules="{ required: true}">
                        <a-input v-model="info.goods_price" type="number" suffix="￥" />
                    </a-form-model-item>
                    <a-form-model-item label="市场价格" :rules="{ required: true}">
                        <a-input v-model="info.goods_market_price" type="number" suffix="￥" />
                    </a-form-model-item>
                    <a-form-model-item label="商品重量" :rules="{ required: true}">
                        <a-input v-model="info.goods_weight" type="number" suffix="Kg" />
                    </a-form-model-item>
                    <a-form-model-item label="商品库存" :rules="{ required: true}">
                        <a-input v-model="info.goods_stock" type="number">
                            <a-icon slot="suffix" type="stock"></a-icon>
                        </a-input>
                    </a-form-model-item>
                </template>
                <a-form-model-item label="规格属性(SKU)">
                    <div class="attr_modal">
                        <div class="attr_item" v-for="(v,k) in goodsAttr" :key="k">
                            <span style="margin-right:10px;margin-left:8px">{{v.name}}：</span>
                            <a-checkbox v-for="(vo,key) in v.specs" :key="key" @change="specChange(v,vo)" :default-checked="vo.check" :value="vo.check">{{vo.name}}</a-checkbox>
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
                                <a-col class="col_th" :span="4">重量</a-col>
                                <a-col class="col_th" :span="4">库存</a-col>
                                <a-col class="col_th" :span="4">图片</a-col>
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
                                <a-col class="col_th" :span="4">-</a-col>
                            </a-row>
                        </div>
                    </div>
                    <!-- 规格sku end -->
                </a-form-model-item>
                <a-form-model-item label="运费模版">
                    <a-select v-model="info.freight_id" :filter-option="false">
                        <a-select-option :value="0">默认运费</a-select-option>
                        <a-select-option v-for="(v,k) in freightList" :key="k" :value="v.id">{{v.name}}</a-select-option>
                    </a-select>
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
            <goods-attr-modal :attrVisible="attrVisible" @goods_attr_modal_cancel="attrVisible=false" @goods_attr="goods_attr_chose" />
            
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
              goods_images:[],
          },
          list:[],
          brandList:[],// 品牌列表
          freightList:[], // 运费模版
          platform:false, // 平台PC false 手机 TRUE
          goods_content:'', // 商品详情
          id:0,
          goodsClassList:[],
          classInfo:[],

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
            // if(this.$isEmpty(this.info.name)){
            //     return this.$message.error('分类名不能为空');
            // }

            
            let api = this.$apiHandle(this.$api.sellerGoods,this.id);
            this.info.classInfo = this.classInfo; // 获取商品栏目
            this.info.skuList = this.skuList; // 获取商品SKU
            this.info.goods_status = this.info.goods_status?1:0;
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
                        return this.$router.go(-2);
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }
   
            
        },
        get_info(){
            this.$get(this.$api.sellerGoods+'/'+this.id).then(res=>{
                this.goodsAttr = res.data.attrList||[];
                this.skuList = res.data.skuList||[];
                res.data.goods_status = res.data.goods_status==0?false:true;
                this.info = res.data;
                this.check_platform(false);
                this.goodsBrandHandleSearch(this.info.goods_brand.name);
                this.$forceUpdate();
            })
        },
        get_goods_class(){
            this.$get(this.$api.sellerStoreGoodsClasses).then(res=>{
                this.goodsClassList = res.data;
                if(!this.$isEmpty(this.$route.query.id)){
                    let idsStr = this.$route.query.id;
                    let ids = idsStr.split(',');
                    this.goodsClassList.forEach(item=>{
                        if(item[0].id == ids[0] && item[1].id == ids[1] && item[2].id == ids[2]){
                            this.classInfo = item;
                        }
                    })
                }
                if(this.classInfo.length<=0){
                    this.$message.error("非法栏目");
                    setTimeout(()=>{
                        this.$router.go(-1);
                    },1000);
                    
                }
            })
        },
        get_freight_list(){
            this.$get(this.$api.sellerFreights).then(res=>{
                if(res.code == 200 && res.data.length>0){
                    res.data.splice(0,1);
                    this.freightList = res.data;
                }
            })
        },

        onload(){

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }

            this.get_goods_class();
            this.get_freight_list();

            // this.$get(this.$api.sellerGoodsBrands,params).then(res=>{
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
        // 属性选择
        goods_attr_chose(e){
            this.attrVisible=false;
            if(e.length<=0){
                return;
            }
            
            e.forEach((items,k)=>{
                let status = false;
                this.goodsAttr.forEach(attrItems=>{
                    if(attrItems.id == items.id){
                        status = true;
                    }
                })

                if(!status){
                    this.goodsAttr.push(e[k]);
                }

            })
            this.$forceUpdate();
        },
        // 规格选择
        specChange(attrs,specs){
            // console.log(attrs,specs)
            let index = -1;
            this.goodsAttr.forEach((items,key)=>{
                if(items.id == attrs.id){
                    index = key;
                }
            })
            this.goodsAttr[index].specs.forEach(items=>{
                if(items.id == specs.id){
                    if(items.check == undefined){
                        items.check = true;
                    }else{
                        items.check = !items.check;
                    }
                    
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
            let i=0;
            this.goodsAttr.forEach((items,key)=>{
                let canPlus = false;
                items.specs.forEach(specItem=>{
                    if(specItem.check){
                        if(this.$isEmpty(attrList[i])){
                            attrList[i] = [];
                            attrListName[i] = [];
                        }
                        attrList[i].push(specItem.id);
                        attrListName[i].push(specItem.name);
                        canPlus = true;
                    }
                })
                if(canPlus){
                    i++;
                }
            })
            if(attrList.length<=0){
                return this.skuList = [];
            }
            
            // 判断是否单选一个属性
            let attrName = []
            let attrId = [];
            if(attrList.length!=1){
                attrName = this.cartesianProduct(attrListName);
                attrId = this.cartesianProduct(attrList);
                attrId.forEach((items,key)=>{
                    skuList.push({spec_id:items,sku_name:attrName[key],goods_market_price:0,goods_price:0,goods_stock:0,goods_weight:0});
                })
            }else{
                attrName = attrListName[0];
                attrId = attrList[0];
                attrId.forEach((items,key)=>{
                    skuList.push({spec_id:[items],sku_name:[attrName[key]],goods_market_price:0,goods_price:0,goods_stock:0,goods_weight:0});
                })
            }
          
            // 判断是否有已经设置过金额的则不改变内容
            console.log(skuList.length,this.skuList.length)
            if(!this.$isEmpty(this.skuList[0]) && skuList[0].spec_id.length==this.skuList[0].spec_id.length){ // 如果规格数量不一致了则不变了直接替换
                // 判断是否是规格减少了
                if(skuList.length<this.skuList.length){
                    
                    let skuListLength = this.skuList.length;
                    let strList = [];
                    for(let i=0;i<skuListLength;i++){
                        let ngt = false;
                        skuList.forEach(skuItem=>{
                            if(skuItem.spec_id.sort().toString() == this.skuList[i].spec_id.sort().toString()){
                                ngt = true;
                            }
                        })
                        if(!ngt){
                            strList.push(this.skuList[i].spec_id.sort().toString());
                        }
                    }
                    for(let i=0;i<strList.length;i++){
                        let ngt = false;
                        this.skuList.forEach((skuItem,key)=>{
                            if(strList[i] == skuItem.spec_id.sort().toString()){
                                ngt = true;
                            }
                            if(ngt){
                                this.skuList.splice(key,1);
                            }
                        })
                        
                    }
                }else{
                    skuList.forEach(item=>{
                        let gt = false;
                        this.skuList.forEach(skuItem=>{
                            if(skuItem.spec_id.sort().toString() == item.spec_id.sort().toString()){
                                gt = true;
                            }
                        })
                        if(!gt){
                            this.skuList.push(item);
                        }
                    })
                }
                
            }else{
                this.skuList = skuList;
            }
            
            
            
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
        },
        // 品牌搜索
        goodsBrandHandleSearch(e){
            this.$get(this.$api.sellerGoodsBrands,{name:e}).then(res=>{
                this.brandList = res.data.data;
            })
        },
        // 修改栏目
        to_chose_class(){
            if(this.info.id>0){
                this.$router.push('/Seller/goods/chose_class/'+this.info.id);
            }else{
                this.$router.go(-1);
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
            .item_bg{
                border-radius: 4px;
                text-align: center;
                line-height: 160px;
                display: none;
                width: 100%;
                height: 100%;
                position: absolute;
                color:#fff;
                top:0;
                left:0;
                background: rgba(0,0,0,0.5);/* IE9、标准浏览器、IE6和部分IE7内核的浏览器(如QQ浏览器)会读懂 */
                i{
                    padding: 0 14px;
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