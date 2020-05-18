<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>栏目编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="地区名" prop="name" :rules="[{required:true,message:'地区名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="父栏目" prop="pid">
                        <!-- <el-select v-model="info.pid" placeholder="请选择栏目" filterable @change="selectChange()">
                            <el-option label="顶级栏目" :value="0"></el-option>
                            <el-option v-for="(v,k) in area_list" :label="v.name" :key="k" :value="v.id"></el-option>
                        </el-select> -->
                        <el-cascader v-model="info.area_info" :props="props" :options="area_list"></el-cascader>
                    </el-form-item>
                    <el-form-item label="编号" prop="area_id" :rules="[{required:true,message:'编号不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.area_id"></el-input></el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <el-button @click="resetForm('info')">重置</el-button>
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
          info:{},
          area_list:[],
          // 省市区动态加载
          props:{
              value:'id',
              label:'name',
              checkStrictly:true,
          },
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
                    let url = _this.$api.addArea;
                    if(_this.edit_id>0){
                        url = _this.$api.editArea+_this.edit_id;
                    }
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
        get_area_info:function(){
            let _this = this;
            this.$get(this.$api.editArea+this.edit_id).then(function(res){
                _this.info = res.data.info;
                // _this.area_list = res.data.info.area;
            })

        },
        get_area_list:function(){
            this.$get(this.$api.adminGetAreaList).then(res=>{
                this.area_list = res.data;
            });
        },
        // get_area_list:function(){
        //     let _this = this;
        //     this.$get(this.$api.addArea).then(function(res){
        //         _this.area_list = res.data;
        //     })
        // },
        selectChange:function(){
            this.$forceUpdate();
        },
        
    },
    created() {

        // 判断是否是编辑
        this.info.pid = 0;
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }else{
            return this.get_area_list();
        }

        if(this.edit_id>0){
            this.get_area_info();
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>