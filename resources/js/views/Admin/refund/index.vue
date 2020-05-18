<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <!-- <div><router-link to="/Admin/menus/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div> -->
                    
                    <div><el-input v-model="where.order_no" placeholder="请输入订单号"></el-input></div>
                    <!-- <div style="width:130px;"><el-date-picker format="yyyy-MM-dd HH:mm" type="date" placeholder="开始日期" v-model="where.times[0]" style="width: 100%;"></el-date-picker></div> -->
                    <div><el-date-picker format="yyyy-MM-dd HH:mm" v-model="where.times" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker></div>
                    <div><el-button icon="el-icon-search" @click="get_order_list()">条件筛选</el-button></div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <!-- <el-table-column prop="id" label="#"  width="70px"></el-table-column> -->
                    <el-table-column label="商品名称" width="270px">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><el-image style="width: 50px; height: 50px" :src="scope.row.image"><div slot="error" class="image-slot"><i class="el-icon-picture-outline"></i></div></el-image></dt>
                                <dd class="table_dl_dd_all" :title="scope.row.order_name">{{ scope.row.order_name }}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column prop="order_no" label="订单号"></el-table-column>
                    <el-table-column label="订单状态">
                        <template slot-scope="scope">
                            <el-tag type="success" v-if="scope.row.cn_status=='订单完成'">{{scope.row.cn_status}}</el-tag>
                            <el-tag type="warning" v-else-if="scope.row.cn_status=='售后处理'">{{scope.row.cn_status}}</el-tag>
                            <el-tag v-else-if="scope.row.cn_status=='等待发货'">{{scope.row.cn_status}}</el-tag>
                            <el-tag type="danger" v-else-if="scope.row.cn_status=='等待支付'">{{scope.row.cn_status}}</el-tag>
                            <el-tag type="info" v-else>{{scope.row.cn_status}}</el-tag>
                        </template> 
                    </el-table-column>
                    <!-- <el-table-column label="PC推荐首页">
                        <template slot-scope="scope">
                            <div :class="scope.row.is_index==1?'green_round':'gray_round'" @click="goods_index(scope.row.id)"></div>
                        </template> 
                    </el-table-column> -->
                    <el-table-column prop="total_price" label="价格"></el-table-column>
                    <el-table-column label="加入时间">
                        <template slot-scope="scope">
                            <div>{{scope.row.add_time|formatDate}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push('/Admin/order/info/'+scope.row.id)">查看订单</el-button>
                            <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
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
          goods_verify_count:0,
          where:{
              times:[],
              type:'REFUND',
              order_no:'',
          },
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
        get_order_list:function(){
            let where = {};
            where.page = this.current_page;
            where.params = this.where;
            this.$get(this.$api.getAdminOrderList,where).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.list = res.data.data;
                this.current_page = res.data.current_page;
            })

        },
        // 删除处理
        // del:function(id){
        //     if(this.$isEmpty(id)){
        //         return this.$message.error('请先选择删除的对象');
        //     }
        //     this.$post(this.$api.delGoods,{id:id}).then(res=>{
        //         if(res.code == 200){
        //             this.get_order_list();
        //             return this.$message.success("删除成功");
        //         }else{
        //             return this.$message.error("删除失败");
        //         }
        //     });
        // },

        current_change:function(e){
            this.current_page = e;
            this.get_order_list();
        },
    },
    created() {
        this.get_order_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.table_dl_dd_all{
    height: 50px;
    overflow: hidden;
}
</style>