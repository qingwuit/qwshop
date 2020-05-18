<template>
    <div class="user_index">
        <div class="user_info_balance">
            <ul>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe8cc;</i></dt>
                    <dd>账号余额</dd>
                    <dd class="money">256 元</dd>
                </dl></li>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe629;</i></dt>
                    <dd>账号积分</dd>
                    <dd class="money">256 积分</dd>
                </dl></li>
                <li><dl>
                    <dt><i class="icon iconfont">&#xe68e;</i></dt>
                    <dd>用户等级</dd>
                    <dd class="money">V1</dd>
                </dl></li>
            </ul>
        </div>
        <div class="user_info_order_num_show">
            <ul>
                <li><router-link to="/">
                    <el-badge :value="12" class="item">
                    <i class="icon iconfont">&#xe617;</i>
                    <span>待支付</span>
                    </el-badge>
                </router-link></li>
                <li><router-link to="/">
                    <el-badge :value="12" class="item">
                    <i class="icon iconfont">&#xe63b;</i>
                    <span>待发货</span>
                    </el-badge>
                </router-link></li>
                <li><router-link to="/">
                    <el-badge :value="12" class="item">
                    <i class="icon iconfont">&#xe61d;</i>
                    <span>待收货</span>
                    </el-badge>
                </router-link></li>
                <li><router-link to="/">
                    <el-badge :value="12" class="item">
                    <i class="icon iconfont">&#xe610;</i>
                    <span>待评论</span>
                    </el-badge>
                </router-link></li>
                <li><router-link to="/">
                    <el-badge :value="12" class="item">
                    <i class="icon iconfont">&#xe64c;</i>
                    <span>售后处理</span>
                    </el-badge>
                </router-link></li>
            </ul>
        </div>
        <div class="user_index_myorder">
            
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {},
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_info_balance{
    ul li{
        background: #fff;
        width: 302px;
        margin-right: 20px;
        float: left;
        dl{
            padding:20px;
        }
        dl:after{
            content:'';
            display: block;
            clear: both;
        }
        dl dt{
            color:#fff;
            background: #409eff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            line-height: 60px;
            text-align: center;
            margin-right: 20px;
            float: left;
            i{
                font-size: 24px;
            }
        }
        dl dd{
            margin-top: 8px;
        }
        dl dd.money{
            font-size: 14px;
            color:#666;
            margin-top: 5px;
        }
    }
    ul li:nth-child(2) dl dt{
        background: #e6a23c;
    }
    ul li:nth-child(3) dl dt{
        background: #f56c6c;
    }
    ul li:last-child{
        margin-right: 0;
    }
    ul:after{
        content:'';
        display: block;
        clear: both;
    }
}
.user_info_order_num_show{
    background: #fff;
    padding:20px;
    font-size: 14px;
    height: 30px;;
    margin-top: 20px;
    ul li{
        float: left;
        text-align: center;
        width: 180px;
        i{
            font-size: 22px;
            margin-right: 6px;
        }
    }
    ul li:last-child{
        margin-right: 0;
    }
}
</style>