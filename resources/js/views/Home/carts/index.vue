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

        <div class="cart_th">
            <a-checkbox :indeterminate="indeterminate">全选</a-checkbox>
            <span class="goods">商品</span>
            <span class="attr">规格</span>
            <span class="price">单价（元）</span>
            <span class="num">数量</span>
            <span class="total">小计</span>
            <span class="handle">操作</span>
        </div>

        <div class="cart_table">
            <div class="store_list">
                <div class="store_title"><a-checkbox :indeterminate="indeterminate">青梧商城</a-checkbox><span class="open_store">进入店铺</span></div>
                <div class="goods_list">
                    <ul>
                        <li>
                            <span class="checkbox_goods"><a-checkbox :indeterminate="indeterminate" /></span>
                            <router-link :to="'#'"><dl class="goods_item">
                                <dt><img src="http://api.qingwuit.com/Uploads/goods/2020_01_14/15789867525935_200.png" alt=""></dt>
                                <dd>荣耀20 PRO 荣耀最强拍照手机 4800万全焦段AI四摄 麒麟980全网通版8GB+128GB 冰岛幻境</dd>
                            </dl></router-link>
                            <span class="attr">-</span>
                            <span class="price">￥20.00</span>
                            <span class="num">
                                <font><a-icon type="minus" /></font>
                                <input type="text" disabled>
                                <font><a-icon type="plus" /></font>
                            </span>
                            <span class="total">￥40.00</span>
                            <span class="handle">移除</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="store_list">
                <div class="store_title"><a-checkbox :indeterminate="indeterminate">青梧商城</a-checkbox><span class="open_store">进入店铺</span></div>
                <div class="goods_list">
                    <ul>
                        <li>
                            <span class="checkbox_goods"><a-checkbox :indeterminate="indeterminate" /></span>
                            <router-link :to="'#'"><dl class="goods_item">
                                <dt><img src="http://api.qingwuit.com/Uploads/goods/2020_01_14/15789867525935_200.png" alt=""></dt>
                                <dd>荣耀20 PRO 荣耀最强拍照手机 4800万全焦段AI四摄 麒麟980全网通版8GB+128GB 冰岛幻境</dd>
                            </dl></router-link>
                            <span class="attr">-</span>
                            <span class="price">￥20.00</span>
                            <span class="num">
                                <font><a-icon type="minus" /></font>
                                <input type="text" disabled>
                                <font><a-icon type="plus" /></font>
                            </span>
                            <span class="total">￥40.00</span>
                            <span class="handle">移除</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="cart_th">
            <a-checkbox :indeterminate="indeterminate">全选</a-checkbox>
            <span class="goods"></span>
            <span class="attr"></span>
            <span class="price"></span>
            <span class="num"></span>
            <span class="total" >已选择 <font color="#ca151e">0</font> 件</span>
            <span class="handle" style="width:140px;text-align:right;"><div class="error_btn" style="padding:5px 30px;">结算</div></span>
        </div>


    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {},
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
        width: 160px;
        padding-left: 20px;
    }
    .total{
        width: 80px;
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