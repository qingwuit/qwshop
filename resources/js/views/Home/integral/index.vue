<template>
    <div class="integral_index">
        <div><banner :banner="data.store_slide||[{image:require('@/assets/Home/integral.jpg').default}]" :height="350" /></div>

        <!-- 积分商城banner下信息 -->
        <div class="banner_b_info w1200">
            <div class="block or">

                <div class="integral_is_login" v-show="!isLogin">
                    <div class="integral_login_avatar">
                        <img :src="require('@/assets/Home/user_default2.png').default" />
                    </div>
                    <div class="integrral_info_login">
                        <router-link to="/login">登录</router-link>
                        |
                        <router-link to="/register">注册</router-link>
                    </div>
                    <div class="clear"></div>
                </div>
                

                <div class="integrral_info" v-show="isLogin">
                    <div class="integral_user_avatar">
                        <img style="width:70px;height:70px;" :src="data.userInfo.avatar||require('@/assets/Home/user_default2.png').default" />
                    </div>
                    <div class="myintegral">
                        <el-icon><Coin /></el-icon>
                        我的积分：{{data.userInfo.integral||'0.00'}}
                    </div>
                </div>
                
                <div class="interal_person" v-show="isLogin">
                    <router-link to="">积分中心</router-link>
                </div>
            </div>
            <div class="block or2">
                <div class="integral_block_title">私人定制</div>
                <div class="integral_block_img"><img :src="require('@/assets/Home/integral_01.png').default" /></div>
                <div class="integral_block_desc">入驻会员提供个性化定制服务需求</div>
            </div>
            <div class="block or3">
                <div class="integral_block_title">24H服务</div>
                <div class="integral_block_img"><img :src="require('@/assets/Home/integral_02.png').default" /></div>
                <div class="integral_block_desc">入驻会员提供24*7解答服务</div>
            </div>
            <div class="block or4">
                <div class="integral_block_title">优先参与</div>
                <div class="integral_block_img"><img :src="require('@/assets/Home/integral_03.png').default" /></div>
                <div class="integral_block_desc">入驻会员提供满场活动优先参与资格</div>
            </div>
        </div>

        <div class="integral_content">
            <div class="integral_goods_list w1200" v-if="data.recommend.length>0">
                <div class="integral_goods_list_title">
                    热门推荐<span><router-link to="/integral/search">更多 >></router-link></span>
                </div>
                <div class="integral_goods_list_item">
                    <ul>
                        <li v-for="(v,k) in data.recommend" :key="k">
                            <router-link :to="'/integral/goods/'+v.id">
                                <dl>
                                    <dt><img :src="v.goods_master_image" /></dt>
                                    <dd class="integral_goods_list_item_title" :title="v.goods_name">{{v.goods_name}}</dd>
                                    <dd class="integral_goods_list_item_price"><el-icon><Coin /></el-icon> {{v.goods_price}} <em>市场价:{{v.goods_market_price}}</em></dd>
                                    <dd class="integral_goods_list_item_btn">积分兑换</dd>
                                </dl>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="integral_goods_list w1200" v-for="(v,k) in data.list" :key="k">
                <div class="integral_goods_list_title">
                    {{v.name}}<span><router-link :to="'/integral/search'">更多 >></router-link></span>
                </div>
                <div class="integral_goods_list_item">
                    <ul>
                        <li v-for="(vo,key) in v.integral_goods" :key="key">
                            <router-link :to="'/integral/goods/info/'+vo.id">
                                <dl>
                                    <dt><img :src="vo.goods_master_image" /></dt>
                                    <dd class="integral_goods_list_item_title" :title="vo.goods_name">{{vo.goods_name}}</dd>
                                    <dd class="integral_goods_list_item_price"><el-icon><Coin /></el-icon> {{vo.goods_price}} <em>市场价:{{vo.goods_market_price}}</em></dd>
                                    <dd class="integral_goods_list_item_btn">积分兑换</dd>
                                </dl>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {reactive,computed,getCurrentInstance} from "vue"
import { Coin  } from '@element-plus/icons'
import banner from '@/components/home/banner'
import {useStore} from 'vuex'
export default {
    components: {Coin,banner},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const routeUriIndex = store.state.load.routeUriIndex
        const data = reactive({
            recommend:[],
            list:[],
            userInfo:computed(()=>store.state.login.userData[routeUriIndex]),
        })
        
        const isLogin = computed( () => store.state.login.loginData[routeUriIndex].isLogin )

        const loadData = ()=>{
            proxy.R.get('/integral/home').then(res=>{
                if(!res.code){
                    data.recommend = res.recommend
                    data.list = res.list
                }
            })
        }

        loadData()

        return {
            isLogin,
            data
        }
    },
  
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
        ul:after{
            clear:both;
            display: block;
            content:'';
        }
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
                img{
                    width: 100%;
                }
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
                
                em{
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
    &:after{
        display: block;
        clear: both;
        content:'';
    }
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
            img{
                width: 70px;
                height: 70px;  
            }
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
                img{
                   border-radius: 50%; 
                }
            }
            .integral_user_avatar .img{
                border-radius: 50%;
            }
            .myintegral{
                line-height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                i{
                    margin-right: 5px;
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
                img{
                    width: 40px;
                    height: 40px;
                }
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