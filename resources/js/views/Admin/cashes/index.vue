<template>
    <div class="qingwu">
        <div class="admin_table_page_title">资金提现</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="money" slot-scope="rows">
                        <font color="#42b983">-{{rows.money}}</font>
                    </span>
                    <span slot="cash_status" slot-scope="rows">
                        <div title="等待" class="gray_round" v-if="rows.cash_status==0" ></div>
                        <div title="成功" class="green_round" v-if="rows.cash_status==1" ></div>
                        <div title="拒绝" class="red_round" v-if="rows.cash_status==2" ></div>
                    </span>
                    <span slot="action" slot-scope="rows">
                        <a-button icon="check-circle" :disabled="rows.cash_status>0" @click="pass(rows.id)">通过</a-button>
                        <a-button icon="close-circle" :disabled="rows.cash_status>0" type="danger" @click="refuse(rows)">拒绝</a-button>
                    </span>
            </a-table>
            <div class="admin_pagination" v-if="total>0">
                <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
            </div>
        </div>

        <a-modal v-model="visible" title="拒绝原因" @ok="handleOk"><a-input  v-model="info.refuse_info" /></a-modal>
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
          info:{
              cash_status:0,
              refuse_info:'',
          },
          visible:false,
          total:0, //总页数
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'名称',fixed:'left',dataIndex:'name'},
              {title:'提现银行',dataIndex:'bank_name'},
              {title:'银行卡号',dataIndex:'card_no'},
              {title:'提现金额',key:'id',scopedSlots: { customRender: 'money' }},
              {title:'提现状态',key:'id',scopedSlots: { customRender: 'cash_status' }},
              {title:'备注',dataIndex:'remark'},
              {title:'拒绝原因',dataIndex:'refuse_info'},
              {title:'提现时间',dataIndex:'created_at'},
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
            this.$get(this.$api.adminCashes,this.params).then(res=>{
                this.total = res.data.total;
                this.store_info = res.data.store_info
                this.list = res.data.data;
            });
        },
        pass(id){
            this.$confirm({
                title: '你确定通过提现申请？',
                content: '请先确定已经打完款.',
                okText: '是',
                okType: 'danger',
                cancelText: '取消',
                onOk:()=> {
                    this.info.cash_status = 1;
                    this.$put(this.$api.adminCashes+'/'+id,this.info).then(res=>{
                        if(res.code == 200){
                            this.onload();
                            this.$message.success('通过申请');
                        }else{
                            this.$message.error(res.msg)
                        }
                    });
                    
                },
            });
        },
        refuse(e){
            this.info = e;
            this.visible = true;
        },
        handleOk(){
            this.info.cash_status = 2;
            this.$put(this.$api.adminCashes+'/'+this.info.id,this.info).then(res=>{
                if(res.code == 200){
                    this.onload();
                    this.$message.success(res.msg);
                }else{
                    this.$message.error(res.msg)
                }
                this.visible = false;
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