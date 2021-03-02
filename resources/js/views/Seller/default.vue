<template>
    <div class="admin_default">
        <a-row :gutter="{ xs: 24,  md: 24}">
            <a-col :span="8" :xs="{ span: 24 }" :lg="{ span: 8 }">
                <div class="admin_card">
                    <div class="title">总销售额</div>
                    <div class="content">
                        <div class="total_price">￥ {{info.total_price||'0.00'}}</div>
                        <div class="total_rate">
                            <span>周同比 {{info.week_rate||'0.00'}} %<a-icon v-if="info.week_rate>=0" style="margin-left:5px;color:red;" type="caret-up" /><a-icon v-else style="margin-left:5px;color:green;" type="caret-down" /></span>
                            <span>日同比 {{info.day_rate||'0.00'}} %<a-icon v-if="info.day_rate>=0" style="margin-left:5px;color:red;" type="caret-up" /><a-icon v-else style="margin-left:5px;color:green;" type="caret-down" /></span>
                        </div>
                        <div class="unline"></div>
                        <div class="month_rate"><a-tooltip><template slot="title">月订单完成度</template><a-progress :percent="info.month_rate||60.00" /></a-tooltip></div>
                        <div class="today_price">日销售额：￥ {{info.today_price||'0.00'}}</div>
                    </div>
                </div>
            </a-col>
            <a-col :span="8" :xs="{ span: 24 }" :lg="{ span: 8 }">
                <div class="admin_card">
                    <div class="title">订单信息</div>
                    <div class="content">
                        <a-row :gutter="{ xs: 12,  md: 12}">
                            <a-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                <div class="color_block">
                                    <div><a-tag color="blue">等待付款</a-tag></div>
                                    <div class="color_blcok_font">{{info.order_wait||'0'}}</div>
                                </div>
                            </a-col>
                            <a-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                <div class="color_block">
                                    <div><a-tag color="green">完成订单</a-tag></div>
                                    <div class="color_blcok_font">{{info.order_complete||'0'}}</div>
                                </div>
                            </a-col>
                        </a-row>
                        <a-row :gutter="{ xs: 12,  md: 12}">
                            <a-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                <div class="color_block">
                                    <div><a-tag color="red">等待发货</a-tag></div>
                                    <div class="color_blcok_font">{{info.order_send||'0'}}</div>
                                </div>
                            </a-col>
                            <a-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                <div class="color_block">
                                    <div><a-tag color="orange">售后处理</a-tag></div>
                                    <div class="color_blcok_font">{{info.order_refund||'0'}}</div>
                                </div>
                            </a-col>
                        </a-row>
                        <div style="height:20px"></div>
                    </div>
                </div>
            </a-col>
            <a-col :span="8" :xs="{ span: 24 }" :lg="{ span: 8 }">
                <div class="admin_card">
                    <div class="title">版本信息</div>
                    <div class="content" style="margin-top:0;">
                        <div class="copyright">
                            <span class="copyright_title">当前版本：</span>
                            <span class="copyright_rs"><a-tag>2.0.0</a-tag></span>
                        </div>
                        <div class="unline"></div>
                        <div class="copyright">
                            <span class="copyright_title">商城框架：</span>
                            <span class="copyright_rs" @click="openWeb">青梧商城系统（QwSystem）</span>
                        </div>
                        <div class="unline"></div>
                        <div class="copyright" style="padding-bottom:18px">
                            <span class="copyright_title">开源地址：</span>
                            <span class="copyright_rs"><a-button icon="download" type="primary" @click="download">前往下载</a-button></span>
                        </div>
                    </div>
                </div>
            </a-col>
        </a-row>
        <div class="admin_card">
            <div class="title">
                <div class="right_block">
                    <ul>
                        <li :class="params.is_type==0?'ck':''" @click="typeChange(0)">本周</li>
                        <li :class="params.is_type==1?'ck':''" @click="typeChange(1)">本年</li>
                    </ul>
                    <div class="daterange"><a-range-picker v-model="params.created_at" @change="onChange" format="YYYY-MM-DD" /></div>   
                </div>
                销售趋势
            </div>
            <div class="content">
                <a-row :gutter="{ xs: 24,  md: 24}">
                    <a-col :span="16" :xs="{ span: 24 }" :lg="{ span: 16 }">
                        <div id="container" class="default_gd"></div>
                    </a-col>
                    <a-col :span="8" :xs="{ span: 24 }" :lg="{ span: 8 }">
                        <div class="sort_list">
                            <div class="list_title">商品销售额排名</div>
                            <div class="list_block" v-for="v in 6" :key="v"><font style="color:#999;float:right;">{{list[v-1]?list[v-1]['orders_count']:'-'}}</font><span>{{v}}</span><div style="width: 70%;display: inline-block;height: 20px;overflow: hidden;">{{list[v-1]?list[v-1]['goods_name']:'-'}}</div></div>

                        </div>
                    </a-col>
                </a-row>
                
            </div>
        </div>
        <div class="admin_card">
            <div class="title">单量趋势</div>
            <div class="content">
                <div id="user_plot" class="default_gd"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { Column,Line } from 'g2plot';
