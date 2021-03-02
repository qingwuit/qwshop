<template>
    <div class="qingwu">
        <div class="admin_table_page_title">全国地址管理</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push('/Admin/areas/form')" type="primary" icon="plus">添加</a-button>
            <a-button @click="clear_cache"><a-font type="iconitemno_0"></a-font>清除缓存</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" @expand="expanded" :loading="loading" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="expand"></span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Admin/areas/form/'+rows.id)">编辑</a-button>
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
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',key:'id',fixed:'left',scopedSlots: { customRender: 'expand' }},
              {title:'地址',dataIndex:'name',fixed:'left'},
              {title:'编号',dataIndex:'code',fixed:'left'},
            //   {title:'链接',dataIndex:'link'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[], // 展示数据
          area:[], // 数据库获取数据
          loading: false, // 数据加载状态 
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
                    this.$delete(this.$api.adminAreas+'/'+ids).then(res=>{
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
            this.$get(this.$api.adminAreasClearCache).then(res=>{
                return this.$message.success(res.msg)
            });
        },
        onload(){
            this.loading = true;
            this.$get(this.$api.adminAreas).then(res=>{
                this.loading = false;
                this.area = res.data;
                res.data.forEach(item=>{
                    let info = {};
                    info = {name:item.name,pid:item.pid,id:item.id,code:item.code,deep:item.deep,children:[]}
                    this.list.push(info);
                })
                // this.list = res.data;
            });
        },
        // 展开再去获取数据防止卡住
        expanded(status,rows){
            if(rows.deep == 0){
                this.area.forEach((items,key)=>{
                    if(items.code == rows.code){
                        this.list[key].children = items.children;
                    }
                })
            }
            
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