<template>
    <div class="qingwu">
        <el-row :gutter="20">
            <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    总销售额
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                    <div class="default_total">
                        <font style="font-size:30px;">￥ {{info.total_price||'0.00'}}</font>
                        <div class="default_tongbi">
                            <div class="default_tongbi_left">
                                周同比：10.25%
                                <i class="el-icon-caret-top" style="color:red"></i>
                            </div>
                            <div class="default_tongbi_right">
                                日同比：10.25%
                                <i class="el-icon-caret-bottom" style="color:green"></i>
                            </div>
                        </div>
                        <div class="unline"></div>
                        <el-progress :percentage="90"></el-progress>
                        <div class="default_day_sale">日销售额：￥ {{info.today_total_price||'0.00'}}</div>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    订单信息
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                    <div class="default_program2">
                        <ul>
                            <li>
                                <div class="default_sq">
                                    <el-tag>等待付款</el-tag>
                                </div>
                                <p>{{info.pay_order||'0'}}</p>
                            </li>
                            <li>
                                <div class="default_sq">
                                    <el-tag type="success">完成订单</el-tag>
                                </div>
                                <p>{{info.complete_order||'0'}}</p>
                            </li>
                            <li>
                                <div class="default_sq">
                                    <el-tag type="danger">等待发货</el-tag>
                                </div>
                                <p>{{info.wait_order||'0'}}</p>
                            </li>
                            <!-- <li><div class="default_sq"></div><p></p></li> -->
                        </ul>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    版本信息
                    <div class="unline" style="margin-bottom: 0px;"></div>
                    <div class="default_copyright">
                        <ul>
                            <li>
                                <span>当前版本：</span>
                                <el-tag type="info">v 1.0.0</el-tag>
                            </li>
                            <li>
                                <span>商城框架：</span> 青梧商城系统（<a target="_blank" href="https://www.QingWuIt.com">QingWuIt</a>）
                            </li>
                            <li>
                                <span>下载Apk：</span>
                                <el-button type="primary" size="mini" icon="el-icon-download">点击下载</el-button>
                            </li>
                        </ul>
                    </div>
                </el-card>
            </el-col>
        </el-row>

        <div class="default_tubiao">
            <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                总销售额
                <i
                    style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                    class="el-icon-refresh"
                ></i>
                <div class="unline"></div>
                <div class="default_total">
                    <div id="myChart2" :style="{width:'60%',height:'250px',float:'left'}"></div>
                    <div class="default_hot_goods" style="float: left;margin-left:3%;width:37%;">
                        <div>门店销售额排名</div>
                        <ul>
                            <li v-for="(v,k) in info.store" :key="k">
                                <div style="color:#999;float:right;">{{v.sum_total}}</div>
                                <span>{{k+1}}</span>
                                <div
                                    style="width:70%;overflow:hidden;height:20px;"
                                >{{v.store_name}}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </el-card>
        </div>

        <el-row :gutter="20">
            <el-col :span="24" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    入驻会员
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                    <!-- 图表 -->
                    <div id="myChart" :style="{width:'100%',height:'250px'}"></div>
                </el-card>
            </el-col>

            <!-- <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    授权信息
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                </el-card>
            </el-col> -->
        </el-row>
    </div>
</template>

