<template>
    <div class="qingwu">
        <div class="admin_table_page_title"><a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>订单结算-{{$route.params.id}}</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="status" slot-scope="rows">
                    <a-tag v-if="rows.status==0" color="blue">未结算</a-tag>
                    <a-tag v-else color="green">已结算</a-tag>
                </span>
                <span slot="action" slot-scope="rows">
                    <div>{{rows.info}}</div>
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
              {title:'订单号',dataIndex:'order_no',fixed:'left'},
              {title:'总金额',dataIndex:'total_price'},
              {title:'结算金额',dataIndex:'settlement_price'},
              {title:'结算状态',key:'id',scopedSlots: { customRender: 'status' }},
              {title:'创建时间',dataIndex:'created_at'},
              {title:'备注',key:'id',scopedSlots: { customRender: 'action' }},
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
            this.$get(this.$api.adminOrderSettlements+'/'+this.$route.params.id,this.params).then(res=>{
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