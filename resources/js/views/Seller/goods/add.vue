<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>商品添加</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="商品名" prop="goods_name" :rules="[{required:true,message:'商品名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.goods_name"></el-input></el-form-item>
                    <el-form-item label="副标题" prop="goods_subname"><el-input type="textarea" placeholder="请输入内容" v-model="info.goods_subname"></el-input></el-form-item>
                    <el-form-item label="商品编号" prop="goods_no"><el-input placeholder="TL-0000000201" v-model="info.goods_no"></el-input></el-form-item>
                    <el-form-item label="商品分类" class="width_auto">
                        <el-breadcrumb separator="/" class="goods_class_add_left">
                            <el-breadcrumb-item v-for="(v,k) in class_list" :key="k">{{v.name}}</el-breadcrumb-item>
                        </el-breadcrumb>
                        <el-tooltip class="item" effect="dark" content="编辑分类会导致信息丢失,需要重新填写" placement="bottom"><el-button type="success" @click="$router.push('/Seller/goods/chose_class');">编辑</el-button></el-tooltip>
                    </el-form-item>
                    <el-form-item label="商品品牌" prop="goods_subname" @change="selectChange()">
                        <el-select v-model="info.bid" placeholder="请选择">
                            <el-option label="请选择品牌" :value="0"></el-option>
                            <el-option v-for="(v,k) in brand_list" :key="k" :label="v.name" :value="v.id"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="商品主图" prop="thumb" class="width_auto">
                        <el-upload :action="$api.goodsUpload" :headers="upload_headers" list-type="picture-card" ref="upload" :file-list="file_list2" :limit="5" :multiple="true"  :on-success="handleAvatarSuccess" :on-exceed="onExceed" >
                            <!-- <i  class="el-icon-plus avatar-uploader-icon"></i> -->
                            <i slot="default" class="el-icon-plus"></i>
                            <div slot="file" slot-scope="{file}">
                                <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" >
                                <div class="is_master" :style="get_master_image(file)"><i class="el-icon-finished"> 主图</i></div>
                                <span class="el-upload-list__item-actions">
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="setMaster(file)" >
                                        <i class="el-icon-finished"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handlePictureCardPreview(file)" >
                                        <i class="el-icon-zoom-in"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handleRemove(file)" >
                                        <i class="el-icon-delete"></i>
                                    </span>
                                </span>
                            </div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="商品价格" prop="goods_price">
                        <el-input placeholder="0.00" type="number" :disabled="check_list.length>0" v-model="info.goods_price">
                            <template slot="append">￥</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="市场价格" prop="goods_market_price"><el-input type="number" :disabled="check_list.length>0" placeholder="0.00" v-model="info.goods_market_price"></el-input></el-form-item>
                    <el-form-item label="商品库存" prop="goods_num"><el-input :disabled="check_list.length>0" placeholder="0" type="age" v-model.number="info.goods_num"></el-input></el-form-item>
                    <el-form-item label="规格属性" class="width_auto">
                        <div>
                            <el-checkbox-group v-model="check_list">
                                <div class="spec_list" v-for="(v,k) in chose_spec_list" :key="k">
                                    <div class="spec_name">{{v.spec_name+'：'}}</div>
                                    <ul>
                                        <li v-for="(vo,key) in v.attr_name.split(',')" :key="key">
                                            <el-checkbox :label="v.id+'|'+vo">{{vo}}</el-checkbox>
                                        </li>
                                    </ul>
                                </div>
                            </el-checkbox-group>
                        </div>
                        <div class="clear" v-show="chose_spec_list.length"></div>
                        <div class="attr_spec_btn">
                            <el-button type="primary" @click="open_spec_table">添加规格</el-button>
                        </div>
                    </el-form-item>
                    <el-form-item label="属性价格" class="width_auto_70" v-if="check_list.length>0">
                        <div class="spec_group_title">
                            <el-row>
                                <el-col :span="6">名称</el-col>
                                <el-col :span="6">商品价格</el-col>
                                <el-col :span="6">市场价格</el-col>
                                <el-col :span="6">商品库存</el-col>
                            </el-row>
                        </div>
                        <div class="spec_group" v-for="(v,k) in all_spec_group" :key="k" v-show="v.is_chose">
                            <el-row>
                                <el-col :span="6">{{v.attr_str}}</el-col>
                                <el-col :span="6"><el-input type="number" class="spec_group_input" v-model="v.price" placeholder="0.00"><template slot="append">￥</template></el-input></el-col>
                                <el-col :span="6"><el-input type="number" class="spec_group_input" v-model="v.market_price" placeholder="0.00"><template slot="append">￥</template></el-input></el-col>
                                <el-col :span="6"><el-input type="number" class="spec_group_input" v-model="v.num" placeholder="0"><template slot="append"><i class="el-icon-pie-chart"></i></template></el-input></el-col>
                            </el-row>
                        </div>
                    </el-form-item>

                    <el-form-item label="运费物流">
                        <div class="freight_chose">
                            <el-radio v-model="info.freight_chose" :label="0">固定运费</el-radio>
                            <el-radio v-model="info.freight_chose" :label="1">快递模版</el-radio>
                        </div>
                        <div class="fixed_freight" v-show="info.freight_chose==0">
                            <el-input type="number" v-model="info.goods_freight" placeholder="0.00"><template slot="append">￥</template></el-input>
                        </div>
                        <div class="fixed_freight" v-show="info.freight_chose==1">
                            <div class="freight_info">{{freight_info}}</div>
                            <el-button type="primary" style="margin-top:10px;" @click="chose_freight_template_show=true">选择模版</el-button>
                        </div>
                    </el-form-item>

                    <el-form-item label="商品详情" class="width_auto_70">
                        <wangeditor @goods_content="goods_content"></wangeditor>
                    </el-form-item>
                    <el-form-item label="自定义分类" prop="store_goods_class" @change="selectChange()">
                        <el-select v-model="info.store_goods_class" placeholder="请选择">
                            <el-option label="请选择自定义分类" :value="0"></el-option>
                            <el-option v-for="(v,k) in store_goods_class_list" :key="k" :label="v.name" :value="v.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="参加拼团" prop="is_groupbuy">
                        <el-switch v-model="info.is_groupbuy" active-color="#13ce66" :active-value="1" :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="拼团价格" prop="groupbuy_price" v-show="info.is_groupbuy==1">
                        <el-input placeholder="0.00" type="number"  v-model="info.groupbuy_price">
                            <template slot="append">￥</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="拼团人数" prop="groupbuy_num" v-show="info.is_groupbuy==1">
                        <el-input type="age" placeholder="0" v-model="info.groupbuy_num">
                            <template slot="append">人</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="是否上架" prop="goods_status">
                        <el-switch v-model="info.goods_status" active-color="#13ce66" :active-value="1" :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="是否置顶" prop="is_top">
                        <el-switch v-model="info.is_top" active-color="#13ce66" :active-value="1" :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">发布产品</el-button>
                    </el-form-item>
                </el-form>
                   
            </div>
        </div>

        <!-- dialog -->
        <el-dialog title="选择属性规格" :visible.sync="dialogTableVisible">
            <chose-spec @chose_spec="chose_spec"></chose-spec>
        </el-dialog>

        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>

        <!-- dialog -->
        <el-dialog title="选择模版" :visible.sync="chose_freight_template_show">
            <chose-freight-template @chose_freight_template="chose_freight_template"></chose-freight-template>
        </el-dialog>

    </div>

