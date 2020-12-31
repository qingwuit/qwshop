<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            会员分析
        </div>
        <div class="unline underm"></div>

        <admin-search :searchConfig="searchConfig" @searchParams="search"></admin-search>

        <div class="admin_form">
            <div id="user_plot2" style="margin-top:20px;margin-bottom:40px"></div>
        </div>

        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false"  row-key="id">
            </a-table>
            <div class="admin_pagination" v-if="total>0">
                <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
            </div>
        </div>
    </div>
</template>

<script>
import adminSearch from '@/components/admin/search'
import { Line } from 'g2plot';
export default {
    components: {adminSearch,},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
          },
          searchConfig:[
              {label:'时间查询',name:'created_at',type:'date_picker'},
              {label:'查询方式',name:'is_type',type:'select',data:[
                  {label:'默认',value:0},
                  {label:'月份',value:1},
                  {label:'年度',value:2},
              ]},
          ],
          columns:[
              {title:'#',dataIndex:'id',fixed:'left'},
              {title:'用户名',dataIndex:'nickname'},
            //   {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
        
          list:[],
          plot:[{time:'2012-12-00',num:9.00},{time:'2012-12-01',num:2.00},{time:'2012-12-02',num:1.00},{time:'2012-12-03',num:5.00},{time:'2012-12-04',num:7.00},{time:'2012-12-05',num:5.00},{time:'2012-12-06',num:1.00}],
          isPlot:false,
          plotObj:null,
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 选择分页
        onChange(e){
            this.params.page = e;
        },
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
        get_info(){
            this.$get(this.$api.adminStatistics+'/user',this.params).then(res=>{
                this.plot = res.data.plot;
                this.list = res.data.list.data;
                this.total = res.data.list.total;
                this.get_user_plot();
            })
        },
        get_user_plot(){
            if(this.isPlot){
                this.plotObj.changeData(this.plot);
                return;
            }
            let data = this.plot;
            this.plotObj = new Line('user_plot2', {
                data,
                xField: 'time',
                yField: 'num',
                label: {},
                point: {
                    size: 4,
                    style: {
                    stroke: '#fff',
                    lineWidth: 2,
                    },
                },
                meta: {
                    time: {
                    alias: '时间',
                    },
                    num: {
                    alias: '注册数',
                    },
                },
            });

            this.plotObj.render();
            this.isPlot = true;
        },
        // 获取列表
        onload(){
            this.get_info();
            
        },
        
    },
    created() {},
    mounted() {
        this.onload();
    }
};
</script>
<style lang="scss" scoped>

</style>