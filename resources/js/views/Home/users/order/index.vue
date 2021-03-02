<template>
    <div class="user_order">
        <div class="user_main">
            <div class="block_title">
                我的订单
            </div>
            <div class="x20"></div>

            <div class="home_search_block">
                <a-form layout="inline">
                    <!-- <a-form-item label="起始时间"><a-range-picker  v-model="params['created_at']" format="YYYY-MM-DD HH:mm:ss" show-time :allow-clear="false" /></a-form-item><br> -->
                    <a-form-item label="订单号码">
                        <a-input v-model="params['order_no']" :placeholder="'2020091418433488438'"/>
                    </a-form-item>
                    <a-form-item label="订单状态">
                        <a-select  v-model="params['order_status']" style="width:160px" >
                            <a-select-option v-for="(vo,key) in searchConfig[1].data" :key="key" :value="vo.value">{{vo.label}}</a-select-option>
                        </a-select>
                    </a-form-item>
                    <span class="default_btn" style="margin-top:5px;display:inline-block;padding:4px 15px;" @click="search()"><a-icon type="search" />&nbsp;查询</span>
                </a-form>
            </div>

            <div class="safe_block" >
              <div class="order_list" v-if="list.length>0">
                <div class="order_item" v-for="(v,k) in list" :key="k">
                    <div class="order_item_title">
                        <span>{{v.created_at}}<font :color="v.order_status==6?'#42b983':'#ca151e'">{{v.order_status_cn||'-'}}</font></span>
                        订单号：{{v.order_no||'-'}}
                    </div>
                    <div class="order_item_list"  @click="$router.push('/user/order/'+v.id)">
                        <ul>
                            <li v-for="(vo,key) in v.order_goods" :key="key"><router-link to="">
                                <div class="order_thumb"><img :src="vo.goods_image||require('@/asset/order/default.png')" :alt="vo.goods_name"></div>
                                <div class="order_list_title">{{vo.goods_name||'-'}}</div>
                                <div class="order_list_attr">{{vo.sku_name||'-'}}</div>
                                <div class="order_list_num">x {{vo.buy_num||'1'}}</div>
                                <div class="order_list_price">￥{{vo.total_price||'0.00'}}</div>
                            </router-link></li>
                        </ul>
                    </div>

                    <div class="order_item_btn" v-show="v.order_status!=6 || v.order_status !=0">
                        <div class="default_btn" v-if="v.order_status==1" @click="edit_order_status(v.id)">取消订单</div>
                        <div class="success_btn" v-if="v.order_status==1" @click="pay_order(v.id)">立即支付</div>
                        <div class="default_btn" v-if="v.order_status>2 " @click="get_order_info(v.id)">查看物流</div>
                        <div class="error_btn" v-if="v.order_status==3" @click="edit_order_status(v.id,4)">确定收货</div>
                        <div class="gray_btn" v-if="v.order_status==4" @click="$router.push('/user/comment/add/'+v.id)">前往评论</div>
                        <div class="warn_btn" v-if="v.order_status>3 && v.order_status !=5 && v.refund_status!=2" @click="$router.push('/user/refund/'+v.id)">申请售后</div>
                        <div class="warn_btn" v-if="v.order_status==5 || v.refund_status==2" @click="$router.push('/user/refund/form/'+v.id)">查看售后</div>
                    </div>
                </div>
                <div class="fy" v-if="total>0">
                    <a-pagination v-model="params.page" :page-size.sync="params.per_page" :total="total" @change="onChange" show-less-items />
                </div>
            </div>
            <div style="min-height:600px;padding-top:100px" v-else>
                <a-empty />
            </div>
            </div>
        </div>

        <!-- 物流 -->
        <a-modal v-model="visible" title="物流信息" :footer="null">
            <a-timeline v-if="order_info.delivery_list.length>0">
                <a-timeline-item  v-for="(v,k) in order_info.delivery_list" :key="k" :color="k==0?'red':'gray'">
                <p>{{v.context+' '+v.time}}</p>
                </a-timeline-item>
            </a-timeline>
            <a-empty v-else />
        </a-modal>
    </div>
</template>

<script>
import adminSearch from '@/components/admin/search'
export default {
    components: {adminSearch},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:20,
              order_status:'',
          },
          total:0, //总页数
          list:[],
          searchConfig:[
              {label:'订单号',name:'order_no',type:'text'},
              {label:'订单状态',name:'order_status',type:'select',data:[
                  {label:'全部订单',value:''},
                  {label:'订单取消',value:0},
                  {label:'等待支付',value:1},
                  {label:'等待发货',value:2},
                  {label:'确认收货',value:3},
                  {label:'等待评论',value:4},
                  {label:'售后订单',value:5},
                  {label:'订单完成',value:6},
              ]},
          ],
          order_info:{
              delivery_list:[],
          },
          visible:false,
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
        onload(){
            console.log(this.$route)
            if(!this.$isEmpty(this.$route.params.status)){
                this.params.order_status = this.$route.params.status;
            }
            this.$get(this.$api.homeOrder,this.params).then(res=>{
                this.list = res.data.data;
                this.total = res.data.total;
            })
        },
        get_order_info(id){
            this.$get(this.$api.homeOrder+'/get_order_info/'+id).then(res=>{
                this.visible = true;
                this.order_info = res.data;
            })
        },
        pay_order(order_id){
            let str = window.btoa(JSON.stringify({order_id:[order_id]})); 
            this.$router.push("/order/order_pay/"+str);
        },
        edit_order_status(id,order_status=0){
            this.$put(this.$api.homeOrder+'/'+'edit_order_status',{id:id,order_status:order_status}).then(res=>{
                this.onload();
                return this.$returnInfo(res)
            })
        },
    },
    created() {
        this.onload()
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.order_item_btn{
    text-align: right;
    margin-top: 20px;
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