<template>
    <div class="qingwu">
        <div class="admin_table_page_title">订单结算</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
            <a-button @click="handleSet()" type="primary" icon="tool">手动结算</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="status" slot-scope="rows">
                    <a-tag v-if="rows.status==0" color="blue">未结算</a-tag>
                    <a-tag v-else color="green">已结算</a-tag>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="read" @click="$router.push('/Admin/order_settlements/form/'+rows.settlement_no)">查看</a-button>
                </span>
            </a-table>
            <div class="admin_pagination" v-if="total>0">
                <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
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
          params:{
              page:1,
              per_page:30,
          },
          total:0, //总页数
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',dataIndex:'settlement_no',fixed:'left'},
              {title:'总金额',dataIndex:'total_price'},
              {title:'结算金额',dataIndex:'settlement_price'},
              {title:'结算状态',key:'id',scopedSlots: { customRender: 'status' }},
              {title:'创建时间',dataIndex:'created_at'},
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
       
 
        onload(){
            this.$get(this.$api.adminOrderSettlements,this.params).then(res=>{
                this.total = res.data.total;
                this.list = res.data.data;
            });
        },
        handleSet(){
            this.$post(this.$api.adminOrderSettlements).then(res=>{
                return this.$returnInfo(res);
            });
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