export default {
    components: {
    },
    props: {},
    data() {
      return {
          params:{
              is_type:0,
              created_at:[],
          },
          info:{},
          list:[],
          isUserPlot:false,
          isOrderPlot:false,
          user_plot:[{time:'2012-12-00',num:9.00},{time:'2012-12-01',num:2.00},{time:'2012-12-02',num:1.00},{time:'2012-12-03',num:5.00},{time:'2012-12-04',num:7.00},{time:'2012-12-05',num:5.00},{time:'2012-12-06',num:1.00}],
          order_plot:[{time:'2012-12-00',num:9.00},{time:'2012-12-01',num:2.00},{time:'2012-12-02',num:1.00},{time:'2012-12-03',num:5.00},{time:'2012-12-04',num:7.00},{time:'2012-12-05',num:5.00},{time:'2012-12-06',num:1.00}],
          userObj:null,
          orderObj:null,
      };
    },
    watch: {},
    computed: {},
    methods: {
        download(){
            window.open("https://gitee.com/qingwuitcn/qwshop")
        },
        openWeb(){
            window.open("https://www.qwststem.com")
        },
        onChange(e){
            // this.params.created_at = e;
            this.params.created_at[0] = moment(e[0]).format('YYYY-MM-DD')
            this.params.created_at[1] = moment(e[1]).format('YYYY-MM-DD')
            console.log(this.params.created_at)
            this.get_info();
        },
        typeChange(e){
            this.params.is_type = e;
            this.get_info();
        },
        get_sale_plot(){
            let data = this.order_plot;
            if(this.isOrderPlot){
                this.orderObj.changeData(data);
                return;
            }
            this.orderObj = new Column('container', {
                data,
                xField: 'time',
                yField: 'num',
                columnWidthRatio: 0.6,
                meta: {
                    time: {
                    alias: '时间',
                    },
                    num: {
                    alias: '销售额',
                    },
                },
            });
            this.orderObj.render();
            this.isOrderPlot = true;
        },
        get_user_plot(){
            let data = this.user_plot;
            if(this.isUserPlot){
                this.userObj.changeData(data);
                return;
            }
            this.userObj = new Line('user_plot', {
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

            this.userObj.render();
            this.isUserPlot = true;
        },
        get_info(){
            this.$get(this.$api.sellerStatistics+'/all',this.params).then(res=>{
                this.info = res.data;
                this.list = res.data.list;
                
                this.user_plot = res.data.user_plot;
                this.order_plot = res.data.order_plot;
                this.get_sale_plot();
                this.get_user_plot();
            })
        },
    },
    created() {
        
    },
    mounted() {
        
        this.get_info();
    },
    
};
</script>
<style lang="scss" scoped>

</style>