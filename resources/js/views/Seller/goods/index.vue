<template>
    <div class="qingwu">
        <div class="admin_table_page_title">商品管理</div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_table_handle_btn">
            
            <a-badge :count="99">
                <a-button @click="$router.push('/Admin/goods/form')" icon="solution">等待审核</a-button>
            </a-badge>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.thumb" :src="rows.thumb"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.goods_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="read" @click="$router.push('/Admin/goods/form/'+rows.id)">查看详情</a-button>
                </span>
            </a-table>
        </div>
    </div>
</template>

<script>
import adminSearch from '@/components/admin/search'
export default {
    components: {adminSearch,},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
          },
          total:0, //总页数
          searchConfig:[
              {label:'商品名称',name:'goods_name',type:'text'},
              {label:'商品编号',name:'goods_no',type:'text'},
              {label:'上架状态',name:'goods_status',type:'select',data:[{label:'上架',value:1},{label:'下架',value:0}]},
              {label:'审核状态',name:'goods_verify',type:'select',data:[{label:'已审核',value:1},{label:'未审核',value:0}]},
              {label:'商品品牌',name:'brand_id',type:'text'},
              {label:'商品分类',name:'class_id',type:'text'},
          ],
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'商品名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'上架状态',dataIndex:'goods_status'},
              {title:'PC推荐',dataIndex:'is_master'},
              {title:'价格',dataIndex:'goods_price'},
              {title:'库存',dataIndex:'goods_stock'},
              {title:'修改时间',dataIndex:'updated_at'},
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
            this.params = params;
            this.params.page = page;
            this.params.per_page = per_page;
            this.onload();
        },
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
                    this.$delete(this.$api.adminGoods+'/'+ids).then(res=>{
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
            this.$get(this.$api.adminGoods,this.params).then(res=>{
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