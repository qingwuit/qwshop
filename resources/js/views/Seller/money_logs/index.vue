<template>
    <div class="qingwu">
        <div class="admin_table_page_title">店铺资金</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
            <div class="money_logs_title">
                <a-descriptions bordered :column="{ xxl: 4, xl: 3, lg: 3, md: 3, sm: 2, xs: 1 }">
                    <a-descriptions-item label="店铺余额">
                    <font color="red">￥{{store_info.store_money||0.00}}</font>
                    </a-descriptions-item>
                    <a-descriptions-item label="冻结资金">
                    <font color="#999">￥{{store_info.store_frozen_money||0.00}}</font>
                    </a-descriptions-item>
                    <!-- <a-descriptions-item label="操作">
                        <a-button type="danger">立即提现</a-button>
                    </a-descriptions-item>
                    <a-descriptions-item label="-">
                    </a-descriptions-item> -->
                    
                </a-descriptions>
            </div>
            
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="money" slot-scope="rows">
                    <font v-if="rows.money>=0" color="red">+{{rows.money}}</font>
                    <font v-if="rows.money<0" color="#42b983">{{rows.money}}</font>
                </span>
                <span slot="is_type" slot-scope="rows">
                    <a-tag v-if="rows.is_type==0">余额</a-tag>
                    <a-tag v-if="rows.is_type==1">冻结资金</a-tag>
                    <a-tag v-if="rows.is_type==2">积分</a-tag>
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
              {title:'名称',dataIndex:'name'},
              {title:'资金',key:'id',scopedSlots: { customRender: 'money' }},
              {title:'类型',key:'id',scopedSlots: { customRender: 'is_type' }},
              {title:'原因',dataIndex:'info'},
              {title:'创建时间',dataIndex:'created_at'},
          ],
          list:[],
          store_info:{},
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
            this.$get(this.$api.sellerMoneyLogs,this.params).then(res=>{
                this.total = res.data.total;
                this.store_info = res.data.store_info
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
.money_logs_title{
    margin-bottom: 30px;
}
</style>