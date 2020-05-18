<template>
    <div class="shop_product_block width_center_1200">
        <div class="product_left">
            <left-store-info></left-store-info>
            <left-sale-list :user_id="user_id"></left-sale-list>
        </div>
        <div class="product_right" v-if="!no_goods">
            <skeleton type="custom" v-show="!isSkeleton" :options="{ width: '100%', height: '100%' }" :childrenOption="[
                {type: 'card', rules: 'a,d', active: true, options:{width: '260px',height: '260px'} },
                {type: 'card', rules: 'b,e', active: true, options:{width: '260px',height: '260px'} },
                {type: 'card', rules: 'c,f', active: true, options:{width: '260px',height: '260px'} },
            ]" />
            <div class="store_goods_class_block" v-if="top_goods_list.length>0 && isSkeleton">
                <div class="store_right_block">热门推荐</div>
                <ul>
                    <li v-for="(v,k) in top_goods_list" :key="k"><router-link :to="'/goods/info/'+v.id">
                        <div class="product_act_in">
                            <dl>
                                <dt><el-image :src="v.image" :alt="v.goods_name" lazy></el-image></dt>
                                <dd class="product_title" :title="v.goods_name">{{v.goods_name}}</dd>
                                <dd class="product_subtitle">{{v.goods_subname}}</dd>
                                <dd class="product_price">￥{{v.goods_price}}<span>{{v.goods_market_price}}元</span></dd>
                            </dl>
                        </div>
                    </router-link></li>
                </ul>
                <div class="clear"></div>
            </div>

            <div class="store_goods_class_block" v-for="(v,k) in store_goods_list.store_goods_class_list" :key="k" v-show="v.length>0">
                <div class="store_right_block">{{store_goods_list.store_goods_class_arr[k]['name']}}<span><router-link :to="'/store/'+$route.params.id+'/class/'+store_goods_list.store_goods_class_arr[k]['id']">查看更多 +</router-link></span></div>
                <ul>
                    <li v-for="(vo,key) in v" :key="key"><router-link :to="'/goods/info/'+vo.id">
                        <div class="product_act_in">
                            <dl>
                                <dt><el-image :src="vo.image" :alt="vo.goods_name" lazy></el-image></dt>
                                <dd class="product_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                <dd class="product_subtitle">{{vo.goods_subname}}</dd>
                                <dd class="product_price">￥{{vo.goods_price}}<span>{{vo.goods_market_price}}元</span></dd>
                            </dl>
                        </div>
                    </router-link></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="product_right" v-else>
            <div class="store_right_block">温馨提示！</div>
            <div style="text-align:center;line-height:500px;">该店铺暂时没有任何产品！</div>
        </div>
    </div>
</template>

<script>
import leftSaleList from "@/components/home/public/left_sale_list"
import leftStoreInfo from "@/components/home/store/left_store_info"
export default {
    components: {
        leftSaleList,
        leftStoreInfo,
    },
    props: {},
    data() {
      return {
          store_goods_list:{},
          top_goods_list:[],
          no_goods:false,
          user_id:0,
          isSkeleton:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_store_index_list:function(){
            
            this.$post(this.$api.homeGetStoreIndexInfo,{user_id:this.$route.params.id}).then(res=>{
                this.store_goods_list = res.data;
                this.top_goods_list = res.data.top_goods_list;
                this.isSkeleton = true;
            });
        }
    },
    created() {
        this.get_store_index_list();
        
    },
    mounted() {
        this.user_id = parseInt(this.$route.params.id);
    }
};
</script>
<style lang="scss" scoped>
.product_left{
    width: 234px;
    float: left;
    box-sizing: border-box;
}
.store_goods_class_block{margin-bottom: 30px;}
.product_right{
    width: 966px;
    float: left;
    .store_right_block{
        span{
            float: right;
            font-size: 12px;
            color:#666;
            margin-right: 20px;
            a:hover{
                color:#ca151e;
            }
        }
        font-size: 14px;
        border-left: 3px solid #ca151e;
        padding-left: 30px;
        margin-bottom: 20px;
        background: #fafafa;
        line-height: 35px;
        margin-left: 20px;
    }
    ul li{
        width: 220px;
        height: 300px;
        margin-bottom: 14px;
        margin-left: 20px;
        box-sizing: border-box;
        
        
        float: left;
        .product_act_in{
            width: 220px;
            height: 300px;
            background: #fff;
            box-sizing: border-box;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
            // background: #fafafa;
            border:1px solid #f1f1f1;
        }
        dl dt{
            width: 140px;
            height: 140px;
            margin:0 auto;
            padding-top: 20px;
        }
        dl dt img{
            width: 180px;
            height: 180px;
        }
        dl dd{
            width: 190px;
            overflow: hidden;
            text-align: center;
            margin:0 auto;
            line-height: 30px;
        }
        dl dd.product_title{
            font-size: 14px;
            margin-top: 30px;
            height: 30px;
        }
        dl dd.product_subtitle{
            margin-top: 0px;
            font-size: 12px;
            color:#b0b0b0;
            line-height: 14px;
        }
        dl dd.product_price{
            margin-top: 10px;
            font-size: 16px;
            color:#ca151e;
            line-height: 34px;
            span{
                font-size: 14px;
                color:#b0b0b0;
                margin-left: 8px;
                text-decoration: line-through;
            }
        }
    }
    
    ul li:hover .product_act_in{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        margin-top:-3px;
    }
    
}
.seckill_title{
    font-size: 22px;
    margin-bottom: 20px;
    span{
        float:right;
        font-size: 14px;
        padding-right: 15px;
    }
    a:hover{
        color:#ca151e;
    }
}
</style>