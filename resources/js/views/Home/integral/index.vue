<template>
    <div class="integral_index">
        <div class="shop_top"><shop-top :subnav_show="subnav_show" :change_color="true"></shop-top></div>
        <div><banner :list="banner_list" height="300px"></banner></div>

        <!-- 积分商城banner下信息 -->
        <div class="banner_b_info width_center_1200">
            <div class="block or">

                <div class="integral_is_login" v-show="!is_login">
                    <div class="integral_login_avatar">
                        <el-image :src="require('@/public/pc/user.png')"></el-image>
                    </div>
                    <div class="integrral_info_login">
                        <router-link to="/user/login">登录</router-link>
                        |
                        <router-link to="/user/register">注册</router-link>
                    </div>
                    <div class="clear"></div>
                </div>
                

                <div class="integrral_info" v-show="is_login">
                    <div class="integral_user_avatar">
                        <el-image style="width:70px;height:70px;" :src="user_info.avatar||require('@/public/pc/user.png')"></el-image>
                    </div>
                    <div class="myintegral">
                        <i class="icon iconfont">&#xe629;</i>
                        我的积分：{{user_info.integral||'0.00'}}
                    </div>
                </div>
                
                <div class="interal_person" v-show="is_login">
                    <router-link to="">积分中心</router-link>
                </div>
            </div>
            <div class="block or2">
                <div class="integral_block_title">私人定制</div>
                <div class="integral_block_img"><el-image :src="require('@/public/pc/integral_01.png')" lazy></el-image></div>
                <div class="integral_block_desc">入驻会员提供个性化定制服务需求</div>
            </div>
            <div class="block or3">
                <div class="integral_block_title">24H服务</div>
                <div class="integral_block_img"><el-image :src="require('@/public/pc/integral_02.png')" lazy></el-image></div>
                <div class="integral_block_desc">入驻会员提供24*7解答服务</div>
            </div>
            <div class="block or4">
                <div class="integral_block_title">优先参与</div>
                <div class="integral_block_img"><el-image :src="require('@/public/pc/integral_03.png')" lazy></el-image></div>
                <div class="integral_block_desc">入驻会员提供满场活动优先参与资格</div>
            </div>
        </div>

        <div class="integral_content">
            <div class="integral_goods_list width_center_1200">
                <div class="integral_goods_list_title">
                    热门推荐<span><router-link to="/integral/goods/index/keyword.">更多 >></router-link></span>
                </div>
                <div class="integral_goods_list_item">
                    <ul>
                        <li v-for="(v,k) in hot_list" :key="k">
                            <router-link :to="'/integral/goods/info/'+v.id">
                                <dl>
                                    <!-- http://127.0.0.1:8000/Uploads/goods/2019_11_21/15743049996339_200.jpg -->
                                    <dt><el-image :src="v.image" lazy></el-image></dt>
                                    <dd class="integral_goods_list_item_title" :title="v.goods_name">{{v.goods_name}}</dd>
                                    <dd class="integral_goods_list_item_price"><i class="icon iconfont">&#xe629;</i> {{v.goods_price}} <font>市场价:{{v.goods_market_price}}</font></dd>
                                    <dd class="integral_goods_list_item_btn">积分兑换</dd>
                                </dl>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="integral_goods_list width_center_1200" v-for="(v,k) in class_list" :key="k">
                <div class="integral_goods_list_title">
                    {{v.name}}<span><router-link :to="'/integral/goods/index/class_id.'+v.cid">更多 >></router-link></span>
                </div>
                <div class="integral_goods_list_item">
                    <ul>
                        <li v-for="(vo,key) in v.goods_list" :key="key">
                            <router-link :to="'/integral/goods/info/'+vo.id">
                                <dl>
                                    <!-- http://127.0.0.1:8000/Uploads/goods/2019_11_21/15743049996339_200.jpg -->
                                    <dt><el-image :src="vo.image" lazy></el-image></dt>
                                    <dd class="integral_goods_list_item_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                    <dd class="integral_goods_list_item_price"><i class="icon iconfont">&#xe629;</i> {{vo.goods_price}} <font>市场价:{{vo.goods_market_price}}</font></dd>
                                    <dd class="integral_goods_list_item_btn">积分兑换</dd>
                                </dl>
                            </router-link>
                        </li>
                    </ul>
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
import banner from "@/components/home/public/banner.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        banner,
        ShopFoot,
    },
    props: {},
    data() {
      return {
          subnav_show:false,
          banner_list:[],  // 幻灯片
          hot_list:[],
          class_list:[],
          user_info:{},
          is_login:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_index_info:function(){
            this.$get(this.$api.homeGetIntegralIndexInfo).then(res=>{
                this.banner_list = res.data.banner.adv;
                this.hot_list = res.data.hot_goods;
                this.class_list = res.data.class_list;
            });
        },
        get_user_info:function(){
            this.$get(this.$api.homeGetUserInfo).then(res=>{
                if(res.code == 200){
                    this.is_login = true;
                    this.user_info = res.data;
                }
                
            });
        }
    },
    created() {
        let token = localStorage.getItem('token');
        if(!this.$isEmpty(token)){
            this.get_user_info();
        }else{
            this.is_login = false;
        }
        this.get_index_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.integral_content{
    background: #f5f5f5;
    padding: 30px 0;
    margin-top: 50px;
    .integral_goods_list_title{
        font-size: 22px;
        margin-bottom: 35px;
        margin-top: 35px;
        span{
            float: right;
            font-size: 12px;
            a:hover{
                color:#ca151e;
            }
        }
    }
    .integral_goods_list_item{
        ul li{
            width: 280px;
            height: 420px;
            float: left;
            margin-right: 23px;
            margin-bottom: 23px;
            background: #fff;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
            dl{
                background: #fff;
                -webkit-transition: all .2s linear;
                transition: all .2s linear;
            }
            dl dt{
                width: 180px;
                height: 180px;
                margin:40px auto 20px auto;
                background: #efefef;
            }
            dl dd.integral_goods_list_item_title{
                width: 220px;
                margin:0 auto;
                text-align: center;
                font-size: 14px;
                line-height: 25px;
                overflow:hidden;
                height: 50px;
            }
            dl dd.integral_goods_list_item_price{
                color:#ca151e;
                text-align: center;
                line-height: 60px;
                font{
                    text-decoration: line-through;
                    color:#999;
                    font-size: 14px;
                    margin-left: 5px;
                }
            }
            dl dd.integral_goods_list_item_btn{
                line-height: 30px;
                color:#ca151e;
                border:1px solid #ca151e;
                text-align: center;
                width: 140px;
                margin:0 auto;
            }
            dl dd.integral_goods_list_item_btn:hover{
                background: #ca151e;
                color:#fff;
            }
            
        }
        ul li:nth-child(4n){
            margin-right: 0;
        }
        ul li:hover dl{
            margin-top:-3px;
        }
        ul li:hover{
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        }
    }
}
.banner_b_info{
    margin-top: 50px;
    .block{
        width: 288.75px;
        height: 210px;
        float: left;
        margin-right: 15px;
        color:#fff;
        text-align: center;
        -webkit-transition: all .2s linear;
        transition: all .2s linear;
        .integral_block_title{
            line-height: 80px;
            font-size: 20px;
        }
        .integral_block_img{
            width: 70px;
            height: 70px;
            margin:0 auto;
            margin-bottom: 20px;
        }
        .integral_block_desc{
            font-size: 12px;
            width: 200px;
            line-height: 20px;
            margin:0 auto;
        }
        .integrral_info{
            color:#fff;
            .integral_user_avatar{
                width: 70px;
                height: 70px;
                margin:30px auto 0 auto;
                border-radius: 50%;
            }
            .integral_user_avatar .el-image{
                border-radius: 50%;
            }
            .myintegral{
                line-height: 60px;
                i{
                    font-size: 20px;
                }
            }
            
        }
        .integral_is_login{
            margin-top: 85px;
            color:#fff;
            .integral_login_avatar{
                width: 40px;
                height: 40px;
                float: left;
                margin-right: 10px;
                margin-left: 80px;
            }
            .integrral_info_login{
                float: left;
            }
            
        }
        
        .integrral_info_login a{
            line-height: 40px;
            color:#fff;
        }
        .interal_person{
            font-size: 14px;
            color:#fff;
            a{
                color:#fff;
            }
            a:hover{
                text-decoration: underline;
            }
        }
    }
    .block:hover{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.2);
    }
    .or{
        // background: #f0f0f0;
        // color:#666;
        background: #ef7232;
        color:#fff;
    }
    .or2{
        background: #ee736e;
    }
    .or3{
        background: #62cca6;
    }
    .or4{
        background: #419ee6;
    }
    .block:last-child{
        margin-right: 0px;
    }
}
</style>