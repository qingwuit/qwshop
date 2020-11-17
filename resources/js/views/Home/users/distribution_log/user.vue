<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                分销佣金
            </div>
            <div class="x20"></div>

            <div class="home_search_block">
                <div class="check_types">
                    <ul>
                        <li :class="params.lev==0?'red':''" @click="onChangeLev(0)">一级分销</li>
                        <li :class="params.lev==1?'red':''" @click="onChangeLev(1)">二级分销</li>
                        <li :class="params.lev==2?'red':''" @click="onChangeLev(2)">三级分销</li>
                    </ul>
                </div>
            </div>
            <div class="admin_table_list" >
                <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
                
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
              lev:0,
          },
          total:0, //总页数
          columns:[
              {title:'用户昵称',fixed:'left',dataIndex:'nickname'},
              {title:'登录时间',dataIndex:'login_time'},
              {title:'创建时间',dataIndex:'created_at'},
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
            this.$get(this.$api.homeDistributionUser,this.params).then(res=>{
                this.total = res.data.total;
                this.list = res.data.data;
            });
        },
        onChangeLev(e){
            this.params.lev = e;
            this.params.page = 1;
            this.onload();
        }
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.check_types{
    margin-bottom: 20px;
    ul:after{
        clear: both;
        display: block;
        content:'';
    }
    ul li{
        width: 120px;
        line-height: 35px;
        text-align: center;
        display: block;
        float: left;
        cursor: pointer;
        border-left:1px solid #efefef;
        border-top:1px solid #efefef;
        border-bottom:1px solid #efefef;
        &:last-child{
            border-right: 1px solid #efefef;
        }
        &:hover{
            background: #ca151e;
            color:#efefef;
            border-color: #ca151e;
        }
        &.red{
            background: #ca151e;
            color:#efefef;
            border-color: #ca151e;
        }
    }
}
</style>