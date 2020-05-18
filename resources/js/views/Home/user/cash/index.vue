<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            {{title}}<span><el-button size="mini" type="danger" @click="get_add_cash_info()">申请提现</el-button></span>
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_table_main">
            <el-table :data="list" @selection-change="handleSelectionChange" >
                <el-table-column type="selection"></el-table-column>
                <el-table-column label="#" prop="id" width="70px"></el-table-column>
                <el-table-column label="提现人" prop="real_name"></el-table-column>
                <el-table-column label="提现银行" prop="bank"></el-table-column>
                <el-table-column label="银行卡号" prop="card_no"></el-table-column>
                <el-table-column label="提现金额" prop="money"></el-table-column>
                <el-table-column label="手续费" prop="rate_money"></el-table-column>
                <el-table-column label="提现状态" prop="status">
                    <template slot-scope="scope">
                        <div :class="scope.row.status==1?'green':'red'"></div>
                    </template> 
                </el-table-column>
                <el-table-column label="加入时间">
                    <template slot-scope="scope">
                        <div>{{scope.row.add_time|formatDate}}</div>
                    </template> 
                </el-table-column>
                
            </el-table>
            <div class="home_fy_block" style="margin-top:40px;" >
                <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
            </div>
        </div>

        <!-- 弹出框 -->
        <el-dialog :visible.sync="dialogVisible" :title="'申请提现'" >
            <div class="home_form_main">
                <el-form  label-width="100px" ref="info">
                    <el-form-item label="余额" prop="now_money"><el-input disabled  v-model="info.money"></el-input></el-form-item>
                    <el-form-item label="手续费率" prop="rate"><el-input disabled  v-model="info.cash_rate"><template slot="append">%</template></el-input></el-form-item>
                    <el-form-item label="银行名称" prop="rate"><el-input disabled  v-model="info.bank_name"></el-input></el-form-item>
                    <el-form-item label="银行卡号" prop="rate"><el-input disabled  v-model="info.card_no"></el-input></el-form-item>
                    <el-form-item label="真名" prop="rate"><el-input disabled  v-model="info.real_name"></el-input></el-form-item>
                    <el-form-item label="提现金额" prop="money" :rules="[{required:true,message:'提现金额不能为空',trigger: 'blur' }]"><el-input placeholder="0.00" v-model="money"></el-input></el-form-item>
                </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="danger" @click="add_cash()">确 定</el-button>
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
          title:'提现记录',
          dialogVisible:false,
          info:{},
          money:0,
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        handleSelectionChange:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id = ids.join(',');
        },
        get_cash_log:function(){
            this.$post(this.$api.homeGetCashLog,{page:this.current_page}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })
        },
        get_add_cash_info:function(){
            
            this.$get(this.$api.homeAddCashLog).then(res=>{
                if(res.code == 200){
                    this.dialogVisible = true;
                    this.info = res.data;
                }else{
                    this.$message.error(res.msg);
                }
                
            });
        },
        add_cash:function(){
            this.$post(this.$api.homeAddCashLog,{money:this.money}).then(res=>{
                if(res.code == 200){
                    this.dialogVisible = false;
                    this.$message.success('申请成功');
                    this.get_cash_log();
                }else{
                    this.$message.error(res.msg);
                }
            });
        },
        
        current_change:function(e){
            this.current_page = e;
            this.get_cash_log();
        },
    },
    created() {
        this.get_cash_log();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.red{
    background:#ca151e;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    display: block;
}
.green{
    background-color:#42b983;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    display: block;
}

</style>