<template>
    <div class="qingwu">
        <div class="admin_table_page_title">拼团管理</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push('/Seller/collectives/form')" type="primary" icon="plus">添加</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.goods.goods_master_image" :src="rows.goods.goods_master_image"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.goods.goods_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="rate" slot-scope="rows">
                    <a-tag color="blue">折扣 {{rows.discount||0}} %</a-tag>
                </span>
             
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Seller/collectives/form/'+rows.id)">编辑</a-button>
                    <a-button icon="read" @click="$router.push('/Seller/collective_logs/'+rows.id)">详情</a-button>
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
            //   {title:'#',dataIndex:'id',fixed:'left'},
              {title:'商品名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'成团人数',dataIndex:'need'},
              {title:'折扣',key:'id',scopedSlots: { customRender: 'rate' }},
              {title:'编辑时间',dataIndex:'updated_at'},
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
                    this.$delete(this.$api.sellerCollectives+'/'+ids).then(res=>{
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
            this.$get(this.$api.sellerCollectives,this.params).then(res=>{
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