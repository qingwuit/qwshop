<template>
    <div class="cart">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>

        <div class="cart_body">
            <div class="join_over width_center_1200">
                <div class="join_bzt">
                    <el-steps :active="0" simple>
                        <el-step title="我的购物车" icon="el-icon-shopping-cart-full"></el-step>
                        <el-step title="物流地址" icon="el-icon-map-location"></el-step>
                        <el-step title="选择支付" icon="el-icon-mouse"></el-step>
                        <el-step title="支付成功" icon="el-icon-check"></el-step>
                    </el-steps>
                </div>
            </div>

            <!-- 店铺和商品列表 -->
            <div class="store_goods_list width_center_1200">
                <div class="store_goods_list_title">
                    <el-row>
                        <el-col :span="4"><el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox></el-col>
                        <el-col :span="10">商品</el-col>
                        <el-col :span="2">单价（元）</el-col>
                        <el-col :span="4">数量</el-col>
                        <el-col :span="2">小计</el-col>
                        <el-col :span="2">操作</el-col>
                    </el-row>
                </div>
            </div>
            <div class="store_goods_all  width_center_1200" v-if="cart_list.length>0">
                <div class="store_goods_all_store_info" v-for="(v,k) in cart_list" :key="k">
                    <el-checkbox :indeterminate="store_list_check[k].chose" v-model="store_list_check[k].check" @change="handleStoreChange(k)">{{v[0].store_name}}<span>进入店铺</span></el-checkbox>
                    <ul>
                        <li v-for="(vo,key) in v" :key="key">
                            <el-row>
                                <el-col :span="1"><el-checkbox v-model="vo.check" @change="handleCartListChange(key,k)"></el-checkbox></el-col>
                                <el-col :span="13">
                                    <div class="goods_thumb"><img width="40px" height="40px" :src="vo.image" alt=""></div>
                                    <div class="goods_title"><router-link to="/">{{vo.goods_name}}</router-link></div>
                                </el-col>
                                <el-col :span="2"><div class="price">￥{{vo.goods_price}}</div></el-col>
                                <el-col :span="4">
                                    <div class="jian" @click="change_cart(vo,1,0)"><i class="el-icon-minus"></i></div>
                                    <div class="nums"><input disabled v-model="vo.goods_num" type="text"></div>
                                    <div class="jia" @click="change_cart(vo,1,1)"><i class="el-icon-plus"></i></div>
                                </el-col>
                                <el-col :span="2"><div class="price" style="color:#333;font-weight:bold;">￥{{vo.goods_num*vo.goods_price}}</div></el-col>
                                <el-col :span="2"><div class="remove" @click="del_cart(vo)">移除</div></el-col>
                            </el-row>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <!-- 没有购物车商品则 -->
            <div class="empty_show_list width_center_1200" v-else>
                <dl>
                    <dt><img :src="require('@/public/pc/not-common-icon.png')" alt=""></dt>
                    <dd>主人，您购物车内任何商品哟~</dd>
                    <dd class="btn"><router-link to="/">前往商城</router-link></dd>
                </dl>
            </div>

            <div class="store_goods_list width_center_1200">
                <div class="store_goods_list_title">
                    <el-row>
                        <el-col :span="13"><el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox></el-col>
                        <el-col :span="4"><div class="topay2">已选择<font color="#ca151e">{{total_num}}</font>件商品</div></el-col>
                        <el-col :span="5"><div class="topay2">总价(不含运费)：<span>￥{{total_price}}</span></div></el-col>
                        <el-col :span="2"><div class="topay" @click="to_create">结算</div></el-col>
                    </el-row>
                </div>
            </div>
        </div>

        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          checkAll:false,
          checkStore:[],
          isIndeterminate:false,
          cart_list:[],
          store_list_check:[],
          total_price:0, // 统计价格
          total_num:0, // 统计数量
      };
    },
    watch: {},
    computed: {},
    methods: {
        // checkbox 选择
        handleCheckAllChange:function(e){
            if(e){
                this.store_list_check.forEach(res=>{
                    res.check = true;
                    res.chose = false;
                });
                this.cart_list.forEach(res=>{
                    res.forEach(goodsRes => {
                        goodsRes.check = true;
                    });
                });
            }else{
                this.store_list_check.forEach(res=>{
                    res.check = false;
                    res.chose = false;
                });
                this.cart_list.forEach(res=>{
                    res.forEach(goodsRes => {
                        goodsRes.check = false;
                    });
                });
            }
            this.sum_price(); // 统计价格
        },
        // 商家checkbox
        handleStoreChange:function(e){
            if(this.store_list_check[e]['check'] == true && this.store_list_check[e]['chose'] == false){
                this.cart_list[e].forEach(res=>{
                    res.check = true;
                });
                this.isIndeterminate = false;
                this.checkAll = true;
            }
            if(this.store_list_check[e]['check'] == false && this.store_list_check[e]['chose'] == false){
                this.cart_list[e].forEach(res=>{
                    res.check = false;
                });
                this.isIndeterminate = false;
                this.checkAll = false;
            }
            if(this.store_list_check[e]['chose'] == true){
                this.cart_list[e].forEach((res)=>{
                    res.check = true;
                });
                this.store_list_check[e]['chose'] = false;
                this.isIndeterminate = true;
                this.checkAll = true;
            }
            this.sum_price(); // 统计价格
        },
        // 购物车列表checkbox
        handleCartListChange:function(e,index){
            let a = 0;
            this.cart_list[index].forEach(res=>{
                if(res.check){
                    a += 1;
                }
            });

            if(a == this.cart_list[index].length){
                this.store_list_check[index]['check'] = true;
                this.store_list_check[index]['chose'] = false;
            }else if(a==0){
                this.store_list_check[index]['chose'] = false;
                this.store_list_check[index]['check'] = false;
            }else{
                this.store_list_check[index]['chose'] = true;
            }
            this.sum_price(); // 统计价格
        },
        change_cart:function(info,num,type,is_change_num=0){
            let data = {};
            data.id = info.id;
            data.goods_num = num;
            data.type = is_change_num; // 直接数字修改
            data.change_type = type;    // 加还是减
            this.$post(this.$api.homeChangeCart,data).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }
                this.get_cart_list();
            });
        },
        del_cart:function(info){
            this.$post(this.$api.homeDelCart,{ids:info.id}).then(res=>{
                if(res.code == 200){
                    this.get_cart_list();
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
                
            });
        },
        sum_price:function(){
            let total_price = 0;
            let total_num = 0;
            this.cart_list.forEach(res=>{
                res.forEach(cartRes=>{
                    if(cartRes.check){
                        total_price += (cartRes.goods_num*cartRes.goods_price);
                        total_num += 1;
                    }
                });
            });
            this.total_price = total_price;
            this.total_num = total_num;
        },
        // 前往设置收货地址
        to_create:function(){
            let chose_cart = [];
            this.cart_list.forEach(res=>{
                res.forEach(goodsRes=>{
                    if(!this.$isEmpty(goodsRes.check) && goodsRes.check == true){
                        let str = goodsRes.goods_id+'-'+goodsRes.id+'|'+goodsRes.store_id+'|'+goodsRes.goods_num;
                        if(!this.$isEmpty(goodsRes.spec_id) && goodsRes.spec_id>0){
                            str += '|'+goodsRes.spec_id;
                        }
                        chose_cart.push(str);
                    }
                });
            });
            if(chose_cart.length<=0){
                return this.$message.error('请选择购物车产品！');
            }
            let cart_str = chose_cart.join(',');
            this.$router.push("/order/create_order/1/"+cart_str);
        },
        get_cart_list:function(){
            this.$get(this.$api.homeGetCartList).then(res=>{
                this.cart_list = res.data;
                this.store_list_check = [];
                this.cart_list.forEach(()=>{
                   this.store_list_check.push({check:false,chose:false}); 
                });
            });
        }
    },
    created() {
        this.get_cart_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.join_bzt{
    margin-top:30px;
}
.store_goods_list{
    margin-top: 30px;
    font-size: 14px;
    .store_goods_list_title{
        background: #f5f5f5;
        padding: 10px 20px;
    }
    .topay{
        background: #ca151e;
        text-align: center;
        line-height: 30px;
        width: 80px;
        color:#fff;
        border-radius: 3px;
        font-size: 12px;
    }
    .topay2{
        line-height: 30px;
        text-align: right;
        padding-right: 15px;
        font{
            padding:0 5px;
        }
        span{
            font-size: 18px;
            line-height: 30px;
            color:#ca151e;
        }
    }
}
.store_goods_all_store_info{
    margin-top: 20px;
    font-size: 14px;
    margin-left: 40px;
    span{
        padding:3px 8px;
        border:1px solid #ca151e;
        border-radius: 3px;
        color:#ca151e;
        margin-left: 15px;
    }
    ul{
        margin-top: 20px;
        margin-right: 40px;
        border:1px solid #efefef;
        li{
            border-bottom: 1px solid #efefef;
            padding-left: 20px;
            height: 60px;
            .el-checkbox{
                margin-top: 10px;
            }
            .goods_thumb{
                margin-top: 10px;
                float: left;
                margin-right: 15px;
                img{
                    border-radius: 3px;
                }
            }
            .goods_title{
                margin-top: 10px;
                
            }
            .remove{
                color:#ca151e;
                line-height: 60px;
            }
            .price{
                line-height: 60px;
                color:#666;
            }
            .jia,.jian{
                line-height: 30px;
                padding:0 8px;
                color:#666;
                float: left;
                border: 1px solid #efefef;
                margin-top: 15px;
            }
            .nums{
                margin:0 10px;
                margin-top: 15px;
                float: left;
                width: 40px;
                input{
                    width: 40px;
                    height: 30px;
                    outline: none;
                    border:1px solid #efefef;
                    padding:0px 5px;
                    box-sizing: border-box;
                    color:#666;
                }
            }
        }
        li a{
            color:#666;
        }
        li a:hover{
            color:#ca151e;
        }
        li:after{
            content: "";
            display: block;
            clear: both;
        }
        li:last-child{
            border-bottom: none;
        }
    }
}
</style>