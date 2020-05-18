<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
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
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column label="商品名称">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><img :src="scope.row.goods.goods_master_image||require('@/assets/default_pic.png')" width="50px" height="50px"></dt>
                                <dd class="table_dl_dd_all">{{ scope.row.goods.goods_name }}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column label="参加状态">
                        <template slot-scope="scope">
                            <el-tag type="success" v-if="scope.row.status==1">通过</el-tag>
                            <el-tag type="warning" v-else-if="scope.row.status==0">审核中</el-tag>
                        </template> 
                    </el-table-column>
                    <el-table-column label="是否上架">
                        <template slot-scope="scope">
                            <div :class="scope.row.goods.goods_status==1?'green_round':'gray_round'"></div>
                        </template> 
                    </el-table-column>
                    <el-table-column prop="all_goods_num" label="库存">
                        <template slot-scope="scope">
                            <div>{{scope.row.goods.all_goods_num||scope.row.goods.goods_num}}</div>
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
          adv_position_id:0,
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
        get_seckill_list:function(){
            this.$post(this.$api.sellerGetAddSeckillGoods,{page:this.current_page,sid:this.$route.params.sid}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        // 删除处理
        del:function(id){
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.sellerDelSeckillGoods,{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_seckill_list();
                    return this.$message.success("删除成功");
                }else{
                    return this.$message.error("删除失败");
                }
            });
        },
        current_change:function(e){
            this.current_page = e;
            this.get_seckill_list();
        },
    },
    created() {
        this.get_seckill_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
</style>