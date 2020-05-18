<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/area/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                </div>
            </div>

            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" row-key="id" :lazy="true" :load="load" :tree-props="{children: 'children',hasChildren:'hasChildren'}">
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column prop="area_id" label="编号"></el-table-column>
                    <!-- <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push({name:'area_form',params:{id:scope.row.id}})">编辑</el-button>
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button>
                        </template>
                    </el-table-column> -->
                </el-table>
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
        get_area_list:function(){
            let _this = this;
            this.$get(this.$api.getAreaList).then(function(res){
                _this.list = res.data;
            })

        },
        load:function(e, treeNode, resolve){
            let treeNodes = treeNode // 无效代码
            this.$post(this.$api.getAreaChildren,{area_id:e.area_id}).then(res=>{
                resolve(res);
            })
        },
        // 删除处理
        del:function(id){
            let _this = this;
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delArea,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_area_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败");
                }
            });
        },
        
    },
    created() {
        this.get_area_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>