<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><el-icon><Shop /></el-icon>  我的购物车</div>
                <div class="item"><el-icon><MessageBox /></el-icon>物流地址</div>
                <div class="item"><el-icon><Money /></el-icon>选择支付</div>
                <div class="item"><el-icon><CircleCheckFilled /></el-icon>支付成功</div>
            </div>
        </div>



        <div class="cart_th" v-if="data.params.total>0">
            <el-checkbox :indeterminate="data.indeterminate" :model-value="data.checkAll" @change="onCheckAllChange">全选</el-checkbox>
            <span class="goods">商品</span>
            <span class="attr">规格</span>
            <span class="price">单价（元）</span>
            <span class="num">数量</span>
            <span class="total">小计</span>
            <span class="handle">操作</span>
        </div>

        <div class="cart_table" v-if="data.params.total>0">
            <div class="store_list" v-for="(v,k) in data.list" :key="k">
                <div class="store_title"><el-checkbox :indeterminate="v.css" :model-value='v.checked' @change="onCheckAllStoreChange(k)">{{v.store_name}}</el-checkbox><span class="open_store" @click="$router.push('/store/'+v.store_id)">进入店铺</span></div>
                <div class="goods_list">
                    <ul>
                        <li v-for="(vo,key) in v.cart_list" :key="key">
                            <span class="checkbox_goods"><el-checkbox :indeterminate="false" :model-value='vo.checked' @change="onChange(k,key)" /></span>
                            <router-link :to="'/goods/'+vo.goods_id"><dl class="goods_item">
                                <dt><img :src="vo.goods_image||require('@/assets/Home/default.png')" :alt="vo.goods_name"></dt>
                                <dd class="goods_title">{{vo.goods_name}}</dd>
                            </dl></router-link>
                            <span class="attr">{{vo.sku_name||'-'}}</span>
                            <span class="price">￥{{vo.goods_price}}</span>
                            <span class="num">
                                <em @click="edit(vo.cart_id,0)">-</em>
                                <input type="text" disabled v-model="vo.buy_num">
                                <em @click="edit(vo.cart_id,1)">+</em>
                            </span>
                            <span class="total">￥{{R.formatFloat(vo.goods_price*vo.buy_num,2)}}</span>
                            <span class="handle" @click="del(vo.cart_id)">移除</span>
                        </li>
                    </ul>
                </div>
            </div>
           
        </div>


        <div class="cart_th" v-if="data.params.total>0">
            <el-checkbox :indeterminate="data.indeterminate" :model-value="data.checkAll" @change="onCheckAllChange">全选</el-checkbox>
            <span class="goods"></span>
            <span class="attr"></span>
            <span class="price"></span>
            <span class="total btcss" >已选择 <font color="#ca151e">{{data.allCount}}</font> 件，总计 <font color="#ca151e">{{R.formatFloat(data.allPrice)}} </font>元</span>
            <span class="handle" style="width:140px;text-align:right;"><div class="error_btn" style="padding:5px 30px;" @click="buy">结算</div></span>
        </div>

        <div style="min-height:600px;padding-top:100px" v-else>
            <el-empty />
        </div>
        


    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import { Shop,CircleCheckFilled,Money,MessageBox,Check} from '@element-plus/icons'
