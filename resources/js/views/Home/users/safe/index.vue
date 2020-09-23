<template>
    <div class="safe">
        <div class="user_main">
            <div class="block_title">
                账户安全
            </div>
            <div class="x20"></div>
            <div class="safe_block" >
                <ul>
                    <li>
                        <div class="safe_icon success"><a-font type="iconmima" /></div>
                        <div class="safe_text">登陆密码<p>互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。</p></div>
                        <div class="safe_btn" @click="$router.push('/user/safe/edit_password')">修改</div>
                    </li>
                    <li>
                        <div class="safe_icon success"><a-font type="iconalipay" /></div>
                        <div class="safe_text">支付密码<p>安全认证，保证余额。</p></div>
                        <div class="safe_btn" @click="$router.push('/user/safe/edit_pay_password')">修改</div>
                    </li>
                    <li>
                        <div class="safe_icon success"><a-font type="iconshouji" /></div>
                        <div class="safe_text">手机认证<p>您的手机已验证，若已丢失或停用，请立即更换，避免账户被盗。</p></div>
                        <div class="safe_btn" @click="$router.push('/user/safe/edit_phone')">修改</div>
                    </li>
                    <li>
                        <div :class="user_info.user_check?'safe_icon success':'safe_icon'"><a-font type="iconnamecard" /></div>
                        <div class="safe_text">身份认证<p>您还未实名认证该账户，立即实名认证可加快提现速度。</p></div>
                        <div class="safe_btn" @click="$router.push('/user/safe/edit_check')">{{user_info.user_check?'查看认证':'立即认证'}}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
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
    mounted() {}
};
</script>
<style lang="scss" scoped>
.safe_block{
    min-height: 600px;
    li{clear:both;border-bottom: 1px solid #f1f1f1;}
    ul li:after{
        clear:both;
        display: block;
        content:'';
    }
    &:after{
        clear:both;
        display: block;
        content:'';
    }
    .safe_icon{
        line-height: 90px;
        margin-right: 40px;
        margin-left: 15px;
        float: left;
        i{
            font-size: 24px;
            color:#ca151e;
        }
        &.success i{
            color: #42b983;
        }
    }
    .safe_text{
        float: left;
        font-size: 16px;
        font-weight: bold;
        padding-top: 20px;
        line-height: 25px;
        p{
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
    }
    .safe_btn{
        border: 1px solid #efefef;
        width: 100px;
        line-height: 30px;
        background: #fff;
        text-align: center;
        float: right;
        margin-top: 28px;
        margin-right: 15px;
        cursor: pointer;
        &:hover{
            color:#ca151e;
            border-color: #ca151e;
        }
    }
}
</style>