<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/integral/add"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
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
                                <dt><el-image style="width: 50px; height: 50px" :src="scope.row.goods_master_image"><div slot="error" class="image-slot"><i class="el-icon-picture-outline"></i></div></el-image></dt>
                                <dd class="table_dl_dd_all">{{ scope.row.goods_name }}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column label="是否上架">
                        <template slot-scope="scope">
                            <div :class="scope.row.goods_status==1?'green_round':'gray_round'" @click="goods_status(scope.row.id)"></div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="热门推荐">
                        <template slot-scope="scope">
                            <div :class="scope.row.is_hot==1?'green_round':'gray_round'" @click="goods_index(scope.row.id)"></div>
                        </template> 
                    </el-table-column>
                    <el-table-column prop="goods_price" label="积分"></el-table-column>
                    <el-table-column prop="all_goods_num" label="库存">
                        <template slot-scope="scope">
                            <div>{{scope.row.all_goods_num||scope.row.goods_num}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="加入时间">
                        <template slot-scope="scope">
                            <div>{{scope.row.add_time|formatDate}}</div>
                        </template> 
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push('/Admin/integral/edit/'+scope.row.id)">编辑</el-button>
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
        get_goods_list:function(){
            this.$get(this.$api.getIntegralList,{page:this.current_page}).then(res=>{
                this.list = res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            })

        },
        // 删除处理
        del:function(id){
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delIntegral,{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_goods_list();
                    return this.$message.success("删除成功");
                }else{
                    return this.$message.error("删除失败");
                }
            });
        },
        // 指定ID修改状态
        goods_status:function(id){
            this.$post(this.$api.goodsStatusIntegral,{id:id}).then(res=>{
                if(res.code==200){
                    this.$message.success('操作成功');
                }else{
                    this.$message.success('操作失败');
                }
                this.get_goods_list();
            });
        },
        goods_index:function(id){
            this.$post(this.$api.goodsHotIntegral,{id:id}).then(res=>{
                if(res.code==200){
                    this.$message.success('操作成功');
                }else{
                    this.$message.success('操作失败');
                }
                this.get_goods_list();
            });
        },
  
        current_change:function(e){
            this.current_page = e;
            this.get_goods_list();
        },
    },
    created() {
        this.get_goods_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
</style>