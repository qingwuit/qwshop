<template>
    <div class="goods_index">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div class="mbx width_center_1200">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item><a href="/">积分产品</a></el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="goods_where width_center_1200">
            
            <div class="goods_cat_list">
                <div class="goods_cat_title">关键词：</div>
                <ul>
                    <li><el-input v-model="info.keywords" placeholder="输入关键词"></el-input></li>
                </ul>
            </div>

            <div class="goods_cat_list">
                <div class="goods_cat_title">分类筛选：</div>
                <ul>
                    <li :class="info.goods_class_id==0?'font_color':''" @click="brand_change(0)">全部</li>
                    <li v-for="(v,k) in class_list" :key="k" @click="brand_change(v.id)" :class="info.goods_brand==v.id?'font_color':''">{{v.name}}</li>
                </ul>
            </div>
            <div class="goods_cat_list">
                <div class="goods_cat_title">筛选排序：</div>
                <ul class="goods_sort_ul">
                    <li @click="goods_sort_click(0)" :class="info.goods_type==0?'font_color':''">默认<span class="goods_sort_top"><i :style="(info.goods_type==0 && info.order_type==0)?'color:#ca151e':''" class="icon iconfont">&#xe7fe;</i></span><span class="goods_sort_bottom"><i :style="(info.goods_type==0 && info.order_type==1)?'color:#ca151e':''" class="icon iconfont">&#xe7ff;</i></span></li>
                    <li @click="goods_sort_click(1)" :class="info.goods_type==1?'font_color':''">价格<span class="goods_sort_top"><i :style="(info.goods_type==1 && info.order_type==0)?'color:#ca151e':''" class="icon iconfont">&#xe7fe;</i></span><span class="goods_sort_bottom"><i :style="(info.goods_type==1 && info.order_type==1)?'color:#ca151e':''" class="icon iconfont">&#xe7ff;</i></span></li>
                    <li @click="goods_sort_click(2)" :class="info.goods_type==2?'font_color':''">销量<span class="goods_sort_top"><i :style="(info.goods_type==2 && info.order_type==0)?'color:#ca151e':''" class="icon iconfont">&#xe7fe;</i></span><span class="goods_sort_bottom"><i :style="(info.goods_type==2 && info.order_type==1)?'color:#ca151e':''" class="icon iconfont">&#xe7ff;</i></span></li>
                </ul>
            </div>
        </div>

        <div class="goods_list width_center_1200" v-if="goods_list.length>0">
            <div class="goods_list_item" v-for="(v,k) in goods_list" :key="k">
                <router-link :to="'/integral/goods/info/'+v.id">
                <dl>
                    <dt><el-image :src="v.image" alt="" lazy></el-image></dt>
                    <dd class="title">{{v.goods_name}}</dd>
                    <dd class="price">{{v.goods_price}} 积分</dd>
                    <dd><span>立即兑换</span><span>{{v.goods_sale_num}} 人兑换</span></dd>
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
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          class_list:[],
          goods_list:[],
          info:{
              goods_class_id:0,
              goods_type:0,
              keywords:'',
              order_type:0,
          },
          total_data:0, // 总条数
          page_size:30,
          current_page:1,
      };
    },
    watch: {
        info:{
            handler(){
                this.search_goods();
            },
            deep:true,
        }
    },
    computed: {},
    methods: {
  
        // 获取品牌信息
        get_class_list:function(){
            this.$get(this.$api.homeGetIntegralGoodsClass).then(res=>{
                this.class_list = res.data;
            });
        },
        brand_change:function(e){
            this.info.goods_class_id = e;
        },
        goods_sort_click:function(e){
            // 是否取反
            if(e == this.info.goods_type){
                if(this.info.order_type == 0){
                    this.info.order_type = 1;
                }else{
                    this.info.order_type = 0;
                }
            }else{ // 新条件先倒叙
                this.info.goods_type = e;
                this.info.order_type = 0;
            }
        },
        // 搜索指定产品
        search_goods:function(){
            this.info.page = this.current_page;
            this.$post(this.$api.homeSearchIntegralGoods,this.info).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.goods_list = res.data.data;
            });
        },
        
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.search_goods();
        },
        get_search_where:function(){
            let params = this.$route.params.info;
            let paramsArr = params.split('|');
            paramsArr.forEach(res=>{
                let paramsInfo = [];
                paramsInfo = res.split('.');
                if(paramsInfo[0] == 'keywords'){  // 搜索关键词
                    if(paramsInfo.length==2){
                        this.info.keywords = paramsInfo[1];
                    }else{
                        this.info.keywords = '';
                    }
                }
                if(paramsInfo[0] == 'class_id'){
                    if(paramsInfo.length==2){
                        this.info.goods_class_id = paramsInfo[1];
                    }else{
                        this.info.goods_class_id = 0;
                    }
                }
                if(paramsInfo[0] == 'goods_type'){
                    if(paramsInfo.length==2){
                        this.info.goods_type = paramsInfo[1];
                    }else{
                        this.info.goods_type = 0;
                    }
                }
                if(paramsInfo[0] == 'order_type'){
                    if(paramsInfo.length==2){
                        this.info.order_type = paramsInfo[1];
                    }else{
                        this.info.order_type = 0;
                    }
                }
            });
            this.search_goods();
        },
    },
    created() {
        this.get_class_list();
        this.get_search_where();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.goods_sort_top{
    position: relative;
    color:#666;
    i{
        position: absolute;
        right: -16px;
        font-size: 12px;
    }
}
.goods_sort_bottom{
    
    position: relative;
    color:#666;
    i{
        position: absolute;
        right: -16px;
        font-size: 12px;
    }
}
.mbx{
    margin:20px auto;
}
.goods_where{
    border:1px solid #efefef;
    line-height: 50px;
    font-size: 14px;
    .goods_cat_list{
        border-bottom: 1px solid #efefef;
        padding-left:20px; 
        .goods_cat_title{
            float: left;
            margin-right: 20px;
        }
        ul{
            float: left;
        }
        ul.goods_sort_ul{
            li{
                margin-right: 30px;
            }
        }
        ul li{
            float: left;
            color:#666;
            margin-right: 20px;
            a{
                color:#666;
            }
            a:hover{
                color:#ca151e;
            }
        }
        li:hover{
            color:#ca151e;
        }
        ul li.font_color{
            color:#ca151e;
        }
        
    }
    .goods_cat_list:after{
        content:'';
        clear:both;
        display: block;
    }
    .goods_cat_list:last-child{
        border-bottom: none;
    }
}
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
    
    .goods_list_item:hover dl{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        margin-top:-3px;
    }
}
</style>