</template>

<script>

import wangeditor from "@/components/seller/wangeditor.vue"
import choseSpec from "@/components/seller/attr_spec/chose_spec.vue"
import choseFreightTemplate from "@/components/seller/freight_template/chose_freight_template.vue"
export default {
    components: {
        wangeditor,
        choseSpec,
        choseFreightTemplate,
    },
    props: {},
    data() {
      return {
          info:{
              bid:0,
              cid:0,
              is_groupbuy:0,
              groupbuy_price:0.00, // 拼团价格
              groupbuy_num:5,// 拼团需要人数
              goods_status:1,
              is_top:0,
              goods_market_price:0.00,
              goods_price:0.00,
              goods_num:0,
              goods_master_image:'',
              freight_chose:0, // 运费类型
              goods_freight:0.00,
              store_goods_class:0,
          },
          freight_info:'无',
          brand_list:[],
          class_list:[],
          store_goods_class_list:[], // 自定义分类
          upload_headers:{},
          file_list:[],
          file_list2:[], // element 内置file_list
          dialogTableVisible:false, // 选择规格打开
          dialogVisible:false,// 是否打开预览
          dialogImageUrl:'', // 预览
          disabled:false,
          chose_freight_template_show:false,
          chose_spec_list:[], // 选择的规格
          check_list:[], // 选中属性
          all_spec_group:[], // 所有属性组合
          spec_group:[], // 选中的属性组合
          spec_group_diff:[], // 差集
      };
    },
    watch: {
        check_list:function(val){
            let arr = [];
            let info = [];
            this.spec_group_diff = [];
            val.forEach(res => {
                info = res.split('|');
                if(arr[info[0]+'_spec_id'] == undefined){
                    arr[info[0]+'_spec_id'] = [];
                }
                arr[info[0]+'_spec_id'].push(info[1]);
            });
            
            let real_arr = [];
            Object.keys(arr).forEach(arrRes=>{
                real_arr.push(arr[arrRes]);
            });

            var real_spec = [];
            real_spec = this.add_other_attr(this.recursive_sort(real_arr));
            // this.spec_group = real_spec;
            // console.log(this.spec_group);

            // let del_index = [];
            var is_diff = true;
            for(let a=0;a<real_spec.length;a++){
                is_diff = true;
                for(let b=0;b<this.all_spec_group.length;b++){
                    let allstr = '';
                    let allstr2 = '';
                    if(real_spec[a].attr instanceof Array){
                        allstr = real_spec[a].attr.join(' ');
                    }else{
                        allstr = real_spec[a].attr;
                    }
                    if(this.all_spec_group[b].attr instanceof Array){
                        allstr2 = this.all_spec_group[b].attr.join(' ');
                    }else{
                        allstr2 = this.all_spec_group[b].attr;
                    }
                    // console.log(allstr,allstr2);
                    if(allstr==allstr2){
                        is_diff = false;
                        break; // 跳出循环
                    }
                }
                
                if(is_diff){
                    this.spec_group_diff.push(real_spec[a]);
                }
            }

            // console.log('SKU数量:'+real_spec.length);


            this.all_spec_group = this.all_spec_group.concat(this.spec_group_diff);


            // 先吧所有的选中赋0
            this.all_spec_group.forEach(res=>{
                res.is_chose = false;
            });
            
            // 循环将选中的赋1
            real_spec.forEach(realRes=>{
                this.all_spec_group.forEach(allRes=>{
                    let allstr = '';
                    let allstr2 = '';
                    if(realRes.attr instanceof Array){
                        allstr = realRes.attr.join(' ');
                    }else{
                        allstr = realRes.attr;
                    }
                    if(allRes.attr instanceof Array){
                        allstr2 = allRes.attr.join(' ');
                    }else{
                        allstr2 = allRes.attr;
                    }
                    // console.log(allstr,allstr2);
                    if(allstr==allstr2){
                        allRes.is_chose = true;
                    }
                });
            });

            // 修改属性数组
            this.all_spec_group.forEach(res=>{
                let attrstr = '';
                if(res.attr instanceof Array){
                    attrstr = res.attr.join(' ');
                }else{
                    attrstr = res.attr;
                }
                res['attr_str'] = attrstr;
            });

        }
    },
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
                    // 图片 验证
                    if(_this.file_list.length<=0){
                        return _this.$message.error('商品主图至少上传一张');
                    }

                    // 判断是否选中规格
                    if(_this.check_list.length>0){
                        // 验证规格属性是否填写正确
                        if(_this.check_spec_goods()){
                            _this.add_goods();
                        }else{
                            return _this.$message.error('请认真填写规格属性');
                        }
                        
                    }else{
                        if(_this.info.goods_price<=0 || _this.info.goods_market_price<=0 || _this.info.goods_num<=0){
                            return _this.$message.error('价格或库存没填写，或者填写错误！');
                        }else{
                            _this.add_goods();
                        }
                    }

                    
                }
            });
        },
        
        selectChange:function(){
            this.$forceUpdate();
        },
        handleAvatarSuccess(res) {
            if(this.file_list.length==0){
                this.info.goods_master_image = res.data;
            }
            this.file_list.push(res.data);
            this.$forceUpdate();
        },
        handlePictureCardPreview:function(file){
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        // 文件删除
        handleRemove:function(file){
            // console.log(file,this.$refs.upload);
            this.$refs.upload.handleRemove(file);
            let index = this.file_list.indexOf(file.url);
            this.file_list.splice(index,1);
        },
        // 设置主图
        setMaster:function(file){
            this.info.goods_master_image = file.response.data;
        },
        // 上传超过限制
        onExceed:function(){
            this.$message.error('商品展示图片不能超过5个');
        },
        // 打开选择规格属性
        open_spec_table:function(){
            this.dialogTableVisible = !this.dialogTableVisible;
        },
        chose_spec:function(res){
            this.dialogTableVisible = !this.dialogTableVisible;
            this.chose_spec_list = res;
        },
        // 选择运费模版
        chose_freight_template:function(res){
            this.chose_freight_template_show = !this.chose_freight_template_show;
            this.info.freight_id = res[0].id;
            this.freight_info = res[0].name;
        },
        get_master_image:function(file){
            if(file.response != undefined){
                if(file.response.data == this.info.goods_master_image){
                    return 'display:block';
                }else{
                    return 'display:none';
                }
            }
        },
        // 验证是否填写正确 规格数据
        check_spec_goods:function(){
            let check = true;
            this.all_spec_group.forEach(res=>{
                if(res.is_chose){
                    if(res.price<=0 || res.market_price<=0 || res.num<0){
                        check = false;
                        return false;
                    }
                }
            });
            return check;
        },
        // 富文本编辑内容变化
        goods_content:function(data){
            this.info.content = data;
        },
        // 添加商品
        add_goods:function(){
            let info = this.info;
            info.file_list = this.file_list; // 图片
            info.chose_spec = this.chose_spec_list; // 规格
            info.chose_attr = this.check_list; // 属性
            info.spec_attr = [];

            // 规格属性
            this.all_spec_group.forEach(res=>{
                if(res.is_chose){
                    info.spec_attr.push(res);
                }
            });

            // 判断是否选择了模版
            if(this.info.freight_chose == 0){
                this.info.freight_id = 0;
            }

            // 请求
            this.$post(this.$api.addGoods,info).then(res=>{
                if(res.code == 200){
                    this.$message.success('添加成功');
                    this.$router.go(-2);
                }else{
                    this.$message.error(res.msg);
                }
            });
        },
        // 递归排列
        recursive_sort:function(attr_name){
            var len = attr_name.length;
            // 当数组大于等于2个的时候
            if(len >= 2){
                // 第一个数组的长度
                var len1 = attr_name[0].length;
                // 第二个数组的长度
                var len2 = attr_name[1].length;
                // 2个数组产生的组合数
                var lenBoth = len1 * len2;
                //  申明一个新数组
                var items = new Array(lenBoth);
                // 申明新数组的索引
                var index = 0;
                for(var i=0; i<len1; i++){
                    for(var j=0; j<len2; j++){
                        if(attr_name[0][i] instanceof Array){
                            items[index] = attr_name[0][i].concat(attr_name[1][j]);
                        }else{
                            items[index] = [attr_name[0][i]].concat(attr_name[1][j]);
                        }
                        index++;
                    }
                }
                var newArr = new Array(len -1);
                for(var s=2;s<attr_name.length;s++){
                    newArr[s-1] = attr_name[s];
                }
                newArr[0] = items;
                return this.recursive_sort(newArr);
            }else{
                return attr_name[0];
            }
        },
        // 添加其他属性
        add_other_attr:function(data){
            var arrs = new Array();
            for(var i=0;i<data.length;i++){
                var obj = {'attr':data[i],'price':0.00,'market_price':0.00,'num':0,'is_chose':false};
                arrs.push(obj);
            }
            return arrs;
        },
        // 获取添加初始化信息
        get_goods_add_info:function(){
            this.$get(this.$api.addGoods,{class_id:this.$route.params.id}).then(res=>{
                if(res.code == 500){
                    this.$message.error(res.msg);
                    this.$router.go(-1);
                }else{
                    this.class_list = res.data.class_list;
                    this.brand_list = res.data.goods_brand_list;
                    this.store_goods_class_list = res.data.store_goods_class;
                    this.info.cid = this.class_list[2].id;
                }
            });
        }
    },
    created() {
        this.get_goods_add_info();
        
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        // if(this.$isEmpty(this.$router.params.id)){
        //     this.$message.error('先填好商品分类');
        //     this.$router.go(-1);
        // }
        
    },
    mounted() {
        
    }
};
</script>
<style lang="scss" scoped>
.el-breadcrumb{
    line-height: 32px;
}
.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 146px;
    height: 146px;
    line-height: 146px;
    text-align: center;
}
.avatar-upload{
    width: 146px;
    height: 146px;
}
.spec_list{
    .spec_name{
        float: left;
        margin-right:15px;
        font-size: 14px;
    }
    ul li{
        float: left;
        margin-right:15px;
    }
}
.spec_list:after{
    clear:both;
    display: block;
    content:'';
}
.spec_group_title{
    text-align: center;
    background:#f1f1f1;
    border-radius: 4px;
    line-height: 40px;
}
.spec_group{
    text-align: center;
    margin:10px 0;
    border-bottom:1px solid #efefef;
    padding-bottom: 12px;
}
.spec_group_input{
    margin:0 auto;
    width: 60%;
}
.is_master{
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(0,0,0,0.5);
    color:#fff;
    width: 164px;
    text-align: center;
    display: none;
}
.goods_class_add_left{
    float: left;
    margin-right: 15px;
    // border:1px solid #e1e1e1;
    padding:0 20px;
    border-radius: 5px;
    background: #f1f1f1;
}
</style>