<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="#"><el-button disabled type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
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
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column label="秒杀名称" prop="name"></el-table-column>
                    <el-table-column prop="start_time" label="开始时间">
                        <template slot-scope="scope">
                            <div v-if="scope.row.adv_start<=0"> - </div>
                            <div v-else>{{scope.row.start_time|formatDate}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="end_time" label="结束时间">
                        <template slot-scope="scope">
                            <div v-if="scope.row.adv_end<=0"> - </div>
                            <div v-else>{{scope.row.end_time|formatDate}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="280px">
                        <template slot-scope="scope">
                            <!-- <el-button icon="el-icon-edit" @click="$router.push({name:'seckill_form',params:{id:scope.row.id}})">编辑</el-button> -->
                            <el-button icon="el-icon-plus" @click="dialogTableVisible=true;sid=scope.row.id;">添加商品</el-button>
                            <el-button icon="el-icon-menu" @click="$router.push('/Seller/seckill/seckill_goods/'+scope.row.id)">查看商品</el-button>
                            <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>

        <el-dialog title="选择参加秒杀产品" :visible.sync="dialogTableVisible">
            <el-table :data="list2" @selection-change="handleSelectionChange2" >
                <el-table-column type="selection"></el-table-column>
                <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                <el-table-column label="商品名称">
                    <template slot-scope="scope">
                        <dl class="table_dl">
                            <dt><img :src="scope.row.goods_master_image||require('@/assets/default_pic.png')" width="50px" height="50px"></dt>
                            <dd class="table_dl_dd_all">{{ scope.row.goods_name }}</dd>
                        </dl>
                    </template>
                </el-table-column>
                <el-table-column label="商品状态">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.goods_verify==1">通过</el-tag>
                        <el-tag type="warning" v-else-if="scope.row.goods_verify==2">审核中</el-tag>
                        <el-tag type="danger" v-else-if="scope.row.goods_verify==0">失败</el-tag>
                    </template> 
                </el-table-column>
                <el-table-column label="是否上架">
                    <template slot-scope="scope">
                        <div :class="scope.row.goods_status==1?'green_round':'gray_round'" @click="goods_status(scope.row.id)"></div>
                    </template> 
                </el-table-column>
                <el-table-column prop="goods_price" label="价格"></el-table-column>
                <el-table-column prop="all_goods_num" label="库存">
                    <template slot-scope="scope">
                        <div>{{scope.row.all_goods_num||scope.row.goods_num}}</div>
                    </template> 
                </el-table-column>
                
            </el-table>
            <div class="admin_table_main_pagination">
                <el-pagination @current-change="current_change2" background layout="prev, pager, next,jumper,total" :total="total_data2" :page-size="page_size2" :current-page="current_page2"></el-pagination>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="centerDialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="add_seckill_goods()">确 定</el-button>
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
          dialogTableVisible:false, // 选择商品
          // 秒杀商品
          list2:[],
          total_data2:0, // 总条数
          page_size2:20,
          current_page2:1,
          select_id2:'',
          sid:0,
      };
    },
    watch: {
        dialogTableVisible:function(){
            this.get_goods_list();
        }
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
        handleSelectionChange2:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id2 = ids.join(',');
        },
        add_seckill_goods:function(){
            if(this.$isEmpty(this.select_id2)){
                return this.$message.error('没有选择');
            }
            this.$post(this.$api.sellerAddSeckillGoods,{id:this.select_id2,seckill_id:this.sid}).then(res=>{
                this.dialogTableVisible = false;
                if(res.code == 200){
                    return this.$message.success('成功！');
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        get_seckill_list:function(){
            this.$get(this.$api.sellerGetSeckillList,{page:this.current_page}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        get_goods_list:function(){
            this.$get(this.$api.getGoodsList,{page:this.current_page2}).then(res=>{
                this.page_size2 = res.data.per_page;
                this.total_data2 = res.data.total;
                this.current_page2 = res.data.current_page;
                this.list2 = this.$formatGoods(res.data.data);
            })

        },
        // 删除处理
        del:function(id){
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delSeckill,{id:id}).then(res=>{
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
        current_change2:function(e){
            this.current_page = e;
            this.get_adv_list();
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