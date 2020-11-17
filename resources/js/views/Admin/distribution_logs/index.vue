<template>
    <div class="qingwu">
        <div class="admin_table_page_title">分销日志</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="status" slot-scope="rows">
                    <a-tag v-if="rows.status==0" color="blue">未结算</a-tag>
                    <a-tag v-else color="green">已结算</a-tag>
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
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'分销名称',fixed:'left',dataIndex:'name'},
              {title:'用户',dataIndex:'nickname'},
              {title:'店铺',dataIndex:'store_name'},
              {title:'订单号',dataIndex:'order_no'},
              {title:'商品金额',dataIndex:'money'},
              {title:'佣金比例',dataIndex:'lev'},
              {title:'佣金',dataIndex:'commission'},
              {title:'结算状态',key:'id',scopedSlots: { customRender: 'status' }},
              {title:'创建时间',dataIndex:'created_at'},
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
            this.$get(this.$api.adminDistributionLogs,this.params).then(res=>{
                this.total = res.data.total;
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