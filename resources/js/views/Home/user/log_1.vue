<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            {{title}}
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <!-- <el-table-column prop="id" label="#"  width="70px"></el-table-column> -->
                    <el-table-column label="#" prop="log_no"></el-table-column>
                    <el-table-column label="标题" prop="name"></el-table-column>
                    <el-table-column label="余额" prop="money">
                        <template slot-scope="scope">
                            <div v-if="scope.row.money>0" class="red">+{{scope.row.money}}</div>
                            <div v-if="scope.row.money==0">{{scope.row.money}}</div>
                            <div v-if="scope.row.money<0"  class="green">{{scope.row.money}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="冻结资金" prop="frozen_money">
                        <template slot-scope="scope">
                            <div v-if="scope.row.frozen_money>0" class="red">+{{scope.row.frozen_money}}</div>
                            <div v-if="scope.row.frozen_money==0">{{scope.row.frozen_money}}</div>
                            <div v-if="scope.row.frozen_money<0"  class="green">{{scope.row.frozen_money}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="积分" prop="integral">
                        <template slot-scope="scope">
                            <div v-if="scope.row.integral>0" class="red">+{{scope.row.integral}}</div>
                            <div v-if="scope.row.integral==0">{{scope.row.integral}}</div>
                            <div v-if="scope.row.integral<0"  class="green">{{scope.row.integral}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="加入时间">
                        <template slot-scope="scope">
                            <div>{{scope.row.add_time|formatDate}}</div>
                        </template> 
                    </el-table-column>
                    
                    <!-- <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.goods.id)">删除</el-button>
                        </template>
                    </el-table-column> -->
                </el-table>
                <div class="home_fy_block" style="margin-top:40px;" >
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
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
          title:'冻结资金',
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
        get_money_log:function(){
            this.$post(this.$api.homeGetMoneyLog,{page:this.current_page,type:1}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        
        current_change:function(e){
            this.current_page = e;
            this.get_money_log();
        },
    },
    created() {
        this.get_money_log();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.red{
    color:#ca151e;
}
.green{
    color:#42b983;
}

</style>