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
        <table-view 
        :options="options" 
        @searchChange="searchChange"
        @tableHandleSizeChange="handleSizeChange" 
        @tableHandleCurrentChange="handleCurrentChange" 
        :params="params" 
        :searchOption="searchOptions" 
        :btnConfig="btnConfigs" 
        :tableData="data.list" 
        :pagination="data.list.data && data.list.data.length>0" 
        :handleHide="false" ></table-view>
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
import { Line } from 'g2plot'
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        // 配置字段
        const params = reactive({
            is_type:0,
        });
        const data = reactive({
            // list:[],
            plot:[],
            list:{
                data:[]
            },
            line:undefined,
            random:(Math.random()+'').substr(0,5)
        })

        const loadData = async ()=>{
            proxy.R.get('/Admin/dashboard/user',params).then(res=>{
                if(!res.code){
                    data.list = res.list
                    data.plot = res.plot
                    data.plot.forEach(item=>item.num = parseFloat(item.num))
                    data.line.changeData(data.plot)
                }
            })
        }

        const handleSizeChange = (e)=>{
            params.page = e.page
            params.per_page = e.per_page
            loadData()
        }

        const handleCurrentChange = (e)=>{
            params.page = e.page
            params.per_page = e.per_page
            loadData()
        }

        const typeChange = (e)=>{
            params.is_type = e;
            params.created_at = null
            loadData()
        }

        // 搜索回调
        const searchChange = (e)=>{
            delete e.is_type
            Object.assign(params,e)
            if(!e.nickname) delete params.nickname
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
                        alias: '数量',
                    },
                },
            })
            data.line.render()
            loadData()
        })

        const options = reactive([
            {label:'头像',value:'avatar',type:'avatar',perView:true},
            {label:'昵称',value:'nickname'},
            {label:'用户名',value:'username',type:'tags'},
            {label:'手机',value:'phone',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'昵称',value:'nickname',where:'likeRight'},
        ])
        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            destroy:{show:false},
            show:{show:false},
            export:{show:false},
        })

        return {
            data,params,options,btnConfigs,searchOptions,searchChange,
            typeChange,onChange,handleSizeChange,handleCurrentChange
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