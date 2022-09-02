<template>
    <div class="qwit">

        <div class="search_block">
            <ul>
                <li :class="params.is_type==0?'ck':''" @click="typeChange(0)">本周</li>
                <li :class="params.is_type==1?'ck':''" @click="typeChange(1)">本年</li>
            </ul>
            <div class="daterange"><el-date-picker type="daterange" v-model="params.created_at" @change="onChange" format="YYYY-MM-DD" /></div>   
        </div>
        
        <div :id="'order_plot'+data.random" style="margin-top:20px;margin-bottom:40px"></div>

        <div class="admin_form plot" >
            <div class="sort_list">
                <div class="list_title">门店销售额排名 TOP 10</div>
                <div class="list_block" v-for="v in 10" :key="v"><span>{{v}}</span><em class="goods_name">{{data.store[v-1]?data.store[v-1]['store_name']:'-'}}</em><em class="num">{{data.store[v-1]?data.store[v-1]['orders_count']:'-'}}</em></div>
            </div>

            <div class="sort_list">
                <div class="list_title">商品销售额排名 TOP 10</div>
                <div class="list_block" v-for="v in 10" :key="v"><span>{{v}}</span><em class="goods_name">{{data.goods[v-1]?data.goods[v-1]['goods_name']:'-'}}</em><em class="num">{{data.goods[v-1]?data.goods[v-1]['orders_count']:'-'}}</em></div>
            </div>
        </div>
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import { Line } from 'g2plot'
export default {
    setup(props) {
        const {proxy} = getCurrentInstance()
        // 配置字段
        const params = reactive({
            is_type:0,
        });
        const data = reactive({
            // list:[],
            plot:[],
            store:[],
            goods:[],
            line:undefined,
            random:(Math.random()+'').substr(0,5)
        })

        const loadData = ()=>{
            proxy.R.get('/Admin/dashboard/order',params).then(res=>{
                if(!res.code){
                    // data.list = res.list
                    data.store = res.store
                    data.goods = res.goods
                    data.plot = res.plot
                    data.plot.forEach(item=>item.num = parseFloat(item.num))
                    data.line.changeData(data.plot)
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
            data.line = new Line('order_plot'+data.random,{
                data:data.plot,
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
            data,params,
            typeChange,onChange
        }
    }    
}
</script>

<style lang="scss" scoped>
.admin_form  .sort_list .list_block span{
    width: 20px;
    flex:0 0 20px;
    height: 20px;
    background-color: #f5f5f5;
    border-radius: 50%;
    font-size: 14px;
    display: inline-block;
    text-align: center;
    line-height: 20px;
    margin-right: 15px;
}
.plot{
    margin-bottom: 30px;
    display: flex;
    .sort_list{
        flex: 1;
        margin-right: 30px;
        &:last-child{margin-right: 0;}
        .list_block{
            
        }
    }
    .goods_name{
        flex:0 0 60%;
        display: inline-block;
        height: 22px;
        overflow: hidden;
        // white-space:nowrap;text-overflow:ellipsis;
    }
    .num{
        color:#999;float:right;text-align:right;
        flex: 0 0 140px;
    }
}
.admin_form .sort_list .list_title{margin-bottom:10px;margin-top: 10px;font-weight: bold;}
.admin_form .sort_list .list_block{padding:10px 10px;overflow: hidden;display: flex;}
.admin_form .sort_list .list_block:nth-child(1) span{background: #333;color:#fff;}
.admin_form .sort_list .list_block:nth-child(2) span{background: #333;color:#fff;}
.admin_form .sort_list .list_block:nth-child(3) span{background: #333;color:#fff;}
.admin_form .sort_list .list_block:nth-child(4) span{background: #333;color:#fff;}
.search_block{
    margin-bottom: 30px;
    ul{
       display: inline-block; 
    }
    ul li{
        display: inline-block;
        font-size: 14px;
        margin-right: 10px;
        padding:5px 15px;
        border-radius: 3px;
        background: #f1f1f1;
        cursor: pointer;
        &.ck{
            background: #1890ff;
            color:#fff;
        }
    }
    .daterange{
        display: inline-block;
    }
}

</style>