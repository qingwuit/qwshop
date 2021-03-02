<template>
    <div class="qingwu">
        <div class="admin_table_page_title">广告位管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push('/Admin/adv_positions/form')" type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="ap_width" slot-scope="rows">{{rows}}px</span>
                <span slot="ap_height" slot-scope="rows" >{{rows}}px</span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="plus" @click="$router.push('/Admin/advs/form?ap_id='+rows.id)">添加广告</a-button>
                    <a-button icon="search" @click="$router.push('/Admin/advs?ap_id='+rows.id)">查看广告</a-button>
                    <a-button icon="edit" @click="$router.push('/Admin/adv_positions/form/'+rows.id)">编辑</a-button>
                </span>
            </a-table>
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
              {label:'广告位名',name:'ap_name',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'广告位名',fixed:'left',dataIndex:'ap_name'},
              {title:'宽度',key:'id',scopedSlots: { customRender: 'ap_width' },dataIndex:'ap_width'},
              {title:'高度',key:'id',scopedSlots: { customRender: 'ap_height' },dataIndex:'ap_height'},
              {title:'修改时间',dataIndex:'updated_at'},
              {title:'创建时间',dataIndex:'created_at'},
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
                    this.$delete(this.$api.adminAdvPositions+'/'+ids).then(res=>{
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
        onload(){
            this.$get(this.$api.adminAdvPositions,this.params).then(res=>{
                this.list = res.data.data;
            });
        },
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>