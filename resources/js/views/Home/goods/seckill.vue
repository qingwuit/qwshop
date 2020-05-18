<template>
    <div class="goods_index">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div class="mbx width_center_1200">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item><a href="/">秒杀</a></el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="goods_where width_center_1200">
            <ul>
                <li v-for="(v,k) in seckill_list" :key="k" @click="change_seckill(v.id)" :class="v.id==seckill_info['id']?'red':''">{{v.name}} <span v-if="v.id==seckill_info['id']">距离{{fontSkill}} {{hour}}:{{min}}:{{secs}}</span><font v-else>{{v.is_active?'活动中':'即将开始'}}</font></li>
            </ul>
        </div>

        <div class="goods_list width_center_1200" v-if="goods_list.length>0">
            <div class="goods_list_item" v-for="(v,k) in goods_list" :key="k">
                <router-link :to="'/goods/info/'+v.goods.id">
                <dl>
                    <dt><el-image :src="v.goods.image" alt="" lazy></el-image></dt>
                    <dd class="title">{{v.goods.goods_name}}</dd>
                    <dd class="price">￥{{v.goods.goods_price}}</dd>
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
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    data() {
      return {
          goods_list:[],
          seckill_list:[],
          seckill_info:{},
          total_data:0, // 总条数
          page_size:30,
          current_page:1,
          id:0,

          hour:0,
          min:0,
          secs:0,
          intvalobj:null,
          fontSkill:'开始',
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
        
        current_change:function(e){
            this.current_page = e;
            this.get_seckill_list();
        },
        get_seckill_list:function(){
            this.$post(this.$api.homeGetSeckillList,{page:this.current_page,id:this.id}).then(res=>{
                this.goods_list = res.data.seckill_goods.data;
                this.seckill_list = res.data.seckill_list;
                this.seckill_info = res.data.seckill_info;
                this.start_time();
            });
        },
        change_seckill:function(id){
            this.id = id;
            this.get_seckill_list();
        },
        start_time:function(){
            if(this.intvalobj != null){
                clearInterval(this.intvalobj);
            }
            this.intvalobj = setInterval(()=>{
                let start_time = this.seckill_info.start_time;
                let end_time = this.seckill_info.end_time;
                let timestamp = Date.parse(new Date())/1000;
                if(start_time>timestamp){
                    let sec = start_time-timestamp;
                    this.hour = Math.floor(sec/3600);
                    this.min = Math.floor((sec-(this.hour*3600))/60);
                    this.secs = sec-(this.hour*60+this.min)*60;
                    this.fontSkill = '开始';
                }else if(end_time>timestamp){
                    let sec2 = end_time-timestamp;
                    this.hour = Math.floor(sec2/3600);
                    this.min = Math.floor((sec2-(this.hour*3600))/60);
                    this.secs = sec2-(this.hour*60+this.min)*60;
                    this.fontSkill = '结束';
                }else{
                    this.hour = 0;
                    this.min = 0;
                    this.secs = 0;
                    clearInterval(this.intvalobj);
                }
            },1000);
        },
        
    },
    created() {
        this.get_seckill_list();
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
    background: #eee;
    ul li{
        float: left;
        line-height: 50px;
        text-align: center;
        width: 300px;
        font-size: 18px;
        span{
            font-size: 12px;
            margin-left: 15px;
            font-weight: normal;
        }
        font{
            border: 1px solid #666;
            border-radius: 6px;
            line-height: 35px;
            font-size: 12px;
            padding: 3px 6px;
            margin-left: 15px;
        }
    }
    ul li.red{
        background: #ca151e;
        color:#fff;
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