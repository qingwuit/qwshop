<template>
    <div class="create_order_1 w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item check"><a-icon type="shopping-cart" />我的购物车</div>
                <div class="item"><a-icon type="car" />物流地址</div>
                <div class="item"><a-icon type="account-book" />选择支付</div>
                <div class="item"><a-icon type="check-circle" />支付成功</div>
            </div>
        </div>

        <div class="cart_th" v-if="params.total>0">
            <a-checkbox :indeterminate="indeterminate" :checked="checkAll" @change="onCheckAllChange">全选</a-checkbox>
            <span class="goods">商品</span>
            <span class="attr">规格</span>
            <span class="price">单价（元）</span>
            <span class="num">数量</span>
            <span class="total">小计</span>
            <span class="handle">操作</span>
        </div>

        <div class="cart_table" v-if="params.total>0">
            <div class="store_list" v-for="(v,k) in list" :key="k">
                <div class="store_title"><a-checkbox :indeterminate="v.css" :checked='v.checked' @change="onCheckAllStoreChange(k)">{{v.store_name}}</a-checkbox><span class="open_store" @click="$router.push('/store/'+v.store_id)">进入店铺</span></div>
                <div class="goods_list">
                    <ul>
                        <li v-for="(vo,key) in v.cart_list" :key="key">
                            <span class="checkbox_goods"><a-checkbox :indeterminate="false" :checked='vo.checked' @change="onChange(k,key)" /></span>
                            <router-link :to="'/goods/'+vo.goods_id"><dl class="goods_item">
                                <dt><img :src="vo.goods_image||require('@/asset/order/default.png')" :alt="vo.goods_name"></dt>
                                <dd>{{vo.goods_name}}</dd>
                            </dl></router-link>
                            <span class="attr">{{vo.sku_name||'-'}}</span>
                            <span class="price">￥{{vo.goods_price}}</span>
                            <span class="num">
                                <font @click="edit(vo.cart_id,0)"><a-icon type="minus" /></font>
                                <input type="text" disabled v-model="vo.buy_num">
                                <font @click="edit(vo.cart_id,1)"><a-icon type="plus" /></font>
                            </span>
                            <span class="total">￥{{$formatFloat(vo.goods_price*vo.buy_num,2)}}</span>
                            <span class="handle" @click="del(vo.cart_id)">移除</span>
                        </li>
                    </ul>
                </div>
            </div>
           
        </div>


        <div class="cart_th" v-if="params.total>0">
            <a-checkbox :indeterminate="indeterminate" :checked="checkAll" @change="onCheckAllChange">全选</a-checkbox>
            <span class="goods"></span>
            <span class="attr"></span>
            <span class="price"></span>
            <span class="num"></span>
            <span class="total" >已选择 <font color="#ca151e">{{allCount}}</font> 件，总计 <font color="#ca151e">{{$formatFloat(allPrice)}} </font>元</span>
            <span class="handle" style="width:140px;text-align:right;"><div class="error_btn" style="padding:5px 30px;" @click="buy">结算</div></span>
        </div>

        <div style="min-height:600px;padding-top:100px" v-else>
            <a-empty />
        </div>
        


    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:100,
              total:0,
          },
          list:[],
          indeterminate:false,
          checkAll:false,
          allCount:0,// 选中商品数量
          allPrice:0.00,// 选中商品价格
      };
    },
    watch: {},
    computed: {},
    methods: {
        onload(){
            this.$get(this.$api.homeCarts,this.params).then(res=>{
                this.params.total = res.data.total;
                this.params.per_page = res.data.per_page;
                this.params.current_page = res.data.current_page;
                this.list = res.data.data;
                this.list.forEach(item=>{

                })
            })
        },
       
        del(id){
            this.$delete(this.$api.homeCarts+'/'+id).then(res=>{
                this.onload();
                this.cart_count();
                return this.$returnInfo(res);
            })
        },
        edit(id,type){
            this.$put(this.$api.homeCarts+'/'+id,{is_type:type,buy_num:1}).then(res=>{
                this.onload();
                this.cart_count();
                return this.$returnInfo(res);
            })
        },
        cart_count(){
            this.$get(this.$api.homeCarts+'/cart_count').then(res=> {
                if(res.code == 200){
                    this.$store.dispatch('homeCart/set_cart_num',res.data);
                }
            });
        },
        onChange(k,key){
            this.$set(this.list[k].cart_list[key],'checked',!this.list[k].cart_list[key].checked)
            let count = 0;
            this.list[k].cart_list.forEach(item=>{
                if(item.checked){
                    count++;
                }
            })
            // console.log(count,this.list[k].cart_list.length);
            if(count==this.list[k].cart_list.length){
                this.$set(this.list[k],'css',false);
                this.$set(this.list[k],'checked',true);
                
            }else if(count==0){
                this.$set(this.list[k],'css',false);
                this.$set(this.list[k],'checked',false);
            }else{
                this.$set(this.list[k],'css',true);
                this.$set(this.list[k],'checked',false);
            }

            this.onCheckConst();
        },
        onCheckAllStoreChange(k){
            this.list[k].css = false;
            this.list[k].checked = !this.list[k].checked;
            this.list[k].cart_list.forEach(item=>{
                item.checked = this.list[k].checked;
            })
            this.onCheckConst();
        },
        onCheckAllChange(){
            this.indeterminate = false;
            let checkAll = !this.checkAll;
            this.checkAll = checkAll;
            this.list.forEach(item=>{
                item.checked = checkAll;
                item.cart_list.forEach(item2=>{
                    item2.checked = checkAll;
                })
            })
            this.onCheckConst();
        },
        // 最外层checkbox状态修改 加上统计数据价格商品数量
        onCheckConst(){
            this.allPrice = 0;
            let allCount = 0;
            let all = 0;
            this.list.forEach(item=>{
                item.cart_list.forEach(item2=>{
                    all++;
                    if(item2.checked){
                        this.allPrice += item2.buy_num*item2.goods_price;
                        allCount++;
                    }
                })
            })
            if(allCount == all){
                this.indeterminate = false;
                this.checkAll = true;
            }else if(allCount==0){
                this.indeterminate = false;
                this.checkAll = false;
            }else{
                this.indeterminate = true;
                this.checkAll = false;
            }
            this.allCount = allCount;
            this.allPrice = this.$formatFloat(this.allPrice);
        },
        // 立即购买
        buy(){
            let params = {
                order:[],
                ifcart:1, // 是否购物车
            };
            this.list.forEach(item=>{
                item.cart_list.forEach(item2=>{
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
            this.cart_count();
            let str = window.btoa(JSON.stringify(params)); 
            this.$router.push("/order/create_order/"+str);
        },
    },
    created() {
        this.onload();
    },
    mounted() {}
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
            font{
                padding: 4px 12px;
                color: #666;
                float: left;
                border: 1px solid #efefef;
                float: left;
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
    margin-bottom: 20px;
    .open_store{
        border:1px solid #ca151e;
        border-radius: 3px;
        color:#ca151e;
        margin-left: 15px;
        padding:2px 10px;
        font-size: 12px;
        cursor: pointer;
    }
}
.cart_table{
    margin:20px;
}
.step_bar{
    margin:40px 0;
}
.cart_th{
    background: #f5f5f5;
    padding:10px 20px;
    .goods{
        width:470px;
        padding-left: 120px;
    }
    .price{
        width: 110px;
    }
    .attr{
        width: 110px;
    }
    .num{
        width: 80px;
        padding-left: 20px;
    }
    .total{
        width: 160px;
    }
    .handle{
        text-align: center;
    }
    span{
        width: 120px;
        display: inline-block;
    }
}
</style>