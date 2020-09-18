<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                <span><div class="btn" @click="$router.push('/user/favorite')">收藏商品</div></span>
                关注店铺
            </div>
            <div class="x20"></div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                    <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.store_logo" :src="rows.store_logo"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.store_name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                    <span slot="action" slot-scope="rows">
                        <div class="default_btn" @click="$router.push('/store/'+rows.out_id)">查看</div>
                        <div class="default_btn" @click="del(rows.out_id)">删除</div>
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
              is_type:1,
          },
          total:0, //总页数
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'店铺',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
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
        // 删除
        del(ids){
            this.$confirm({
                title: '你确定要删除选择的数据？',
                content: '确定删除后无法恢复.',
                okText: '是',
                okType: 'danger',
                cancelText: '取消',
                onOk:()=> {
                    this.$delete(this.$api.homeFav+'/'+ids,{is_type:1}).then(res=>{
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
            this.$get(this.$api.homeFav,this.params).then(res=>{
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