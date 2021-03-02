<template>
    <div class="qingwu">
        <div class="admin_table_page_title">菜单管理</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push(is_type==0?'/Admin/menus/form':'/Admin/menus/form?is_type=1')" type="primary" icon="plus">添加</a-button>
            <a-button @click="clear_cache"><a-font type="iconitemno_0"></a-font>清除缓存</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="is_sort" slot-scope="rows">
                    <a-input type="number" @blur="sortChange(rows)" v-model="rows.is_sort" />
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push(is_type==0?'/Admin/menus/form/'+rows.id:'/Admin/menus/form/'+rows.id+'?is_type=1')">编辑</a-button>
                </span>
            </a-table>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          is_type:0,
          selectedRowKeys:[], // 被选择的行
          columns:[
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'菜单名称',dataIndex:'name'},
              {title:'链接',dataIndex:'link'},
              {title:'排序',fixed:'right',width:'120px',scopedSlots: { customRender: 'is_sort' }},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 选择框被点击
        onSelectChange(selectedRowKeys) {
            this.selectedRowKeys = selectedRowKeys;
        },
        // 删除
        del(){
            if(this.selectedRowKeys.length==0){
                return this.$message.error('未选择数据.');
            }
            this.$confirm({
                title: '你确定要删除选择的数据？',
                content: '确定删除后无法恢复.',
                okText: '是',
                okType: 'danger',
                cancelText: '取消',
                onOk:()=> {
                    let ids = this.selectedRowKeys.join(',');
                    this.$delete(this.$api.adminMenus+'/'+ids).then(res=>{
                        if(res.code == 200){
                            this.onload();
                            this.$message.success('删除成功');
                        }else{
                            this.$message.error(res.msg)
                        }
                    });
                    
                },
            });
        },
        // 清空缓存
        clear_cache(){
            this.$get(this.$api.adminMenusClearCache).then(res=>{
                return this.$message.success(res.msg)
            });
        },
        onload(){
            let is_type = this.$route.query.is_type;
            let params = {};
            if(!this.$isEmpty(is_type)){
                params.is_type = is_type;
                this.is_type = is_type;
            }
            this.$get(this.$api.adminMenus,params).then(res=>{
                this.list = res.data;
            });
        },
        // 排序移动
        sortChange(rows){
            let api = this.$apiHandle(this.$api.adminMenus,rows.id);
            this.$put(api.url,rows).then(res=>{
                if(res.code == 200){
                    this.onload();
                    return this.$message.success(res.msg)
                }else{
                    return this.$message.error(res.msg)
                }
            })
            
        }
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>