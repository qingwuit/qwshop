<template>
    <div class="qingwu">
        <div class="admin_table_page_title">广告管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push(!$isEmpty($route.query.ap_id)?'/Admin/advs/form?ap_id='+$route.query.ap_id:'/Admin/advs/form')" type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="pic" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.image_url" :src="rows.image_url"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Admin/advs/form/'+rows.id)">编辑</a-button>
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
              {label:'广告名',name:'name',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'广告名',fixed:'left',key:'id',scopedSlots: { customRender: 'pic' }},
              {title:'链接',dataIndex:'url'},
              {title:'开始时间',fixed:'right',dataIndex:'adv_start'},
              {title:'结束时间',fixed:'right',dataIndex:'adv_end'},
            //   {title:'修改时间',dataIndex:'updated_at'},
            //   {title:'创建时间',dataIndex:'created_at'},
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
                    this.$delete(this.$api.adminAdvs+'/'+ids).then(res=>{
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
            if(!this.$isEmpty(this.$route.query.ap_id)){
                this.params.ap_id = this.$route.query.ap_id;
            }
            this.$get(this.$api.adminAdvs,this.params).then(res=>{
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