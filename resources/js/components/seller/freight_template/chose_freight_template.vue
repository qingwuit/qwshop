<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><el-button type="primary" icon="el-icon-plus" @click="chose_freight_template">选择确定</el-button></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>
                
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column prop="name" label="模版名称"></el-table-column>
                    <el-table-column prop="price" label="默认价格">
                        <template slot-scope="scope">
                            {{scope.row.price}} 元
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
          chose_list:[],
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
            this.chose_list = e;
        },
        get_freight_template_list:function(){
            this.$get(this.$api.getFreightTemplateList,{page:this.current_page}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        current_change:function(e){
            this.current_page = e;
            this.get_freight_template_list();
        },
        chose_freight_template:function(){
            this.$emit("chose_freight_template",this.chose_list);
        },
    },
    created() {
        this.get_freight_template_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>