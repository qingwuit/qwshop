<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            分销编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 14 }">
                <a-form-model-item label="选择商品" :rules="{ required: true}">
                    <div class="goods_list chose" v-if="!$isEmpty(info.goods_info.id)">
                        <dl>
                            <dt><img v-lazy="info.goods_info.goods_master_image" :alt="info.goods_info.goods_name"></dt>
                            <dd>{{info.goods_info.goods_name}}</dd>
                        </dl>
                    </div>
                    <div class="goods_list_search" v-if="id==0">
                        <a-input-search placeholder="选择分销产品关键词..." style="width: 100%;" enter-button @search="onSearch" />
                    </div>
                    <div class="goods_list" v-if="total>0" style="margin-top:10px">
                        <dl v-for="(v,k) in goods" :key="k" @click="onChose(v)" style="margin-bttom:10px;">
                            <dt><img v-lazy="v.goods_master_image" :alt="v.goods_name"></dt>
                            <dd>{{v.goods_name}}</dd>
                            <dd><a-tag v-if="!v.distribution" color="green">可选</a-tag><a-tag v-else color="red">已分销</a-tag></dd>
                        </dl>
                    </div>
                    <div class="admin_pagination" v-if="total>0">
                        <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
                    </div>
                </a-form-model-item>
                <a-form-model-item label="一级分销" :rules="{ required: true}">
                    <a-input-number class="input_number" :min="0" :max="0.5" :step="0.1" v-model="info.lev_1" placeholder="不能超过 0.50" suffix="%"></a-input-number>
                </a-form-model-item>
                <a-form-model-item label="二级分销" :rules="{ required: true}">
                    <a-input-number class="input_number" :min="0" :max="0.3" :step="0.1"  v-model="info.lev_2" placeholder="不能超过 0.30" suffix="%"></a-input-number>
                </a-form-model-item>
                <a-form-model-item label="三级分销" :rules="{ required: true}">
                    <a-input-number class="input_number" :min="0" :max="0.2" :step="0.1"  v-model="info.lev_3" placeholder="不能超过 0.20" suffix="%"></a-input-number>
                </a-form-model-item>
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
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
          params:{
              page:1,
              per_page:8,
              goods_name:'',
          },
          total:0, //总页数
          goods:[],
          info:{
              goods_id:0,
              goods_info:{},
          },
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.info.goods_id==0 || this.$isEmpty(this.info.lev_1) || this.$isEmpty(this.info.lev_2) || this.$isEmpty(this.info.lev_3)){
                return this.$message.error('不能为空');
            }

            if(this.info.lev_1>0.5 || this.info.lev_2>0.3 || this.info.lev_3>0.2){
                return this.$message.error('请不要超过分佣百分比');
            }

            
            let api = this.$apiHandle(this.$api.sellerDistributions,this.id);
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
            this.$get(this.$api.sellerDistributions+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 选择分页
        onChange(e){
            this.params.page = e;
        },
        onChose(e){
            if(e.distribution){
                return this.$message.error('该商品已经存在分销');
            }
            this.info.goods_id = e.id;
            this.info.goods_info = e;
        },
        onSearch(e){
            console.log(e)
            this.params.goods_name = e
            this.get_goods();
        },
        get_goods(){
            this.$get(this.$api.sellerDistributions+'/goods/get_distribution_goods',this.params).then(res=>{
                this.total = res.data.total;
                this.goods = res.data.data;
            })
        },
        // 获取列表
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
.input_number{
    width: 40%;
}
.goods_list{
    &.chose{
        margin-bottom: 40px;
        dl{
            background: #fafafa;
            padding: 20px;
            float: none;
            width: 192px;
        }
    }
    &:after{
        clear: both;
        display: block;
        content:'';
    }
    dl{
        cursor: pointer;
        float: left;
        margin-right: 15px;
        width: 152px;
        img{
            width: 150px;
            height: 150px;
        }
        dt{
            border:1px solid #efefef;
            box-sizing: border-box;
        }
        dd{
            width: 150px;
            height: 35px;
            overflow: hidden;
            text-align: center;
        }        
    }
}
</style>