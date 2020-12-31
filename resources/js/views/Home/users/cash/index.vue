<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                <span><div class="btn" @click="$router.push('/user/cash/form')">申请提现</div></span>
                资金提现
            </div>
            <div class="x20"></div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                    <span slot="money" slot-scope="rows">
                        <font color="#42b983">-{{rows.money}}</font>
                    </span>
                    <span slot="cash_status" slot-scope="rows">
                        <div title="等待" class="gray_round" v-if="rows.cash_status==0" ></div>
                        <div title="成功" class="green_round" v-if="rows.cash_status==1" ></div>
                        <div title="拒绝" class="red_round" v-if="rows.cash_status==2" ></div>
                    </span>
                </a-table>
                <div class="fy" style="margin-top:20px;" v-if="total>0">
                    <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
                </div>
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
              is_type:0,
          },
          total:0, //总页数
          columns:[
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'名称',fixed:'left',dataIndex:'name'},
              {title:'提现银行',dataIndex:'bank_name'},
              {title:'银行卡号',dataIndex:'card_no'},
              {title:'提现金额',key:'id',scopedSlots: { customRender: 'money' }},
              {title:'提现状态',key:'id',scopedSlots: { customRender: 'cash_status' }},
              {title:'备注',dataIndex:'remark'},
              {title:'拒绝原因',dataIndex:'refuse_info'},
              {title:'提现时间',dataIndex:'created_at'},
              
          ],
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 选择分页
        onChange(e){
            this.params.page = e;
        },
        onload(){
            this.$get(this.$api.homeCash,this.params).then(res=>{
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