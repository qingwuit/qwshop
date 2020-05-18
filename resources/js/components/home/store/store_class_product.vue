<template>
    <div class="shop_product_block width_center_1200">
        <div class="product_left">
            <left-store-info></left-store-info>
            <left-sale-list :user_id="user_id"></left-sale-list>
        </div>
        <div class="product_right" v-if="store_goods_list.length>0">
            <skeleton type="custom" v-show="!isSkeleton" :options="{ width: '100%', height: '100%' }" :childrenOption="[
                {type: 'card', rules: 'a,d', active: true, options:{width: '260px',height: '260px'} },
                {type: 'card', rules: 'b,e', active: true, options:{width: '260px',height: '260px'} },
                {type: 'card', rules: 'c,f', active: true, options:{width: '260px',height: '260px'} },
            ]" />
            <div class="store_goods_class_block" v-show="isSkeleton">
                <div class="store_right_block">{{class_info.name}}</div>
                <ul>
                    <li v-for="(vo,key) in store_goods_list" :key="key"><router-link :to="'/goods/info/'+vo.id">
                        <div class="product_act_in">
                            <dl>
                                <dt><el-image :src="vo.image" :alt="vo.goods_name" lazy></el-image></dt>
                                <dd class="product_title">{{vo.goods_name}}</dd>
                                <dd class="product_subtitle">{{vo.goods_subname}}</dd>
                                <dd class="product_price">￥{{vo.goods_price}}<span>{{vo.goods_market_price}}元</span></dd>
                            </dl>
                        </div>
                    </router-link></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="home_fy_block">
                <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
            </div>
        </div>
        <div class="product_right" v-else>
            <div class="store_right_block">温馨提示！</div>
            <div style="text-align:center;line-height:500px;">该分类暂时没有任何产品！</div>
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
          store_goods_list:[],
          class_info:{},
          no_goods:false,
          user_id:0,
          total_data:0, // 总条数
          page_size:24,
          current_page:1,
          isSkeleton:false, // false 骨架
      };
    },
    watch: {
        '$route'(to,from) {
            // eslint-disable-next-line no-console
            console.log(to,from)
            this.get_store_index_list();
        },
    },
    computed: {},
    methods: {
        get_store_index_list:function(){
            this.$post(this.$api.homeGetStoreClassGoods,{user_id:this.$route.params.id,class_id:this.$route.params.class_id}).then(res=>{
                this.page_size = res.data.store_goods_class_list.per_page;
                this.total_data = res.data.store_goods_class_list.total;
                this.current_page = res.data.store_goods_class_list.current_page;
                this.store_goods_list = res.data.store_goods_class_list.data;
                this.class_info = res.data.class_info;
                this.isSkeleton = true; // 骨架片
            });
        },
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_store_index_list();
        },
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
.home_fy_block{width: 100%;margin-left: 20px;}
.store_goods_class_block{margin-bottom: 30px;}
.product_right{
    width: 966px;
    float: left;
    .store_right_block{
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
            width: 220px;
            overflow: hidden;
            text-align: center;
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