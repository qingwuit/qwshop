<template>
    <div class="user_order">
        <div class="user_main">
            <div class="block_title">
                <div class="user_order_search">
                    <ul>
                        <li style="padding:0;">
                            <el-select style="width:100px" v-model="data.params.order_status">
                                <el-option v-for="(v,k) in data.searchConfig" :key="k" :label="v.label" :value="v.value"></el-option>
                            </el-select>
                        </li>
                        <li style="padding:0;">
                            <el-input v-model="data.params.order_no" placeholder="订单号" />
                        </li>
                        <li style="padding:0;" >
                            <el-date-picker
                                style="width:250px" 
                                v-model="data.params.created_at"
                                type="daterange"
                                :start-placeholder="$t('home.order.startTime')"
                                :end-placeholder="$t('home.order.endTime')"
                                value-format="YYYY-MM-DD"
                            />
                        </li>
                        <li :class="data.params.is_type==0?'ck':''" @click="typeChange(0)">本周</li>
                        <li :class="data.params.is_type==1?'ck':''" @click="typeChange(1)">本月</li>
                        <li :class="'ck'" @click="loadData"> <i class="fa fa-search" /> </li>
                        
                    </ul>
                </div>
                <em style="line-height:27px">我的订单</em>
            </div>
            <div class="x20"></div>

            <div class="safe_block" >
              <div class="order_list" v-if="data.list.length>0">
                <div class="order_item" v-for="(v,k) in data.list" :key="k">
                    <div class="order_item_title">
                        <span>{{v.created_at}}<font :color="v.order_status==6?'#42b983':'#ca151e'">{{v.order_status_cn||'-'}}</font></span>
                        订单号：{{v.order_no||'-'}}
                    </div>
                    <div class="order_item_list"  @click="$router.push('/user/order/'+v.id)">
                        <ul>
                            <li v-for="(vo,key) in v.order_goods" :key="key"><router-link to="">
                                <div class="order_thumb"><img :src="vo.goods_image||require('@/assets/Home/default.png').default" :alt="vo.goods_name"></div>
                                <div class="order_list_title">{{vo.goods_name||'-'}}</div>
                                <div class="order_list_attr">{{vo.sku_name||'-'}}</div>
                                <div class="order_list_num">x {{vo.buy_num||'1'}}</div>
                                <div class="order_list_price">￥{{vo.total_price||'0.00'}}</div>
                            </router-link></li>
                        </ul>
                    </div>

                    <div class="order_item_btn" v-show="v.order_status!=6 || v.order_status !=0">
                        <div class="default_btn orderbtn" v-if="v.order_status==1" @click="edit_order_status(v.id)">取消订单</div>
                        <div class="success_btn orderbtn" v-if="v.order_status==1" @click="pay_order(v.id)">立即支付</div>
                        <div class="default_btn orderbtn" v-if="v.order_status>2 && v.order_status != 5 && v.refund_status != 3 " @click="$router.push('/user/order/'+v.id)">查看物流</div>
                        <div class="error_btn orderbtn" v-if="v.order_status==3" @click="edit_order_status(v.id,4)">确定收货</div>
                        <div class="gray_btn orderbtn" v-if="v.order_status==4" @click="$router.push('/user/comment/add/'+v.id)">前往评论</div>
                        <div class="warn_btn orderbtn" v-if="v.order_status>=2 && v.order_status !=5 && v.refund_status!=2" @click="$router.push('/user/order/refund/'+v.id)">申请售后</div>
                        <div class="warn_btn orderbtn" v-if="v.order_status==5 || v.refund_status==2" @click="$router.push('/user/order/refund_form/'+v.id)">查看售后</div>
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
import {Search} from '@element-plus/icons'
import {useRouter,useRoute} from 'vue-router'
import dayjs from 'dayjs'
export default {
    components: {Search,search},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const router = useRouter()
        const route = useRoute()
        const data = reactive({
            params:{
                page:1,
                total:0,
                per_page:20,
                last_page:1,
                order_status:-1,
            },
            total:0, //总页数
            list:[],
            searchConfig:[
                {label:'全部订单',value:-1},
                {label:'订单取消',value:0},
                {label:'等待支付',value:1},
                {label:'等待发货',value:2},
                {label:'确认收货',value:3},
                {label:'等待评论',value:4},
                {label:'售后订单',value:5},
                {label:'订单完成',value:6},
            ],
            order_info:{
                delivery_list:[],
            },
            visible:false,
        })

        const loadData = ()=>{
            proxy.R.get('/user/orders?isResource=Home',data.params).then(res=>{
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

        // 搜索条件
        const typeChange = (e)=>{
            if(e==0) data.params.created_at = [dayjs().day(0).format('YYYY-MM-DD'),dayjs().day(6).format('YYYY-MM-DD')]
            if(e==1) data.params.created_at = [dayjs().date(0).format('YYYY-MM-DD'),dayjs().date(31).format('YYYY-MM-DD')]
            data.params.is_type = e
        }
        
        onMounted(() => {
            if(route.params.status) data.params.order_status = parseInt(route.params.status)
            loadData()
            // data.params.created_at = [dayjs().format('YYYY-MM-DD'),dayjs().add(1, 'day').format('YYYY-MM-DD')]
        })

        return {
            data,
            handleCurrentChange,edit_order_status,pay_order,typeChange,loadData,
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
.user_order_search{
    float: right;
    display: block;
    ul{
        li{
            float: left;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            padding:6px 15px;
            border-radius: 3px;
            background: #f1f1f1;
            &:hover{
                background:var(--el-color-primary);
                color:#fff;
            }
            &.ck{
                background:var(--el-color-primary);
                color:#fff;
            }
        }
    }
    .el-range-editor.el-input__inner{
        width: 250px;
    }
}
</style>
<style lang="scss">
.user_order_search{
    .el-range-editor.el-input__inner{
        width: 250px;
    }
}
</style>