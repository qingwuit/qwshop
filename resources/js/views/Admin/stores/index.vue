<template>
    <div class="qingwu">
        <div class="admin_table_page_title">店铺管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <a-badge :count="0" style="margin-right:20px">
                <a-button @click="to_nav(3)" icon="check-square">通过审核</a-button>
            </a-badge>
            <a-badge :count="count.wait" style="margin-right:20px">
                <a-button @click="to_nav(2)" icon="solution">等待审核</a-button>
            </a-badge>
            <a-badge :count="count.refuse" style="margin-right:20px">
                <a-button @click="to_nav(0)" icon="close-square">拒绝入驻</a-button>
            </a-badge>
            <a-badge :count="count.write" style="margin-right:20px">
                <a-button @click="to_nav(1)" icon="book">填写资料</a-button>
            </a-badge>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="status" slot-scope="rows">
                    <div :class="rows.store_status==1?'green_round':'red_round'" style="margin:0 auto"></div>
                </span>
                <span slot="verify" slot-scope="rows">
                    <a-tooltip v-if="rows.store_verify==0" placement="topLeft" :title="rows.store_refuse_info"><a-tag color="red">{{rows.store_verify_cn}}</a-tag></a-tooltip>
                    <a-tag v-if="rows.store_verify==1" color="orange">{{rows.store_verify_cn}}</a-tag>
                    <a-tag v-if="rows.store_verify==2" color="blue">{{rows.store_verify_cn}}</a-tag>
                    <a-tag v-if="rows.store_verify==3" color="green">{{rows.store_verify_cn}}</a-tag>
                </span>
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.store_logo" :src="rows.store_logo"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.store_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="read" @click="$router.push('/Admin/stores/form/'+rows.id)">查看详情</a-button>
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
              store_verify:3,
          },
          total:0, //总页数
          searchConfig:[
              {label:'店铺名称',name:'store_name',type:'text'},
              {label:'公司名称',name:'store_company_name',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'店铺名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'店铺状态',key:'id',fixed:'left',scopedSlots: { customRender: 'status' }},
              {title:'审核状态',key:'id',fixed:'left',scopedSlots: { customRender: 'verify' }},
              {title:'所属公司',dataIndex:'store_company_name'},
              {title:'法人名称',dataIndex:'legal_person'},
              {title:'联系电话',dataIndex:'store_phone'},
              {title:'店铺余额',dataIndex:'store_money'},
              {title:'申请时间',fixed:'right',dataIndex:'created_at'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[],
          count:[],
      };
    },
    watch: {
        '$route'(to,from) {
            // eslint-disable-next-line no-console
            console.log(to,from)
            this.onload();
        },
        '$route' (to, from) {
            console.log(to,from)            
        }
    },
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
                    this.$delete(this.$api.adminStores+'/'+ids).then(res=>{
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
            this.$get(this.$api.adminStores,this.params).then(res=>{
                this.list = res.data.data;
                this.count = res.data.count;
                this.total = res.data.total;
            });
        },
        to_nav(e){
            this.params.store_verify = e;
            this.params.page = 1;
            this.onload();
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