<template>
    <base-view :hideMian="true">
        <template  class="dashboard" #main_view_sub>
            <div class="top_div_block">
                <div class="top_div_item">
                    <div class="admin_card" >
                        <div class="title">总销售额</div>
                        <div class="content">
                            <div class="total_price">{{$t('btn.money')}} {{data.info.total_price||'0.00'}}</div>
                            <div class="total_rate">
                                <span>周同比 {{data.info.week_rate||'0.00'}} %<el-icon v-if="data.info.week_rate>=0" style="margin-left:5px;color:red;" ><CaretTop /></el-icon><el-icon v-else style="margin-left:5px;color:green;"  ><CaretBottom /></el-icon></span>
                                <span>日同比 {{data.info.day_rate||'0.00'}} %<el-icon v-if="data.info.day_rate>=0" style="margin-left:5px;color:red;" ><CaretTop /></el-icon><el-icon v-else style="margin-left:5px;color:green;"  ><CaretBottom /></el-icon></span>
                            </div>
                            <div class="unline" style="padding-top:15px"></div>
                            <div class="month_rate"><el-progress :percentage="data.info?.month_rate||60.00" color="#67c23a" /></div>
                            <div class="today_price">日销售额：{{$t('btn.money')}} {{data.info.today_price||'0.00'}}</div>
                        </div>
                    </div>
                </div>
                <div class="top_div_item">
                    <div class="admin_card" >
                        <div class="title">订单信息</div>
                        <div class="content">
                            <el-row :gutter="12">
                                <el-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                    <div class="color_block">
                                        <div><el-tag>等待付款</el-tag></div>
                                        <div class="color_blcok_font">{{data.info.order_wait||'0'}}</div>
                                    </div>
                                </el-col>
                                <el-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                    <div class="color_block">
                                        <div><el-tag type="success">完成订单</el-tag></div>
                                        <div class="color_blcok_font">{{data.info.order_complete||'0'}}</div>
                                    </div>
                                </el-col>
                            </el-row>
                            <el-row :gutter="12">
                                <el-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                    <div class="color_block">
                                        <div><el-tag type="danger">等待发货</el-tag></div>
                                        <div class="color_blcok_font">{{data.info.order_send||'0'}}</div>
                                    </div>
                                </el-col>
                                <el-col :span="12" :xs="{ span: 24 }" :lg="{ span: 12 }">
                                    <div class="color_block">
                                        <div><el-tag type="warning">售后处理</el-tag></div>
                                        <div class="color_blcok_font">{{data.info.order_refund||'0'}}</div>
                                    </div>
                                </el-col>
                            </el-row>
                            <div style="height:20px"></div>
                        </div>
                    </div>
                </div>
                <div class="top_div_item">
                    <div class="admin_card" >
                        <div class="title">版本信息</div>
                        <div class="content" style="margin-top:0;">
                            <div class="copyright">
                                <span class="copyright_title">当前版本：</span>
                                <span class="copyright_rs"><el-tag type="info">3.0.0</el-tag></span>
                            </div>
                            <div class="unline"></div>
                            <div class="copyright">
                                <span class="copyright_title">商城框架：</span>
                                <span class="copyright_rs" @click="openWeb">青梧商城系统（QwSystem）</span>
                            </div>
                            <div class="unline"></div>
                            <div class="copyright" style="padding-bottom:18px">
                                <span class="copyright_title">开源地址：</span>
                                <span class="copyright_rs"><el-button :icon="Download" type="primary" @click="download">前往下载</el-button></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin_card" >
                <div class="title">
                    <div class="right_block">
                        <ul>
                            <li :class="params.is_type==0?'ck':''" @click="typeChange(0)">本周</li>
                            <li :class="params.is_type==1?'ck':''" @click="typeChange(1)">本年</li>
                        </ul>
                        <div class="daterange"><el-date-picker type="daterange" v-model="params.created_at" @change="onChange" format="YYYY-MM-DD" /></div>   
                    </div>
                    销售趋势
                </div>
                <div class="content">
                    <el-row :gutter="22">
                        <el-col :span="16" >
                            <div :id="'container'+data.random" class="default_gd"></div>
                        </el-col>
                        <el-col :span="8">
                            <div class="sort_list">
                                <div class="list_title">商品销售额排名</div>
                                <div class="list_block" v-for="v in 6" :key="v"><em style="color:#999;float:right;">{{data.list[v-1]?data.list[v-1]['orders_count']:'-'}}</em><span>{{v}}</span><div style="width: 70%;display: inline-block;height: 20px;overflow: hidden;font-size:14px;line-height: 28px;">{{data.list[v-1]?data.list[v-1]['goods_name']:'-'}}</div></div>

                            </div>
                        </el-col>
                    </el-row>
                    
                </div>
            </div>
            <div class="admin_card" >
                <div class="title">单量趋势</div>
                <div class="content">
                    <div :id="'user_plot'+data.random" class="default_gd"></div>
                </div>
            </div>
        </template>
    </base-view>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import {CaretBottom,CaretTop,Download} from '@element-plus/icons'