import {useRouter} from 'vue-router'
import { useStore } from 'vuex'
export default {
    components: {Shop,CircleCheckFilled,Money,MessageBox,Check},
    setup() {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const router = useRouter()
        const data = reactive({
            params:{
                page:1,
                per_page:100,
                last_page:1,
                total:0,
            },
            list:[],
            indeterminate:false,
            checkAll:false,
            allCount:0,// 选中商品数量
            allPrice:0.00,// 选中商品价格
            loading:false,
        })

        const loadData = ()=>{
            data.loading = true
            proxy.R.get('/user/carts',data.params).then(res=>{
                if(!res.code || !res.data || !res.msg){
                    data.list = res.data
                    data.params.total = parseInt(res.total)
                    data.params.per_page = parseInt(res.per_page)
                    data.params.last_page = parseInt(res.last_page)
                    data.params.page = parseInt(res.current_page)
                }
            }).catch((err)=>{
                console.log(err)
            }).finally(()=>{
                data.loading = false
            })
            store.dispatch('init/loadCart')
        }

        const del = (id)=>{
            proxy.R.deletes('/user/carts/'+id).then(res=>{
                if(!res.code){
                    proxy.$message.success(proxy.$t('msg.success'))
                    return loadData()
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        const edit = (id,type)=>{
            proxy.R.put('/user/carts/'+id,{is_type:type,buy_num:1}).then(res=>{
                if(!res.code){
                    proxy.$message.success(proxy.$t('msg.success'))
                    return loadData()
                }
            }).catch(error=>{
                console.log(error)
            })
        }

        const onChange = (k,key)=>{
            data.list[k].cart_list[key].checked = !data.list[k].cart_list[key].checked
            let count = 0;
            data.list[k].cart_list.map(item=>{
                if(item.checked){
                    count++;
                }
            })
            if(count==data.list[k].cart_list.length){
                data.list[k].css = false
                data.list[k].checked = true
            }else if(count==0){
                data.list[k].css = false
                data.list[k].checked = false
            }else{
                data.list[k].css = true
                data.list[k].checked = false
            }

            onCheckConst();
        }

        const onCheckAllStoreChange = (k)=>{
            data.list[k].css = false;
            data.list[k].checked = !data.list[k].checked;
            data.list[k].cart_list.forEach(item=>{
                item.checked = data.list[k].checked;
            })
            onCheckConst();
        }

        const onCheckAllChange = ()=>{
            data.indeterminate = false
            let checkAll = !data.checkAll
            data.checkAll = checkAll
            data.list.forEach(item=>{
                item.checked = checkAll
                item.cart_list.forEach(item2=>{
                    item2.checked = checkAll
                })
            })
            onCheckConst();
        }

        const onCheckConst = ()=>{
            data.allPrice = 0;
            let allCount = 0;
            let all = 0;
            data.list.map(item=>{
                item.cart_list.map(item2=>{
                    all++;
                    if(item2.checked){
                        data.allPrice += item2.buy_num*item2.goods_price;
                        allCount++;
                    }
                })
            })
            if(allCount == all){
                data.indeterminate = false;
                data.checkAll = true;
            }else if(allCount==0){
                data.indeterminate = false;
                data.checkAll = false;
            }else{
                data.indeterminate = true;
                data.checkAll = false;
            }
            data.allCount = allCount;
            data.allPrice = proxy.R.formatFloat(data.allPrice);
        }

        // 立即购买
        const buy = async ()=>{
            let params = {
                order:[],
                ifcart:1, // 是否购物车
            };
            data.list.map(item=>{
                item.cart_list.map(item2=>{
                    if(item2.checked){
                        params.order.push(
                            {
                                goods_id:item2.goods_id, // 商品ID
                                sku_id:item2.sku_id, // SKUid 没有则为0
                                buy_num:item2.buy_num, // 购买数量
                                cart_id:item2.cart_id, // 购物车ID
                            },
                        );
                    }
                })
            })
            if(params.order.length<=0) return proxy.$message.error(proxy.$t('home.goods.cartEmpty'))
            await store.dispatch('init/loadCart')
            let str = window.btoa(JSON.stringify(params)); 
            router.push("/order/before/"+str);
        }

        onMounted(()=>{
            loadData()
        })

        return {
            data,
            onCheckAllChange,del,edit,onChange,onCheckAllStoreChange,buy
        }
    },
};
</script>
<style lang="scss" scoped>
.goods_list{
    .goods_item{
        float: left;
        dt{
            width: 40px;
            height: 40px;
            background: #efefef;
            margin:0 20px;
            border-radius: 2px;
            float: left;
            img{
                width: 40px;
                height: 40px;
                border-radius: 2px;
            }
        }
        dd{
            width: 350px;
            float: left;
            &.goods_title{
                text-overflow: -o-ellipsis-lastline;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                line-clamp: 2;
                -webkit-box-orient: vertical;
            }
        }
    }
    ul{
        margin-top:15px;
    }
    ul li{
        border:1px solid #efefef;
        padding-left: 20px;
        height: 60px;
        border-bottom: none;
        box-sizing: border-box;
        padding-top: 10px;
        &:last-child{
            border-bottom: 1px solid #efefef;
        }
        span{
            float: left;
            line-height: 34px;
        }
        .checkbox_goods{
            margin-top: 8px;
        }
        .attr{
            width: 120px;
            text-align: center;
        }
        .price{
            width: 130px;
            text-align: center;
        }
        .num{
            width: 140px;
            text-align: center;
            &:after{
                content:'';
                clear:both;
                display: block;
            }
            em{
                padding: 4px 12px;
                color: #666;
                float: left;
                border: 1px solid #efefef;
                float: left;
                line-height: 24px;
                box-sizing: border-box;
                cursor: pointer;
            }
            input{
                border:1px solid #efefef;
                height: 32px;
                width: 50px;
                outline: none;
                text-align: center;
                float: left;
                margin:0 5px;
            }
        }
        .total{
            width: 150px;
            text-align: center;
            font-weight: bold;
        }
        .handle{
            width: 80px;
            text-align: center;
            color:#ca151e;
            cursor: pointer;
        }
    }
}
.store_list{
    padding:0px 20px;
    // margin-bottom: 20px;
    .open_store{
        border:1px solid #ca151e;
        border-radius: 3px;
        color:#ca151e;
        margin-left: 15px;
        flex: 0 0 70px;
        text-align: center;
        font-size: 12px;
        cursor: pointer;
        line-height: 20px;
    }
}
.cart_table{
    margin:20px;
    .store_title{
        display: flex;
        align-items: center;
    }
}
.step_bar{
    margin:40px 0;
    .step{
        height: 46px;
        line-height: 46px;
        background: #F5F7FA;
        margin-bottom: 50px;
        display: flex;
    }
    .step .item{
        font-size: 16px;
        color:#C0C4CC;
        flex: 1;
        justify-content: center;
        align-items: center;
        display: flex;
        i{
            margin-right: 10px;
        }
        text-align: center;
        border-right: 4px solid #fff;
    }
    .step .item.check{
        color:#333;
        font-weight: bold;
    }
}
.cart_th{
    background: #f5f5f5;
    padding:10px 20px;
    box-sizing: border-box;
    display: flex;
    .goods{
        flex: 0 0 420px;
        padding-left: 60px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        word-break: break-all;
    }
    .price{
        flex: 0 0 110px;
    }
    .attr{
        flex: 0 0 120px;
    }
    .num{
        flex: 0 0 150px;
        padding-left: 20px;
    }
    .total{
        flex: 0 0 70px;
        &.btcss{
            text-align: right;
            flex: 0 0 280px;
        }
    }
    .handle{
        text-align: center;
    }
    span{
        flex: 0 0 120px;
        display: inline-block;
        line-height: 28px;
    }
}
</style>