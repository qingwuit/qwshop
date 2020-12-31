<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                平台积分
            </div>
            <div class="x20"></div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                    <span slot="money" slot-scope="rows">
                        <font v-if="rows.money>=0" color="red">+{{rows.money}}</font>
                        <font v-if="rows.money<0" color="#42b983">{{rows.money}}</font>
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
              is_type:2,
          },
          total:0, //总页数
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'名称',dataIndex:'name'},
              {title:'资金',key:'id',scopedSlots: { customRender: 'money' }},
              {title:'原因',dataIndex:'info'},
              {title:'建立时间',dataIndex:'created_at'},
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
            this.$get(this.$api.homeMoneyLog,this.params).then(res=>{
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