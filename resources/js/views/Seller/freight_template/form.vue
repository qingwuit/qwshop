<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>广告位编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="模版名称" prop="name" :rules="[{required:true,message:'模版名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="默认价格" prop="price" :rules="[{required:true,message:'价格不能为空',trigger: 'blur' }]"><el-input type="number" placeholder="默认运费" v-model="info.price"></el-input></el-form-item>
                    <el-form-item label="区域价格设置" class="width_auto_70" v-for="(v,k) in list" :key="k"><el-cascader v-model="list[k].content" :props="props" :options="area_list" collapse-tags></el-cascader><el-input placeholder="运费" v-model="list[k].price" style="margin-left:10px;width:120px;"><template slot="append">￥</template></el-input><el-button style="margin-left:10px;" @click="del_template(k)">删除</el-button></el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <el-button @click="add_template">添加地区</el-button>
                    </el-form-item>
                </el-form>
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
          edit_id:0,
          info:{
          },
          // 省市区动态加载
          props:{
              value:'id',
              label:'name',
              multiple: true,
          },
          area_list:[],
          area_info:[],
          list:[],
      };
    },
    watch: {},
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
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addFreightTemplate;
                    if(_this.edit_id>0){
                        url = _this.$api.editFreightTemplate+_this.edit_id;
                    }

                    _this.info.list = _this.list;

                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("编辑成功");
                            _this.$router.go(-1);
                        }else{
                            _this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_freight_template_info:function(){
            let _this = this;
            this.$get(this.$api.editFreightTemplate+this.edit_id).then(function(res){
                _this.info = res.data;
                _this.list = res.data.content;
            })

        },
        get_area_list:function(){
            this.$get(this.$api.sellerGetAreaList).then(res=>{
                this.area_list = res.data;
            });
        },
        add_template:function(){
            this.list.push({content:[],price:0.00});
        },
        del_template:function(e){
            this.list.splice(e,1);
        }
       
    },
    created() {

        // 获取地理数据
        this.get_area_list();

        // 判断是否是编辑
        this.info.pid = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_freight_template_info();
        }
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>