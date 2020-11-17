<template>
    <div class="qingwu">
        <div class="admin_table_page_title"><a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>拼团详情</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.goods_master_image" :src="rows.goods_master_image"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.goods_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="rate" slot-scope="rows">
                    <a-tag color="blue">折扣 {{rows.discount||0}} %</a-tag>
                </span>
                <span slot="status" slot-scope="rows">
                    <div class="green_round" v-if="rows.status==1"></div>
                    <div class="yellow_round" v-if="rows.status==2"></div>
                    <div class="red_round" v-if="rows.status==0"></div>
                </span>
             
                <span slot="action" slot-scope="rows">
                    <a-button icon="read" @click="$router.push('/Seller/orders?collective_id='+rows.id)">订单详情</a-button>
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
              collective_id:0,
          },
          total:0, //总页数
          selectedRowKeys:[], // 被选择的行
          columns:[
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'商品名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'成团人数',dataIndex:'need'},
              {title:'下单数量',dataIndex:'orders_count'},
              {title:'折扣',key:'id',scopedSlots: { customRender: 'rate' }},
              {title:'状态',key:'id',scopedSlots: { customRender: 'status' }},
              {title:'建立时间',dataIndex:'created_at'},
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
            console.log(this.$route)
            this.params.collective_id = this.$route.params.collective_id
            this.$get(this.$api.sellerCollectiveLogs,this.params).then(res=>{
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