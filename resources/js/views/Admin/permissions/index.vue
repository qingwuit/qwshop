<template>
    <div class="qingwu">
        <div class="admin_table_page_title">接口管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push('/Admin/permissions/form')" type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="permission_group" slot-scope="rows">
                    <a-tag color="blue">{{rows.name||'暂无'}}</a-tag>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Admin/permissions/form/'+rows.id)">编辑</a-button>
                </span>
            </a-table>
            <div class="admin_pagination" v-if="total>0">
                <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
            </div>
        </div>
    </div>
</template>

<script>
import adminSearch from '@/components/admin/search'
export default {
    components: {adminSearch,},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
          },
          total:0, //总页数
          searchConfig:[
              {label:'接口名称',name:'name',type:'text'},
              {label:'接口路由',name:'apis',type:'text'},
              {label:'所属分组',name:'pid',type:'select',data:[{label:'全部',value:''}]},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'接口名称',dataIndex:'name'},
              {title:'所属分组',dataIndex:'permission_group',scopedSlots: { customRender: 'permission_group' }},
              {title:'接口路由',dataIndex:'apis'},
              {title:'接口描述',dataIndex:'content'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        search(params){
            let page = this.params.page;
            let per_page = this.params.per_page;
            this.params = params;
            this.params.page = page;
            this.params.per_page = per_page;
            this.onload();
        },
        // 选择框被点击
        onSelectChange(selectedRowKeys) {
            this.selectedRowKeys = selectedRowKeys;
        },
        // 选择分页
        onChange(e){
            this.params.page = e;
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
                    this.$delete(this.$api.adminPermissions+'/'+ids).then(res=>{
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
        get_group(){
            this.$get(this.$api.adminPermissionGroups).then(res=>{
                let list = [];
                res.data.data.forEach(item=>{
                    list.push({label:item.name,value:item.id})
                })
                this.searchConfig.forEach(item=>{
                    if(item.name == 'pid'){
                        item.data = item.data.concat(list)
                    }
                })
            });
        },
        onload(){
            this.$get(this.$api.adminPermissions,this.params).then(res=>{
                this.total = res.data.total;
                this.list = res.data.data;
            });
        },
    },
    created() {
        this.onload();
        this.get_group(); // 分组
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>