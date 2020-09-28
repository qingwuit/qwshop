<template>
    <div class="qingwu">
        <div class="admin_table_page_title"><a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>订单管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            <!-- <a-button @click="$router.push('/Admin/orders/form')" type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button> -->
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.order_image" :src="rows.order_image"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.order_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="total_price" slot-scope="rows">
                    <font color="red">￥{{rows.total_price}}</font>
                </span>
                <span slot="order_status" slot-scope="rows">
                    <a-tag color="red" v-if="rows.order_status==0">{{rows.order_status_cn}}</a-tag>
                    <a-tag color="orange" v-if="rows.order_status==1">{{rows.order_status_cn}}</a-tag>
                    <a-tag color="blue" v-if="rows.order_status>1&&rows.order_status<6">{{rows.order_status_cn}}</a-tag>
                    <a-tag color="cyan" v-if="rows.order_status==6">{{rows.order_status_cn}}</a-tag>
                    <a-tag color="green" v-if="rows.order_status>=7">{{rows.order_status_cn}}</a-tag>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="read" @click="$router.push('/Admin/orders/form/'+rows.id)">查看详情</a-button>
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
          searchConfig:[
              {label:'订单号',name:'order_no',type:'text'},
              {label:'下单时间',name:'created_at',type:'date_picker'},
              {label:'订单状态',name:'order_status',type:'select',data:[
                  {label:'订单取消',value:0},
                  {label:'等待支付',value:1},
                  {label:'等待发货',value:2},
                  {label:'确认收货',value:3},
                  {label:'等待评论',value:4},
                  {label:'售后订单',value:5},
                  {label:'订单完成',value:6},
              ]},
              {label:'用户ID',name:'user_id',type:'text'},
              {label:'店铺ID',name:'store_id',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'订单名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'订单号',dataIndex:'order_no'},
              {title:'店铺',dataIndex:'store_name'},
              {title:'买家',dataIndex:'buyer_name'},
              {title:'订单总额',key:'id',scopedSlots: { customRender: 'total_price' }},
              {title:'订单状态',key:'id',scopedSlots: { customRender: 'order_status' }},
              {title:'下单时间',dataIndex:'created_at'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        search(params){
            let page = this.params.page;
            let per_page = this.params.per_page;

            // 事件需要格式化，后面再看看有没有更好得到办法
            if(!this.$isEmpty(params.created_at) && !this.$isEmpty(params.created_at[0])){
                params.created_at[0] = moment(params.created_at[0]).format('YYYY-MM-DD HH:mm:ss');
                params.created_at[1] = moment(params.created_at[1]).format('YYYY-MM-DD HH:mm:ss');
            }
            this.params = params;
            this.params.page = page;
            this.params.per_page = per_page;
            this.onload();
        },
        // 选择框被点击
        onSelectChange(selectedRowKeys) {
            this.selectedRowKeys = selectedRowKeys;
        },
        // 选择分页
        onChange(e){
            this.params.page = e;
        },
        // 删除
        del(){
            if(this.selectedRowKeys.length==0){
                return this.$message.error('未选择数据.');
            }
            this.$confirm({
                title: '你确定要删除选择的数据？',
                content: '确定删除后无法恢复.',
                okText: '是',
                okType: 'danger',
                cancelText: '取消',
                onOk:()=> {
                    let ids = this.selectedRowKeys.join(',');
                    this.$delete(this.$api.adminOrders+'/'+ids).then(res=>{
                        if(res.code == 200){
                            this.onload();
                            this.$message.success('删除成功');
                        }else{
                            this.$message.error(res.msg)
                        }
                    });
                    
                },
            });
        },
 
        onload(){
            if(!this.$isEmpty(this.$route.query.is_refund)){
                this.params.is_refund = 1;
            }
            if(!this.$isEmpty(this.$route.query.is_return)){
                this.params.is_return = 1;
            }
            this.$get(this.$api.adminOrders,this.params).then(res=>{
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