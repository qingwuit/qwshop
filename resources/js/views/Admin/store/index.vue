<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><el-button type="primary" disabled="" icon="el-icon-plus">添加</el-button></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                </div>
            </div>

            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column>
                    <el-table-column prop="nickname" label="用户昵称">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><el-image style="width: 50px; height: 50px" :src="scope.row.store_logo"><div slot="error" class="image-slot"><i class="el-icon-picture-outline"></i></div></el-image></dt>
                                <dd class="table_dl_dd_all_30">店铺名： {{ scope.row.store_name }}</dd>
                                <dd class="table_dl_dd_all_16_gray">入驻时间： {{ scope.row.add_time|formatDate}}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column prop="nickname" label="用户昵称"></el-table-column>
                    <el-table-column label="店铺状态">
                        <template slot-scope="scope">
                            <div :class="scope.row.store_status==1?'green_round':'red_round'"></div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="store_company_name" label="公司名称"></el-table-column>
                    <el-table-column label="操作" fixed="right" width="220px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-tickets" @click="read_store_info(scope.row)">查看</el-button>
                            <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>

        <el-dialog title="提示" :visible.sync="dialogVisible" width="50%">
            <ul class="store_list_ul">
                <li>
                    <h4>店铺名称:</h4>{{info.store_name|'-'}}
                </li>
                <li>
                    <h4>店铺LOGO:</h4><el-image style="width: 60px; height: 60px" :src="info.store_logo"></el-image>
                </li>
                <li>
                    <h4>入驻公司:</h4>{{info.store_company_name|'-'}}-{{info.area_info}}-{{info.store_address}}
                </li>
                <li>
                    <h4>营业执照号码:</h4>{{info.legal_person|'-'}}-{{info.business_license_no|'-'}}
                </li>
                <li>
                    <h4>营业执照-身份证:</h4><el-image :preview-src-list="read_pic(info.business_license)" style="width: 60px; height: 60px" :src="info.business_license"></el-image>-<el-image :preview-src-list="read_pic(info.id_card)" style="width: 60px; height: 60px" :src="info.id_card"></el-image>
                </li>
         
                <li>
                    <h4>身份证号码:</h4>{{info.id_card_no|'-'}}
                </li>
                <li>
                    <h4>紧急联系:</h4>{{info.emergency_contact}}-{{info.emergency_contact_phone}}
                </li>
                <li>
                    <h4>店铺状态:</h4><el-radio v-model="info.store_status" :label="0">关闭</el-radio>
                    <el-radio v-model="info.store_status" :label="1">开启</el-radio>
                </li>
                <li v-if="info.store_status==0">
                    <h4>关闭原因:</h4><el-input type="textarea" placeholder="关闭原因！" v-model="info.store_close_info"></el-input>
                </li>
                <li v-if="info.store_verify==0">
                    <h4>店铺审核:</h4>
                    <el-radio v-model="store_verify" :label="0">等待审核(忽略)</el-radio>
                    <el-radio v-model="store_verify" :label="1">同意</el-radio>
                    <el-radio v-model="store_verify" :label="2">拒绝</el-radio>
                </li>
                
            </ul>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">关 闭</el-button>
                <el-button type="primary" @click="update_store()">确 定</el-button>
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
          list:[],
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          select_id:'',
          dialogVisible:false,
          info:{},
          store_verify:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSelectionChange:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id = ids.join(',');
        },
        get_store_list:function(){
            let _this = this;
            this.$post(this.$api.getStoreList,{page:this.current_page}).then(function(res){
                _this.page_size = res.data.per_page;
                _this.total_data = res.data.total;
                _this.current_page = res.data.current_page;
                _this.list = res.data.data;
            })

        },
        read_store_info(e){
            this.info = e;
            this.store_verify = this.info.store_verify;
            this.dialogVisible=true;
        },
        read_pic(e){
            return [e];
        },
        update_store:function(){
            if(this.info.store_status==0 && this.info.store_close_info == ''){
                return this.$message.error('请填写关闭店铺的原因！');
            }
            this.info.store_verify = this.store_verify;
            this.$post(this.$api.StorePass,this.info).then(res=>{
                this.$message.success('修改成功');
                this.dialogVisible = false;
                this.get_store_list();
            });
        },
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_store_list();
        },
        // 删除处理
        del:function(id){
            let _this = this;
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delStore,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_store_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败！"+res.msg);
                }
            });
        }
    },
    created() {
        this.get_store_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.store_list_ul li{
    border-bottom: 1px solid #efefef;
    margin:0 0 30px 0; 
    padding-bottom: 15px;
    color:#666;
    h4{
        margin-bottom: 15px;
        color:#333;
    }
}
</style>