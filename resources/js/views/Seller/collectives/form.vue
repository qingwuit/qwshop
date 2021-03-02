<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            拼团编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 14 }">
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-alert message="温馨提示：折扣作用全SKU." type="info" show-icon />
                </a-form-model-item>
                <a-form-model-item label="选择商品" :rules="{ required: true}">
                    <div class="goods_list chose" v-if="!$isEmpty(info.goods_info.id)">
                        <dl>
                            <dt><img v-lazy="info.goods_info.goods_master_image" :alt="info.goods_info.goods_name"></dt>
                            <dd>{{info.goods_info.goods_name}}</dd>
                        </dl>
                    </div>
                    <div class="goods_list_search" v-if="id==0">
                        <a-input-search placeholder="选择产品关键词..." style="width: 100%;" enter-button @search="onSearch" />
                    </div>
                    <div class="goods_list" v-if="total>0" style="margin-top:10px">
                        <dl v-for="(v,k) in goods" :key="k" @click="onChose(v)" style="margin-bttom:10px;">
                            <dt><img v-lazy="v.goods_master_image" :alt="v.goods_name"></dt>
                            <dd>{{v.goods_name}}</dd>
                            <dd><a-tag v-if="!v.is_use" color="green">可选</a-tag><a-tag v-else color="red">已使用</a-tag></dd>
                        </dl>
                    </div>
                    <div class="admin_pagination" v-if="total>0">
                        <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
                    </div>
                </a-form-model-item>
                <a-form-model-item label="折扣率" :rules="{ required: true}">
                    <a-input type="number" v-model="info.discount" placeholder="不能超过 100" suffix="%"></a-input>
                </a-form-model-item>
                <a-form-model-item label="成团人数" :rules="{ required: true}">
                    <a-input type="number" v-model="info.need"  suffix="人"></a-input>
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

            if(this.$isEmpty(this.info.discount) && this.info.discount<100 && this.info.discount>0){
                return this.$message.error('折扣填写错误');
            }

            if(this.$isEmpty(this.info.need)){
                return this.$message.error('成团人数');
            }

            let api = this.$apiHandle(this.$api.sellerCollectives,this.id);
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
            this.$get(this.$api.sellerCollectives+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 选择分页
        onChange(e){
            this.params.page = e;
        },
        onChose(e){
            if(e.is_use){
                return this.$message.error('该商品已经存在');
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
            this.$get(this.$api.sellerCollectives+'/goods/get_collective_goods',this.params).then(res=>{
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