<script>
import echarts from "echarts";
export default {
    components: {},
    props: {},
    data() {
        return {
            info:{},
            week:[0,0,0,0,0,0,0],
            week2:[0,0,0,0,0,0,0],
            month:[],
        };
    },
    watch: {},
    methods: {
        get_info:function(){
            this.$get(this.$api.adminGetStatistics).then(res=>{
                this.info = res.data;
                this.week = [];
                this.week2 = [];
                this.month = [];
                res.data.week.forEach(res=>{
                    this.week.push(res.users);
                });
                res.data.week2.forEach(res=>{
                    this.week2.push(res.users);
                });
                res.data.month.forEach(res=>{
                    this.month.push(res.price);
                });
                this.echartInit();
            });
        },
        echartInit:function(){
            /*ECharts图表*/
            var myChart = echarts.init(document.getElementById("myChart"));
            myChart.setOption({
                title: { text: "会员趋势" },
                tooltip: { trigger: "axis" },
                
                color:["#E6A23C","#000"],
                grid: { left: "3%", right: "4%", bottom: "8%", containLabel: true },
                toolbox: { feature: { saveAsImage: {} } },
                xAxis: {
                    type: "category",
                    boundaryGap: false,
                    data: ["周一", "周二", "周三","周四","周五","周六","周日",]
                },
                yAxis: { type: "value" },
                
                series: [
                    {
                        name: "现周",
                        type: "line",
                        stack: "总量2",
                        data:this.week,
                    },
                    {
                        name: "上周",
                        type: "line",
                        stack: "总量",
                        data: this.week2,
                    }
                ]
            });

            /*ECharts图表*/
            var myChart2 = echarts.init(document.getElementById("myChart2"));
            myChart2.setOption({
                color: "#409EFF",
                title: { text: "销售趋势" },
                legend: {
                    data: ["销量"]
                },
                tooltip: { trigger: "axis" },
                grid: { left: "0%", right: "0%", bottom: "0%", containLabel: true },
                toolbox: { feature: { saveAsImage: {} } },
                xAxis: {
                    data: [
                        "01",
                        "02",
                        "03",
                        "04",
                        "05",
                        "06",
                        "07",
                        "08",
                        "09",
                        "10",
                        "11",
                        "12"
                    ]
                },
                yAxis: { type: "value" },
                series: [
                    {
                        name: "销量",
                        type: "bar",
                        stack: "总量2",
                        data: this.month
                    }
                ]
            });
        }
    },
    created() {
        
    },
    mounted(){
        this.get_info();

        
    }
};
</script>
<style lang="scss" scoped>
.unline {
    margin: 15px 0;
}
.default_program {
    text-align: center;
    width: 100%;
}
.default_program ul li {
    float: left;
    width: 22%;
    background: #f9f9f9;
    margin-right: 4%;
    margin-bottom: 10px;
}
.default_program ul li:nth-child(4n) {
    margin-right: 0;
}
.default_program ul li:hover {
    background: #f1f1f1;
}
.default_program2 ul li {
    float: left;
    width: 48%;
    background: #f9f9f9;
    margin-right: 4%;
    margin-bottom: 18px;
    height: 82px;
    padding: 10px;
    box-sizing: border-box;
    font-size: 12px;
    color: #999;
}
.default_program2 ul li:nth-child(2n) {
    margin-right: 0;
}
.default_program2 ul li:nth-child(3) {
    width: 100%;
}
.default_program2 ul li:hover {
    background: #f3f3f3;
}
.default_program .i_backgraounds {
    text-align: center;
    margin: 0 auto;
    display: block;
    padding: 5px 0;
}
.default_program2 p {
    line-height: 55px;
    font-size: 22px;
    color: #303133;
}
.i_backgraounds i {
    font-size: 28px;
}
.default_program p {
    text-align: center;
    background: #fff;
    line-height: 30px;
    font-size: 12px;
    clear: both;
}
.default_block_col {
    margin-bottom: 20px;
}
.default_copyright ul li {
    line-height: 66px;
    border-bottom: 1px solid #efefef;
}
.default_copyright ul li:nth-child(3) {
    border-bottom: none;
}
.default_copyright ul li span {
    margin-right: 60px;
}
.default_tongbi_left {
    float: left;
}
.default_tongbi_right {
    float: left;
    margin-left: 40px;
}
.default_tongbi:after {
    clear: both;
    content: "";
    display: block;
}
.default_total:after {
    clear: both;
    content: "";
    display: block;
}
.default_tongbi {
    margin-top: 20px;
}
.default_day_sale {
    margin-top: 15px;
    margin-bottom: 21px;
}
.default_tubiao {
    width: 100%;
    margin-bottom: 20px;
}
.default_hot_goods ul li {
    margin-top: 20px;
    overflow: hidden;
}
.default_hot_goods ul li span {
    border-radius: 50%;
    background: #f5f5f5;
    width: 20px;
    height: 20px;
    text-align: center;
    display: block;
    float: left;
    margin-right: 8px;
}
.default_hot_goods ul li:nth-child(1) span {
    background: #314659;
    color: #fff;
}
.default_hot_goods ul li:nth-child(2) span {
    background: #314659;
    color: #fff;
}
.default_hot_goods ul li:nth-child(3) span {
    background: #314659;
    color: #fff;
}
</style>