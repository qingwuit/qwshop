<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                我的优惠券
            </div>
            <div class="x20"></div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                    <span slot="status" slot-scope="rows">
                        <a-tag v-if="rows.status==0" >未使用</a-tag>
                        <a-tag v-else color="green">已使用</a-tag>
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
          },
          total:0, //总页数
          columns:[
              {title:'优惠券名称',fixed:'left',dataIndex:'name'},
              {title:'店铺',dataIndex:'nickname'},
              {title:'优惠券额度',dataIndex:'money'},
              {title:'消费金额',dataIndex:'use_money'},
              {title:'使用状态',key:'id',scopedSlots: { customRender: 'status' }},
              {title:'领取时间',dataIndex:'created_at'},
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
            this.$get(this.$api.homeCoupon,this.params).then(res=>{
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