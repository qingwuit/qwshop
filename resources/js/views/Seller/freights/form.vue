<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            配送运费
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 3 }" :wrapper-col="{ span: 18 }">
                <a-form-model-item label="默认运费">
                    <a-row :gutter="5">
                        <a-col :md="6" :sm="24" ><a-input type="number" addon-before="首重" addon-after="kg内" v-model="list[0]['f_weight']" /></a-col>
                        <a-col :md="6" :sm="24" ><a-input type="number" addon-before="运费" addon-after="￥"  v-model="list[0]['f_price']" /></a-col>
                        <a-col :md="6" :sm="24" ><a-input type="number" addon-before="每增加" addon-after="kg"  v-model="list[0]['o_weight']" /></a-col>
                        <a-col :md="6" :sm="24" ><a-input type="number" addon-before="增加运费" addon-after="￥"  v-model="list[0]['o_price']" /></a-col>
                    </a-row>
                </a-form-model-item>
                <a-form-model-item label="自定义运费">
                    <a-row :gutter="5" style="background:#efefef;text-align:center">
                        <a-col :md="4" :sm="24" >标题</a-col>
                        <a-col :md="4" :sm="24" >首重（kg）</a-col>
                        <a-col :md="4" :sm="24" >运费（￥）</a-col>
                        <a-col :md="4" :sm="24" >续重（kg）</a-col>
                        <a-col :md="4" :sm="24" >续费（￥）</a-col>
                        <a-col :md="4" :sm="24" >操作</a-col>
                    </a-row>
                    <div v-for="(v,k) in list" :key="k">
                        <div v-if="k>0">
                            <a-row :gutter="5" style="background:#efefef;text-align:center;padding-bottom:10px">
                                <a-col :md="4" :sm="24" ><a-input v-model="v.name" /></a-col>
                                <a-col :md="4" :sm="24" ><a-input type="number" addon-after="kg" v-model="v.f_weight" /></a-col>
                                <a-col :md="4" :sm="24" ><a-input type="number" addon-after="￥" v-model="v.f_price" /></a-col>
                                <a-col :md="4" :sm="24" ><a-input type="number" addon-after="kg" v-model="v.o_weight" /></a-col>
                                <a-col :md="4" :sm="24" ><a-input type="number" addon-after="￥" v-model="v.o_price" /></a-col>
                                <a-col :md="4" :sm="24" ><a-button @click="showArea(k)" size="small">区域编辑</a-button><a-button style="margin-left:10px" type="danger" size="small" @click="del(v.id,k)">删除</a-button></a-col>
                            </a-row>
                            <div class="area_list" v-show="v.area_show">
                                <a-checkbox-group :name="'groupcheckbox'+k" v-model="v.area_id" @change="onChange">
                                    <span style="margin-right:20px;padding-bottom: 10px;display: inline-block;" v-for="(vo,key) in areas" :key="key"><a-checkbox :value="''+vo.id">{{vo.name}}</a-checkbox></span>
                                </a-checkbox-group>
                            </div>
                        </div>
                        
                    </div>
                </a-form-model-item>
                
                <a-form-model-item :wrapper-col="{ span: 12, offset: 3 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                    <a-button style="margin-left:10px" icon="edit" @click="addSetting()">添加配置</a-button>
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
          areas:[],
          list:[{id:0,name:'自定义运费模版',f_weight:0.00,f_price:0.00,o_weight:0,o_price:0.00,area_id:[],area_show:false}],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.$post(this.$api.sellerFreights,{info:this.list}).then(res=>{
                this.$returnInfo(res);
                this.get_info();
            })
        },
        get_info(){
            this.$get(this.$api.sellerFreights).then(res=>{
                if(res.code == 200 && res.data.length>0){
                    this.list = res.data;
                    this.$forceUpdate();
                }
                
            })
        },
        // 获取地址
        get_areas(){
            this.$get(this.$api.sellerAreas).then(res=>{
                this.areas = res.data
            })
        },
        addSetting(){
            let obj = {id:0,name:'自定义运费模版',f_weight:0.00,f_price:0.00,o_weight:0,o_price:0.00,area_id:[],area_show:false};
            this.list.push(obj);
        },
        del(id,k){
            if(id!=0){
                this.$delete(this.$api.sellerFreights+'/'+id).then(res=>{
                    if(res.code == 200){
                        this.list.splice(k,1);
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }else{
                this.list.splice(k,1);
            }
        },
        onChange(){
            console.log(this.list[1].area_id)
        },
        showArea(k){
            this.list[k].area_show = !this.list[k].area_show
        },
        // 获取列表
        onload(){
            this.get_areas();
            this.get_info();
        },
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.area_list{
    margin-top:10px;
}
</style>