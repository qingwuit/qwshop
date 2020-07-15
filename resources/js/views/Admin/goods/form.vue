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
                    <a-select>
                        <a-select-option key="" value="">测试</a-select-option>
                    </a-select>
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
import wangEditor from "@/components/wangeditor"
export default {
    components: {wangEditor},
    props: {},
    data() {
      return {
          info:{
              pid:0,
          },
          list:[],
          id:0,
          loading:false,
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

            
            let api = this.$apiHandle(this.$api.adminGoodsBrands,this.id);
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
            this.$get(this.$api.adminGoodsBrands+'/'+this.id).then(res=>{
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

            this.$get(this.$api.adminGoodsBrands,params).then(res=>{
                this.list = res.data;
            });
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,'thumb',rs.data);
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
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
</style>