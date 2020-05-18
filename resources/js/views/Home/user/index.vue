<template>
    <div class="user_index">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div class="user_body">
            <div class="width_center_1200" style="padding-bottom:40px;">
                <div class="user_left">
                    <div class="user_info_block">
                        <dl>
                            <dt><img width="60px" height="60px" :src="user_info.avatar||'/pc/default_avatar.png'" alt=""></dt>
                            <dd style="width:90px;overflow:hidden;">{{user_info.nickname||'神秘人'}}</dd>
                            <dd class="edit_user_info"><router-link to="/user/user_info">编辑信息</router-link></dd>
                        </dl>
                        <div class="other_user_info_step">
                            <font>账号资料：</font> <div style="width:60%;float:left;"><el-progress :percentage="bfb" ></el-progress></div>
                            <div class="clear"></div>
                        </div>
                        <div class="other_user_info_step" style="margin-top:10px;">
                            <font>账户安全：</font> <div class="user_aq" style="width:60%;float:left;">
                                <el-tooltip class="item" effect="dark" content="邮箱认证" placement="bottom"><i :class="user_info.email==''?'icon iconfont':'icon iconfont font_color'">&#xe6eb;</i></el-tooltip>
                                <el-tooltip class="item" effect="dark" content="手机认证" placement="bottom"><i :class="user_info.phone==''?'icon iconfont':'icon iconfont font_color'">&#xe650;</i></el-tooltip>
                                <el-tooltip class="item" effect="dark" content="真实身份认证" placement="bottom"><i :class="is_check==''?'icon iconfont':'icon iconfont font_color'" class="icon iconfont">&#xeb9a;</i></el-tooltip>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="user_cat">
                        <div class="user_cat_title"><i class="icon iconfont">&#xe675;</i>订单中心</div>
                        <div class="user_cat_list">
                            <ul>
                                
                                <li><router-link to="/user/order">我的订单</router-link></li>
                                <li><router-link to="/user/address">收货地址</router-link></li>
                                <!-- <li><router-link to="/">退换货订单</router-link></li> -->
                                <li><router-link to="/user/comment/index">评论列表</router-link></li>
                            </ul>
                        </div>
                        <div class="user_cat_title"><i class="icon iconfont">&#xe601;</i>会员中心</div>
                        <div class="user_cat_list">
                            <ul>
                                <li><router-link to="/user/index">个人中心</router-link></li>
                                <li><router-link to="/user/user_info">用户资料</router-link></li>
                                <li><router-link to="/user/safe">账户安全</router-link></li>
                                <!-- <li><router-link to="/">实名认证</router-link></li> -->
                                <li><router-link to="/user/user_bind">账号绑定</router-link></li>
                                <li><router-link to="/user/fav">收藏/关注</router-link></li>
                                <!-- <li><router-link to="/">我的留言</router-link></li> -->
                            </ul>
                        </div>
                        <div class="user_cat_title"><i class="icon iconfont">&#xe629;</i>积分商城</div>
                        <div class="user_cat_list">
                            <ul>
                                <li><router-link to="/user/integral_order">积分订单</router-link></li>
                            </ul>
                        </div>
                        <div class="user_cat_title"><i class="icon iconfont">&#xe8cc;</i>资产记录</div>
                        <div class="user_cat_list">
                            <ul>
                                <li><router-link to="/user/get_money_log/money">平台余额</router-link></li>
                                <li><router-link to="/user/get_money_log/freeze_money">冻结余额</router-link></li>
                                <li><router-link to="/user/get_money_log/integral">平台积分</router-link></li>
                            </ul>
                        </div>

                        <div class="user_cat_title"><i class="icon iconfont">&#xe618;</i>分销分佣</div>
                        <div class="user_cat_list">
                            <ul>
                                <li><router-link to="/user/inviter/inviter_info">分销信息</router-link></li>
                                <li><router-link to="/user/inviter/inviter_member">分销会员</router-link></li>
                                <li><router-link to="/user/inviter/inviter_money">分销佣金</router-link></li>
                                <li><router-link to="/user/cash/index">资金提现</router-link></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="user_right">
                    <router-view></router-view>
                </div>

                <div class="clear"></div>
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
          user_info:{},
          bfb:0,
          is_check:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_user_info:function(){
            this.$get(this.$api.homeGetUserInfo).then(res=>{
                this.user_info = res.data;
                let arr = Object.keys(this.user_info);
                let len = arr.length; 
                let bfb = 0;
                arr.forEach(userRes=>{
                    if(userRes != ''){
                        bfb += 1;
                    }
                });
                this.bfb = Math.ceil(bfb/len)*100;
            });
        },
        get_user_check_info:function(){
            this.$get(this.$api.homeGetUserCheckInfo).then(res=>{
                if(res.code == 200){
                    this.is_check = true;
                }
            })
        },
    },
    created() {
        this.get_user_info();
        this.get_user_check_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_body{
    background: #f1f1f1;
}
.user_body:after{
    content:'';
    display: block;
    clear: both;
}
.user_left{
    float: left;
    width: 234px;
    margin-right: 20px;
    padding-top:30px;
    .user_info_block{
        background: #fff;
        padding: 20px;
        dl:after{
            content:'';
            display: block;
            clear: both;
        }
        dl dt img{
            border-radius: 50%;
            border:1px solid #efefef;
            box-sizing: border-box;
        }
        dl dt{
            float: left;
            margin-right: 15px;
            width: 60px;
            height: 60px;
            border:1px solid #f4f4f4;
            border-radius: 50%;
        }
        dl dd{
            font-size: 14px;
            float: left;
            margin-top: 10px;
        }
        dl dd.edit_user_info{
            border:1px solid #ca151e;
            line-height: 20px;
            text-align: center;
            color:#ca151e;
            padding: 0 15px;
            margin-top: 5px;
            a{
                color:#ca151e;
            }
        }
    }
    .user_info_block:after{
        content:'';
        display: block;
        clear: both;
    }
    .user_cat{
        margin-top: 20px;
        background: #fff;
        padding:20px;
        font-size: 14px;
        .user_cat_title{
            font-size: 16px;
            padding-left: 20px;
        }
        .user_cat_title i{
            margin-right: 10px;
            font-size: 14px;
            color:#ca151e;
        }
        .user_cat_list{
            padding-left: 45px;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 1px solid #efefef;
            ul li{
                margin: 10px 0;
                a{
                    color:#666;
                }
                a:hover{
                    color:#ca151e;
                }
            }
        }
        .user_cat_list:last-child{
            border-bottom: none;
        }
    }
    .other_user_info_step{
        margin-top:20px;
        clear: both;
        font{
            float: left;
            width: 40%;
            font-size: 14px;
        }
        .user_aq i{margin-right: 10px;margin-left: 5px;line-height: 20px;}
        .user_aq i.font_color{color:#67c23a}
    }
}
.user_right{
    float: left;
    width: 946px;
    padding-top:30px;
    
}
</style>