<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>选择栏目</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <div class="goods_add_chose_class_bg">
                    <div class="chose_class">
                        <el-scrollbar>
                            <ul>
                                <li v-for="(v,k) in class_1" :key="k" :class="v.id==chose_class_1?'chose':''" @click="chose_class_act_1(v)"><i class="icon iconfont">&#xe6ab;</i>{{v.name}}</li>
                            </ul>
                        </el-scrollbar>
                    </div>
                    <div class="chose_class" :style="chose_class_1==0?'background:#fcfcfc;':''">
                        <el-scrollbar>
                            <ul>
                                <li v-for="(v,k) in class_2" :key="k" :class="v.id==chose_class_2?'chose':''" @click="chose_class_act_2(v)"><i class="icon iconfont">&#xe6ab;</i>{{v.name}}</li>
                            </ul>
                        </el-scrollbar>
                    </div>
                    <div class="chose_class" :style="chose_class_2==0?'background:#fcfcfc;':''">
                        <el-scrollbar>
                            <ul>
                                <li v-for="(v,k) in class_3" :key="k" :class="v.id==chose_class_3?'chose':''" @click="chose_class_act_3(v)"><i class="icon iconfont">&#xe6ab;</i>{{v.name}}</li>
                            </ul>
                        </el-scrollbar>
                    </div>
                </div>
                <div class="chose_class_tags">
                    <el-tag type="success">您当前选择的商品类别是： {{class_name.join('&nbsp;&nbsp;&nbsp;')}}</el-tag>
                </div>

                <div class="chose_class_btn">
                    <el-button type="primary" :disabled="chose_class_3==0" @click="next">下一步，填写商品信息</el-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          chose_class_1:0,
          chose_class_2:0,
          chose_class_3:0,
          class_1:[],
          class_2:[],
          class_3:[],
          class_name:[],
          where:{},
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        chose_class_act_1:function(e){
            this.chose_class_1 = e.id;
            this.class_name = [];
            this.class_name[0] = e.name;
            this.chose_class_2 = 0;
            this.chose_class_3 = 0;
            this.where.id = e.id;
            this.where.deep = 0;
            this.$post(this.$api.GetStoreClass,this.where).then(res=>{
                this.class_2 = res.data;
            });
        },
        chose_class_act_2:function(e){
            if(this.chose_class_1>0){
                this.chose_class_2 = e.id;
                if(this.class_name.length>2){
                    this.class_name.splice(2,1);
                }
                this.class_name[1] = e.name;
                this.chose_class_3 = 0;
            }

            this.where.id = e.id;
            this.where.deep = 1;
            this.$post(this.$api.GetStoreClass,this.where).then(res=>{
                this.class_3 = res.data;
            });
            
        },
        chose_class_act_3:function(e){
            if(this.chose_class_2>0){
                this.class_name[2] = e.name;
                this.chose_class_3 = e.id;
            }
        },
        next:function(){
            if(this.chose_class_3==0){
                this.$message.error('请先选择栏目');
            }else{
                if(this.$isEmpty(this.$route.params.goods_id)){
                    this.$router.push("/Seller/goods/add/"+this.chose_class_3);
                }else{
                    this.$router.push("/Seller/goods/edit/"+this.$route.params.goods_id+'?class_id='+this.chose_class_3);
                }
                
            }
        },
        get_store_class:function(){
            this.$post(this.$api.GetStoreClass,this.where).then(res=>{
                this.list = res.data;
            });
        }
    },
    created() {
        this.$post(this.$api.GetStoreClass,this.where).then(res=>{
            this.class_1 = res.data;
            this.where.id = res.data[0].id;
            this.where.deep = 0;
            this.$post(this.$api.GetStoreClass,this.where).then(res2=>{
                this.class_2 = res2.data;
                this.where.id = res2.data[0].id;
                this.where.deep = 1;
                this.$post(this.$api.GetStoreClass,this.where).then(res3=>{
                    this.class_3 = res3.data;
                });
            });
        });
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.goods_add_chose_class_bg{
    background:#fafafa;
    padding:40px;
    width: 1000px;
    margin:0 auto;
    border:1px solid #eee;
    border-radius: 5px;
}
.chose_class{
    height: 400px;
    float: left;
    width: 30%;
    background: #fff;
    margin-right: 5%;
    box-sizing: border-box;
    border:1px solid #efefef;
    border-radius: 5px;
    color:#666;
    font-size: 12px;
    .el-scrollbar{
        height: 100%;
    }
}
.chose_class ul{
    padding:10px;
    li{
        line-height: 30px;
        margin-bottom: 10px;
        padding-left: 8px;
        box-sizing: border-box;
        border:1px solid #fff;
        i{
            float:right;
            padding-right: 8px;
        }
    }
    li.chose{
        box-sizing: border-box;
        border:1px solid #d9ecff;
        background: #ecf5ff;
        color:#409eff;
        border-radius: 3px;
    }
}

.chose_class:last-child{
    margin-right: 0;
}
.goods_add_chose_class_bg:after{
    content:'';
    display: block;
    clear:both;
}
.chose_class_tags{
    text-align: center;
    margin-top:30px;
}
.chose_class_btn{
    margin-top:30px;
    text-align: center;
}
</style>