import baseView from "@/components/common/base"
import dayjs from "dayjs"
import { Column,Line } from 'g2plot'
export default {
    components:{baseView,CaretBottom,Download,CaretTop},
    setup(props) {
        const {proxy} = getCurrentInstance()
        // 配置字段
        const params = reactive({
            is_type:0
        });
        const data = reactive({
            info:{},
            list:[],
            userPlot:[],
            orderPlot:[],
            column:undefined,
            line:undefined,
            random:(Math.random()+'').substr(0,5)
        })



        const loadData = ()=>{
            proxy.R.get('/Seller/dashboard/all',params).then(res=>{
                if(!res.code){
                    data.info = res
                    data.list = res.list
                    data.orderPlot = res.order_plot
                    data.userPlot = res.user_plot
                    data.orderPlot.forEach(item=>item.num = parseFloat(item.num))
                    // data.userPlot.forEach(item=>item.num = parseFloat(item.num))
                    data.column.changeData(data.orderPlot)
                    data.line.changeData(data.userPlot)
                }
            })
            
        }

        const typeChange = (e)=>{
            params.is_type = e;
            params.created_at = null
            loadData()
        }

        const onChange = (e)=>{
            if(!e){
                params.created_at = undefined
                return loadData()
            }
            params.created_at[0] = dayjs(e[0]).format('YYYY-MM-DD')
            params.created_at[1] = dayjs(e[1]).format('YYYY-MM-DD')
            loadData()
        }

        onMounted(() => {
            // 柱子
            data.column = new Column('container'+data.random,{
                data:data.orderPlot,
                xField: 'time',
                yField: 'num',
                meta: {
                    time: {
                        alias: '时间',
                    },
                    num: {
                        alias: '销售额',
                    },
                },
                columnBackground: {
                    style: {
                        fill: 'rgba(0,0,0,0.1)',
                    },
                },
            })
            data.column.render()

            data.line = new Line('user_plot'+data.random,{
                data:data.userPlot,
                xField: 'time',
                yField: 'num',
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
                        range: [0, 1]
                    },
                    num: {
                        alias: '订单数',
                    },
                },
            })
            data.line.render()
            loadData()
        })
        
        return {
            Download,data,params,
            typeChange,onChange
        }
    }
}
</script>

<style lang="scss" scoped>
.admin_card{
    background: #fff;
    margin-bottom: 30px;
    border: 1px solid #EBEEF5;
    transition: .3s;
    padding: 0px 20px;
    border-radius: 3px;
    font-size: 16px;
}
.admin_card:hover{
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.admin_card .title{
    border-bottom: 1px solid #efefef;
    line-height: 30px;
    padding:15px 0;
}
.admin_card .content{
    margin-top: 12px;
}
.admin_card .content .total_price{
    font-size: 30px;
}
.admin_card .content .total_rate{
    font-size: 16px;
    margin-top: 15px;
    margin-bottom: 10px;
    display: flex;
}
.admin_card .content .total_rate span{
    flex: 1;
}
.admin_card .content  .today_price{
    margin-top: 10px;
    padding-bottom: 25px;
}
.admin_card .content .color_block{
    padding:10px 15px;
    background: #f9f9f9;
    margin-bottom: 10px;
    cursor: pointer;
}
.admin_card .content .color_blcok_font{
    font-size: 20px;
    margin-top: 10px;
    padding-left: 5px;
    line-height: 20px;
}

.admin_card .sort_list .list_block span,.admin_form  .sort_list .list_block span{
    width: 20px;
    height: 20px;
    background-color: #f5f5f5;
    border-radius: 50%;
    font-size: 14px;
    display: inline-block;
    text-align: center;
    line-height: 20px;
    margin-right: 15px;
}
.admin_card .sort_list .list_title,.admin_form .sort_list .list_title{margin-bottom:10px;margin-top: 10px;font-weight: bold;}
.admin_card .sort_list .list_block,.admin_form .sort_list .list_block{padding:10px 10px;overflow: hidden;}
.admin_card .sort_list .list_block:nth-child(1) span,.admin_form .sort_list .list_block:nth-child(1) span{background: #333;color:#fff;}
.admin_card .sort_list .list_block:nth-child(2) span,.admin_form .sort_list .list_block:nth-child(2) span{background: #333;color:#fff;}
.admin_card .sort_list .list_block:nth-child(3) span,.admin_form .sort_list .list_block:nth-child(3) span{background: #333;color:#fff;}
.admin_card .sort_list .list_block:nth-child(4) span,.admin_form .sort_list .list_block:nth-child(4) span{background: #333;color:#fff;}
.admin_card .default_gd{height: 320px;margin-top: 20px;margin-bottom: 20px;}
.admin_card .content .color_block:hover{
    background: #f2f2f2;
}
.admin_card .month_rate{
    margin-top: 15px;
}
.admin_card .copyright{
    line-height: 60px;
    display: flex;
}
.admin_card .copyright .copyright_title{
    margin-right: 20px;
    height: 60px;
    overflow: hidden;
}
.admin_card .copyright .copyright_rs{
    height: 60px;
    overflow: hidden;
    cursor: pointer;
}
.admin_card .title .right_block{
    float: right;
}
.admin_card .title .right_block ul li{
    float: left;
    font-size: 14px;
    margin-right: 10px;
    padding:2px 15px;
    border-radius: 3px;
    background: #f1f1f1;
}
.admin_card .title .right_block ul{
    float: left;
}
.admin_card .title .right_block .daterange{
    float: left;
}
.admin_card .title .right_block ul li.ck{
    background: #1890ff;
    color:#fff;
}
.top_div_block{
    display: flex;
    width: 100%;
    .top_div_item{
        flex: 1;
        margin-right: 15px;
        &:last-child{
            margin-right: 0;
        }
    }
    .unline{
        border-bottom: 1px solid #EBEEF5;
    }
}
</style>