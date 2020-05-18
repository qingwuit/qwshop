<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/menus/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                </div>
            </div>
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="后台栏目" name="admin">
                    <div class="admin_table_main">
                        <el-table :data="list" @selection-change="handleSelectionChange" row-key="id" :tree-props="{children: 'children',hasChildren:'hasChildren'}">
                            <el-table-column type="selection"></el-table-column>
                            <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                            <el-table-column prop="name" label="栏目名称"></el-table-column>
                            <el-table-column prop="url" label="链接"></el-table-column>
                            <el-table-column prop="is_sort" label="排序"></el-table-column>
                            <el-table-column label="操作" fixed="right" width="120px">
                                <template slot-scope="scope">
                                    <el-button icon="el-icon-edit" @click="$router.push({name:'menus_form',params:{id:scope.row.id}})">编辑</el-button>
                                    <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="商家栏目" name="seller">
                    <div class="admin_table_main">
                        <el-table :data="list" @selection-change="handleSelectionChange" row-key="id" :tree-props="{children: 'children',hasChildren:'hasChildren'}">
                            <el-table-column type="selection"></el-table-column>
                            <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                            <el-table-column prop="name" label="栏目名称"></el-table-column>
                            <el-table-column prop="url" label="链接"></el-table-column>
                            <el-table-column prop="is_sort" label="排序"></el-table-column>
                            <el-table-column label="操作" fixed="right" width="120px">
                                <template slot-scope="scope">
                                    <el-button icon="el-icon-edit" @click="$router.push({name:'menus_form',params:{id:scope.row.id}})">编辑</el-button>
                                    <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-tab-pane>
                
                
            </el-tabs>
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
          all_list:{},
          select_id:'',
          activeName:'admin',
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
        get_menus_list:function(){
            let _this = this;
            this.$get(this.$api.getMenusList).then(function(res){
                _this.all_list = res.data;
                _this.list = res.data['admin'];
            })

        },
        handleClick:function(){
            if(this.activeName == 'admin'){
                return  this.list = this.all_list['admin'];
            }
            return this.list = this.all_list['seller'];
        },
        // 删除处理
        del:function(id){
            let _this = this;
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delMenus,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_menus_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败");
                }
            });
        }
    },
    created() {
        this.get_menus_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>