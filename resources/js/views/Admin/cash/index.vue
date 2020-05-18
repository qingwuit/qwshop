<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    提现管理
                    <!-- <div><router-link to="/Admin/hooks/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div> -->
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right">
                    <!-- <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div> -->
                </div>
            </div>

            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column>
                    <el-table-column prop="user_id" label="用户ID"></el-table-column>
                    <el-table-column prop="nickname" label="昵称"></el-table-column>
                    <el-table-column prop="bank" label="银行名称"></el-table-column>
                    <el-table-column prop="card_no" label="银行卡号"></el-table-column>
                    <el-table-column prop="rate" label="手续费率">
                        <template slot-scope="scope">
                            <div>{{scope.row.rate}}%</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="rate_money" label="手续费"></el-table-column>
                    <el-table-column prop="money" label="提现金额"></el-table-column>
                    <el-table-column prop="real_money" label="实际打款">
                        <template slot-scope="scope">
                            <div>￥{{scope.row.money-scope.row.rate_money}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="打款状态">
                        <template slot-scope="scope">
                            <div :class="scope.row.status==1?'green_round':'gray_round'" @click="edit_cash(scope.row.id)"></div>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
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
          list:[],
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          select_id:'',
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
        get_cash_list:function(){
            let _this = this;
            this.$get(this.$api.adminGetCashList,{page:this.current_page}).then(function(res){
                _this.page_size = res.data.per_page;
                _this.total_data = res.data.total;
                _this.current_page = res.data.current_page;
                _this.list = res.data.data;
            })

        },
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_cash_list();
        },
        // 删除处理
        del:function(id){
            let _this = this;
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.adminDelCash,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_cash_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败");
                }
            });
        },
        edit_cash:function(id){
            this.$post(this.$api.adminCashChangeStatus,{id:id}).then(()=>{
                this.get_cash_list();
            });
        }
    },
    created() {
        this.get_cash_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>