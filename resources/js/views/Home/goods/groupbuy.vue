<template>
    <div class="goods_index">
        <div class="shop_top"><shop-top @search_goods="to_search" :subnav_show="false" :change_color="true"></shop-top></div>
        <div><banner :list="banner_list" height="300px"></banner></div>

        <div class="goods_list width_center_1200" v-if="goods_list.length>0">
            <div class="goods_list_item" v-for="(v,k) in goods_list" :key="k">
                <router-link :to="'/goods/info/'+v.id">
                <dl>
                    <dt><el-image :src="v.image" alt="" lazy></el-image></dt>
                    <dd class="title">{{v.goods_name}}</dd>
                    <dd class="price">￥{{v.groupbuy_price}}</dd>
                    <dd><span>立即购买</span><span>{{v.comment_count}} 人评价</span></dd>
                </dl>
                </router-link>
            </div>
            
        </div>

        <div class="home_empty_list" v-else>
            <i class="font iconfont">&#xe600;</i>暂无获取到相对应数据！
        </div>

        <div class="home_fy_block  width_center_1200">
            <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
        </div>

        <shop-foot></shop-foot>
        <!-- vue 回到顶部 -->
        <el-backtop></el-backtop>
        
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import banner from "@/components/home/public/banner.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
        banner,
    },
    props: {},
    data() {
      return {
          goods_list:[],
          banner_list:[],
          total_data:0, // 总条数
          page_size:30,
          current_page:1,
          info:{
          },
      };
    },
    watch: {
    },
    computed: {},
    methods: {
        // 搜索指定产品
        search_goods:function(){
            this.info.page = this.current_page;
            this.info.is_groupbuy = 1;
            this.$post(this.$api.homeSearchGoods,this.info).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.goods_list = res.data.data;
            });
        },
        get_groupbuy_banner:function(){
            this.$post(this.$api.homeGetGroupbuyBanner).then(res=>{
                this.banner_list = res.data.banner.adv;
            });
        },
        
    },
    created() {
        this.search_goods();
        this.get_groupbuy_banner();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

.goods_list{
    margin:40px auto;
    .goods_list_item{
        float: left;
        width: 224px;
        height: 364px;
        box-sizing: border-box;
        margin: 0 20px 20px 0;
        dl{
            border: 1px solid #efefef;
            box-sizing: border-box;
            width: 224px;
            height: 364px;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
            .el-image{
                width: 180px;
                height: 180px;
                margin:30px auto 25px auto;
                display: block;
            }
        }
        dl dt img{
            width: 180px;
            height: 180px;
            margin:30px auto 25px auto;
            display: block;
        }
        dl dd{
            font-size: 14px;
        }
        dl dd.title{
            color: #000;
            height: 36px;
            text-align: center;
            padding: 0 15px;
            overflow: hidden;
        }
        dl dd.price {
            color: #e01d20;
            line-height: 50px;
            text-align: center;
            padding: 0 15px;
            font-size: 20px;
            overflow: hidden;
        }
        dl dd span:first-child{
            box-sizing: border-box;
            border-right: 1px solid #efefef;
        }
        dl dd span:first-child:hover{
            color:#fff;
            background: #ca151e;
        }
        dl dd span{
            width: 50%;
            display: block;
            border-top: 1px solid #efefef;
            float: left;
            line-height: 41px;
            text-align: center;
        }
    }
    .goods_list_item:nth-child(5n){
        margin-right: 0;
    }
    // .goods_list_item:hover{
    //     // border:1px solid #ca151e;
    // }
    .goods_list_item:hover dl{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        margin-top:-3px;
    }
}
</style>