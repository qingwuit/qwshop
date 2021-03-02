<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                用户评论
            </div>
            <div class="x20"></div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                    <span slot="name" slot-scope="rows">
                        <div class="admin_pic_txt">
                            <div class="img"><img v-if="rows.goods.id" :src="rows.goods.goods_master_image"><a-icon v-else type="picture" /></div>
                            <div class="text">{{rows.goods.goods_name}}</div>
                            <div class="clear"></div>
                        </div>
                    </span>
                    <span slot="action" slot-scope="rows">
                        <a-button icon="edit" @click="$router.push('/user/order_comments/form/'+rows.id)" :disabled="rows.goods.id==0">编辑</a-button>
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
              {title:'商品名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'综合评分',dataIndex:'score'},
              {title:'描述相符',dataIndex:'agree'},
              {title:'服务态度',dataIndex:'service'},
              {title:'发货速度',dataIndex:'speed'},
              {title:'内容',dataIndex:'content'},
              {title:'建立时间',dataIndex:'created_at'},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
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
            this.$get(this.$api.homeOrderComments,this.params).then(res=>{
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