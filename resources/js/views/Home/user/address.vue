<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title">
            收货地址<span><el-button size="mini" type="danger" @click="dialogVisible = true">新增</el-button></span>
        </div>
        <div class="user_content_blcok_line"></div>
        <div class="user_content_block_show">
            <div class="user_address_list">
                <ul>
                    <li v-for="(v,k) in address_list" :key="k">
                        <dl>
                            <dt><img width="32px" height="32px" src="/pc/address_pos.jpg" alt=""></dt>
                            <dd>{{v.receive_name}}</dd>
                            <dd class="address_info">{{v.area_info+' '+v.address}} {{v.receive_tel}}</dd>
                        </dl>
                        <div class="address_handle"><span v-show="v.is_default==0" @click="set_default(v.id)">设置默认</span><span class="red" v-show="v.is_default==1"><i class="icon iconfont">&#xeb96;</i>默认地址</span>|<span @click="get_address_info(v.id)">编辑</span>|<span @click="del(v.id)">删除</span></div>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
        </div>
        <el-dialog :visible.sync="dialogVisible" :title="edit_id>0?'编辑收货地址':'新增收货地址'" @close="close_dialog">
            <div class="home_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="收货人" prop="receive_name" :rules="[{required:true,message:'收货人不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.receive_name"></el-input></el-form-item>
                    <el-form-item label="手机" prop="receive_tel" :rules="[{required:true,message:'手机不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.receive_tel"></el-input></el-form-item>
                    <el-form-item label="地址" prop="area_info" :rules="[{required:true,message:'地址不能为空',trigger: 'blur' }]">
                        <el-cascader v-model="info.area_info" :props="props" :options="area_list"></el-cascader>
                    </el-form-item>
                    <el-form-item label="详细地址" prop="address" :rules="[{required:true,message:'详细地址不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.address"></el-input></el-form-item>
                    <el-form-item label="默认收货地址" prop="default" >
                        <el-switch v-model="info.default" active-color="#ca151e"></el-switch>
                    </el-form-item>
                </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="danger" @click="submitForm('info')">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          dialogVisible:false,
          edit_id:0,
          info:{},
          area_list:[],
          area_info:[],
          address_list:[],
          // 省市区动态加载
          props:{
              value:'id',
              label:'name',
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addAddress;
                    _this.info.is_default = _this.info.default?1:0;
                    if(_this.edit_id>0){
                        url = _this.$api.editAddress+_this.edit_id;
                    }
                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("编辑成功");
                            _this.dialogVisible = false;
                            _this.get_address_list();
                        }else{
                            _this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        get_area_list:function(){
            this.$get(this.$api.homeGetAreaList).then(res=>{
                this.area_list = res.data;
            });
        },
        get_address_list:function(){
            this.$get(this.$api.getAddressList).then(res=>{
                this.address_list = res.data;
            });
        },
        get_address_info:function(id){
            this.edit_id = id;
            this.dialogVisible = true;
            this.$get(this.$api.editAddress+id).then(res=>{
                this.info = res.data;
                this.default = res.data.is_default==0?false:true;
            });
        },
        set_default:function(id){
            this.$post(this.$api.setDefaultAddress,{id:id}).then(res=>{
                this.get_address_list();
                return this.$message.success('设置默认地址成功');
            });
        },
        // 删除处理
        del:function(id){
            let _this = this;
            this.$post(this.$api.delAddress,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_address_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败");
                }
            });
        },
        close_dialog:function(){
            this.edit_id = 0;
        }
    },
    created() {
        this.get_address_list();
        this.get_area_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_address_list{
    padding-top: 10px;
    ul li{
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #efefef;
        dl{
            float: left;
            display: block;
            width: 650px;
            dt{
                float: left;
                margin-right: 20px;
                img{
                    border-radius: 50%;
                }
            }
            dd{
                line-height: 20px;
                width: 190px;
                width: 500px;
                float: left;
            }
            dd.address_info{
                color:#999;
                float: left;
                width:100%;
                width: 500px;
            }
        }
        .address_handle{
            color:#666;
            float:right;
            line-height: 40px;
            span{
                margin: 0 10px; 
                i{
                    margin-right: 10px;
                }
                
            }
            span:hover{
                color:#ca151e;
            }
            span.red{
                color:#ca151e;
            }
        }
    }
}
</style>