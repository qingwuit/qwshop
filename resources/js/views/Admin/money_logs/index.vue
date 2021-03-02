<template>
    <div class="qingwu">
        <div class="admin_table_page_title">用户资金</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
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
                <span slot="is_seller" slot-scope="rows">
                    <div class="green_round" v-if="rows.is_seller==1"></div>
                    <div class="gray_round" v-if="rows.is_seller==0"></div>
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
    components: {adminSearch},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
          },
          total:0, //总页数
          selectedRowKeys:[], // 被选择的行
          searchConfig:[
              {label:'用户ID',name:'user_id',type:'text'},
              {label:'是否店铺',name:'is_seller',type:'select',data:[{label:'全部',value:''},{label:'是',value:1},{label:'否',value:0}]},
          ],
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'名称',dataIndex:'name'},
            //   {title:'手机',dataIndex:'phone'},
              {title:'资金',key:'id',scopedSlots: { customRender: 'money' }},
              {title:'类型',key:'id',scopedSlots: { customRender: 'is_type' }},
              {title:'是否店铺',key:'id',scopedSlots: { customRender: 'is_seller' }},
              {title:'原因',dataIndex:'info'},
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
       search(params){
            let page = this.params.page;
            let per_page = this.params.per_page;
            this.params = params;
            this.params.page = page;
            this.params.per_page = per_page;
            this.onload();
        },
        onload(){
            this.$get(this.$api.adminMoneyLogs,this.params).then(res=>{
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