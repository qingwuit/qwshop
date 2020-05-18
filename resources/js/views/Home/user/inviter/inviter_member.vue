<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title" style="position: relative;">
            分销成员
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <!-- <el-table-column prop="id" label="#"  width="70px"></el-table-column> -->
                    <el-table-column label="成员名">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><el-image style="width: 50px; height: 50px" :src="scope.row.store.store_logo"><div slot="error" class="image-slot"><i class="el-icon-picture-outline"></i></div></el-image></dt>
                                <dd class="table_dl_dd_all">{{ scope.row.store.store_name }}</dd>
                            </dl>
                        </template>
                    </el-table-column>

                    <el-table-column label="分销级" prop='deep'>
                    </el-table-column>
                    
                    <!-- <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.goods.id)">取消关注</el-button>
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
        get_fav_list:function(){
            this.$post(this.$api.homeGetFavList,{page:this.current_page,is_type:1}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        
        current_change:function(e){
            this.current_page = e;
            this.get_fav_list();
        },
    },
    created() {
        this.get_fav_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>