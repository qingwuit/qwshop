<template>
    <div class="user_default">
        <div class="user_default_in w1200">
            <!-- 用户左侧栏目 -->
            <div class="user_left">
                <div class="user_info_block">
                    <dl>
                        <dt><img :src="user_info.avatar||require('@/asset/user/user_default.png')" alt=""></dt>
                        <dd>{{user_info.nickname||'加载中...'}}</dd>
                        <dd class="edit_user_info"><router-link to="/user/user_info">编辑信息</router-link></dd>
                    </dl>
                    <div class="user_stepbar">
                        <span>账号资料：</span><a-progress class="progress" :percent="user_info.completion" size="small" stroke-color="#ca151e" />
                    </div>
                    <div class="user_safe">
                        <span>账户安全：</span>
                        <span class="safe_icon">
                            <a-font :class="user_info.phone!=''?'success':''" type="iconshouji"></a-font>
                            <a-font :class="user_info.user_check?'success':''" type="iconnamecard"></a-font>
                        </span>
                    </div>
                </div>

                <div class="user_nav">
                    <div class="block" v-for="(v,k) in nav" :key="k">
                        <div class="title">
                            <a-font :type="v.icon"></a-font>
                            <span>{{v.name}}</span>
                        </div>
                        <div class="nav_item" v-for="(vo,key) in v.children" :key="key"><router-link :to="vo.url">{{vo.name}}</router-link></div>
                    </div>
                </div>
            </div>

            <div class="user_right">
                <router-view></router-view>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
        return {
            nav:[
                {
                    name:'订单中心',
                    icon:'icondingdan',
                    children:[
                        {name:'我的订单',url:'/user/order'},
                        {name:'收货地址',url:'/user/address'},
                        {name:'评论列表',url:'/user/order_comments'},
                    ],
                },
                {
                    name:'会员中心',
                    icon:'iconchengyuan',
                    children:[
                        {name:'个人中心',url:'/user'},
                        {name:'用户资料',url:'/user/user_info'},
                        {name:'账户安全',url:'/user/safe'},
                        {name:'账号绑定',url:'/user/oauth'},
                        {name:'资金提现',url:'/user/cash'},
                        {name:'收藏/关注',url:'/user/favorite'},
                        {name:'我的优惠券',url:'/user/coupon'},
                    ],
                },
                {
                    name:'积分商城',
                    icon:'iconjifen',
                    children:[
                        {name:'积分订单',url:'/user/integral_order'},
                    ],
                },
                {
                    name:'资产记录',
                    icon:'iconyue',
                    children:[
                        {name:'平台余额',url:'/user/money'},
                        {name:'冻结资金',url:'/user/frozen_money'},
                        {name:'平台积分',url:'/user/integral'},
                    ],
                },
                {
                    name:'分销分佣',
                    icon:'iconVIP1',
                    children:[
                        {name:'分销信息',url:'/user/distribution'},
                        {name:'分销会员',url:'/user/distribution_users'},
                        {name:'分销佣金',url:'/user/distribution_logs'},
                    ],
                },
                {
                    name:'帮助中心',
                    icon:'iconbangzhu',
                    children:[
                        {name:'网站公告',url:'/user/article/notice'},
                        {name:'其他合作',url:'/user/article/cooperation'},
                        {name:'帮助中心',url:'/user/article/help'},
                        {name:'关于我们',url:'/user/article/about'},
                    ],
                },
            ],
            user_info:{},
        };
    },
    watch: {},
    computed: {},
    methods: {
        get_user_info(){
            this.$get(this.$api.homeUser+'/info').then(res=>{
                this.user_info = res.data;
            })
        },
    },
    created() {
        this.get_user_info();
    },
    mounted() {},
    
};
</script>
<style lang="scss" scoped>
.user_default{
    background: #f1f1f1;
    padding-bottom: 30px;
}
.user_left{
    float: left;
    width: 234px;
    margin-right: 20px;
    padding-top: 30px;
    .user_nav{
        margin-top: 20px;
        background: #fff;
        padding: 20px;
        .block{
            border-bottom: 1px solid #efefef;
            padding-bottom: 15px;
            margin-bottom: 15px;
            .nav_item{
                line-height: 35px;
                margin-left: 55px;
                cursor: pointer;
                color:#666;
                &:hover{
                    color:#ca151e;
                }
            }
            &:last-child{
                border-bottom: none;
            }
        }
        .title{
            font-size: 16px;
            margin-bottom: 10px;
            i{
                color:#ca151e;
                margin:0 6px 0 24px;
                font-size: 18px;
                font-weight: bold;
            }
        }
    }
    .user_info_block{
        background: #fff;
        padding: 20px;
        .user_stepbar{
            margin-top: 20px;
        }
        .user_safe{
            margin-top: 10px;
            i{
                margin-right: 6px;
            }
            .safe_icon .success{
                color:#67c23a;
            }
        }
        .progress{
            width: 120px;
        }
        dl{
            &:after{
                clear:both;
                display: block;
                content:'';
            }
            dt{
                float: left;
                margin-right: 15px;
                width: 60px;
                height: 60px;
                border: 1px solid #f4f4f4;
                border-radius: 50%;
                img{
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                }
            }
            dd{
                font-size: 14px;
                float: left;
                margin-top: 10px;
                &.edit_user_info{
                    border: 1px solid #ca151e;
                    line-height: 20px;
                    text-align: center;
                    padding: 0 15px;
                    margin-top: 5px;
                    a{
                        color: #ca151e;
                    }
                }
            }
        }
    }
}
.user_right{
    float: left;
    width: 946px;
    padding-top: 30px;
}
</style>