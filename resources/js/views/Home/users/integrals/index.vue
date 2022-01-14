<template>
    <div class="user_order">
        <div class="user_main">
            <div class="block_title">
                积分订单
            </div>
            <div class="x20"></div>

            <div class="safe_block" >
              <div class="order_list" v-if="data.list.length>0">
                <div class="order_item" v-for="(v,k) in data.list" :key="k">
                    <div class="order_item_title">
                        <!-- <span>{{v.created_at}}<font :color="v.order_status==6?'#42b983':'#ca151e'">{{v.order_status_cn||'-'}}</font></span> -->
                        订单号：{{v.order_no||'-'}}
                    </div>
                    <div class="order_item_list" >
                        <ul>
                            <li ><router-link to="">
                                <div class="order_thumb"><img :src="v.order_image||require('@/assets/Home/default.png').default" :alt="v.order_name"></div>
                                <div class="order_list_title">{{v.order_name||'-'}}</div>
                                <div class="order_list_attr">{{v.sku_name||'-'}}</div>
                                <div class="order_list_num">x {{v.buy_num||'1'}}</div>
                                <div class="order_list_price">￥{{v.total_price||'0.00'}}</div>
                            </router-link></li>
                        </ul>
                    </div>

                    <div class="order_item_btn" >
                        <div class="default_btn orderbtn"  @click="get_order_info(v.id)">查看物流</div>
                    </div>
                </div>
                <div class="fy" v-if="data.params.total>0">
                    <el-pagination background 
                    layout="total, prev, pager, next" 
                    :page-size="data.params.per_page" 
                    @current-change="handleCurrentChange"
                    :page-count="data.params.last_page"
                    :current-page="data.params.current_page"
                    :total="data.params.total">
                    </el-pagination>
                </div>
            </div>
            <div style="min-height:600px;padding-top:100px" v-else>
                <el-empty />
            </div>
            </div>
        </div>

        <!-- 物流 -->
        <a-modal v-model="visible" title="物流信息" :footer="null">
            <el-timeline v-if="data.order_info.delivery_list.length>0">
                <el-timeline-item  v-for="(v,k) in data.order_info.delivery_list" :key="k" :color="k==0?'red':'gray'">
                <p>{{v.context+' '+v.time}}</p>
                </el-timeline-item>
            </el-timeline>
            <a-empty v-else />
        </a-modal>
    </div>
</template>

<script>
import search from '@/components/common/search'
import {reactive,onMounted,getCurrentInstance} from "vue"
import {useRouter} from 'vue-router'
export default {
    components: {search},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const router = useRouter()
        const data = reactive({
            params:{
                page:1,
                total:0,
                per_page:20,
                last_page:1,
            },
            total:0, //总页数
            list:[],
            searchConfig:[
                {label:'订单号',name:'order_no',type:'text'},
            ],
            order_info:{
                delivery_list:[],
            },
            visible:false,
        })

        const loadData = ()=>{
            proxy.R.get('/integral/orders',data.params).then(res=>{
                if(!res.code){
                    data.list = res.data
                    data.params.total = parseInt(res.total)
                    data.params.per_page = parseInt(res.per_page)
                    data.params.last_page = parseInt(res.last_page)
                    data.params.current_page = parseInt(res.current_page)
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        // 页面切换
        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }

        // 修改订单状态
        const edit_order_status = (id,status=0)=>{
            proxy.R.put('/user/order/'+id,{order_status:status}).then(res=>{
                if(!res.code){
                    proxy.$message.success(proxy.$t('msg.success'))
                    return loadData()
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        const pay_order = (order_id)=>{
            let str = window.btoa(JSON.stringify({order_id:[order_id]})); 
            router.push("/order/pay/"+str);
        }

        onMounted(() => {
            loadData()
        })

        return {
            data,
            handleCurrentChange,edit_order_status,pay_order,
        }
    },
};
</script>
<style lang="scss" scoped>
.order_item_btn{
    text-align: right;
    margin-top: 20px;
    div.orderbtn{
        margin-right: 10px;
        &:last-child{margin-right: 0;}
    }
}
.order_item_title{
    background: #f6f6f6;
    line-height: 40px;
    padding: 0 15px;
    margin: 20px 0;
    span{
        float: right;
        font-size: 12px;
        font{
            margin-left:15px;
        }
    }
}
.order_status_block{
    background: #fff;
    padding: 20px;
    font-size: 14px;
    margin-top: 20px;
    margin-bottom: 20px;
    ul{
        &:after{
            clear:both;
            display: block;
            content:'';
        }
        li{
            position: relative;
            &:first-child i{
                font-weight: bold;
                font-size: 26px;
            }
            &:nth-child(1) i{
                top:-2px;
            }
            &:nth-child(2) i{
                left :36px;
            }
             &:nth-child(3) i{
                 left :40px;
                 top:-2px;
            }
             &:nth-child(4) i{
                top:1px;
                left :40px;
            }
            float: left;
            text-align: center;
            width: 180px;
            i{
                position: absolute;
                font-size: 22px;
                margin-right: 4px;
                left :34px;
            }
        
        }
    }
}
.order_item_list{
    ul li{
        padding:20px 15px;
        border-top: 1px solid #efefef;
        border-left: 1px solid #efefef;
        border-right: 1px solid #efefef;
        &:last-child{
            border-bottom: 1px solid #efefef;
        }
        &:after{
            clear:both;
            display: block;
            content:'';
        }
        .order_thumb{
            width: 42px;
            height: 42px;
            background: #efefef;
            border:1px solid #efefef;
            box-sizing: border-box;
            float: left;
            margin-right: 15px;
            img{
                width: 40px;
                height: 40px;
            }
        }
        .order_list_title{
            width: 280px;
            float: left;
            font-size: 12px;
            line-height: 20px;
            height: 40px;
            overflow: hidden;
        }

        .order_list_attr{
            float: left;
            text-align: center;
            width: 190px;
            line-height: 40px;
        }
        .order_list_num{
            float: left;
            text-align: center;
            width: 140px;
            line-height: 40px;
        }
        .order_list_price{
            float: right;
            color:#ca151e;
            text-align: center;
            width: 90px;
            line-height: 40px;
            // font-weight: bold;
        }
    }
}

</style>