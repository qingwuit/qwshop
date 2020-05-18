<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/adv_position/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
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
                    <el-table-column prop="ap_name" label="广告位"></el-table-column>
                    <el-table-column prop="ap_width" label="宽度">
                        <template slot-scope="scope">
                            {{scope.row.ap_width}} px
                        </template>
                    </el-table-column>
                    <el-table-column prop="ap_height" label="高度">
                        <template slot-scope="scope">
                            {{scope.row.ap_height}} px
                        </template>
                    </el-table-column>
                    <el-table-column prop="ap_isuse" label="状态">
                        <template slot-scope="scope">
                            <div :class="scope.row.ap_isuse==1?'green_round':'gray_round'"></div>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="320px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push({name:'adv_position_form',params:{id:scope.row.id}})">编辑</el-button>
                            <el-button icon="el-icon-search" @click="$router.push({name:'adv_index',params:{adv_position_id:scope.row.id}})" >查看广告</el-button>
                            <el-button icon="el-icon-plus" @click="$router.push({name:'adv_form',params:{adv_position_id:scope.row.id}})" >添加广告</el-button>
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
        get_adv_position_list:function(){
            this.$get(this.$api.getAdvPositionList,{page:this.current_page}).then(res=>{
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
            this.$post(this.$api.delAdvPosition,{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_adv_position_list();
                    return this.$message.success("删除成功");
                }else{
                    return this.$message.error("删除失败");
                }
            });
        },
        current_change:function(e){
            this.current_page = e;
            this.get_adv_position_list();
        },
    },
    created() {
        this.get_adv_position_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
</style>