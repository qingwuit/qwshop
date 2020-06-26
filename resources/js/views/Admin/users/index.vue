<template>
    <div class="qingwu">
        <div class="admin_table_page_title">用户管理</div>
        <div class="unline underm"></div>
        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <a-button type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Admin/users/form/'+rows.id)">编辑</a-button>
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
    components: {
        adminSearch,
    },
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
          },
          total:0, //总页数
          searchConfig:[
            //   {label:'编号',name:'id',type:'text'},
            //   {label:'编号',name:'id2',type:'time_picker'},
              {label:'编号',name:'id3',type:'date_picker'},
              {label:'地区',name:'area',type:'select',data:[{label:'全部',value:''}]},
              {label:'昵称',name:'nickname',type:'text'},
              {label:'时间',name:'time',type:'text'},
              {label:'单号',name:'no',type:'text'},
              {label:'开始',name:'start',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'名称',dataIndex:'name'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[{id:'4',name:'123'},{id:'5',name:123}],
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
                    console.log('OK');
                    this.$message.success('删除成功');
                },
            });
        },
    },
    created() {},
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>