<template>
    <div class="home_login" :style="'background: url('+ require('@/asset/login/user_login__bgs.png') +');'">
        <div class="login_block w1200">
            <div class="login_item">
                <div class="login_title">
                    <ul>
                        <li class="red">帐号登录</li>
                        <li>|</li>
                        <li>扫码登录</li>
                    </ul>
                </div>
                <div class="login_input">
                    <div class="input_block"><input type="text" v-model="username" placeholder="手机" @keyup.enter="login"></div>
                    <div class="input_block"><input type="password" v-model="password" placeholder="密码" @keyup.enter="login"></div>
                </div>

                <div class="login_btn" @click="login">登 录</div>

                <div class="login_btn_b">
                    <router-link to="/user/register">注册</router-link>|<router-link to="/user/forget_password">忘记密码？</router-link>
                </div>

                <a-divider>其他登录方式</a-divider>
                <div class="other_login_block" @click="wechat_login()">
                    <a-font type="iconweixin" />
                    <p>微信登录</p>
                </div>
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
          username: "",
          password: "",
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 登录
        login: function() {
            // 重新赋值vm使 axios可用vue实例
            var vm = this;

            if (this.username == "" || this.password == "") {
                this.$message.error("用户名和密码不能为空！");
                return;
            }

            this.$post(this.$api.homeLogin, {
                phone: this.username,
                password: this.password
            }).then(function(res) {
                if (res.code == 200) {
                    // console.log(res);
                    // 存储用户的token
                    localStorage.setItem("token", res.data.token);
                    vm.$store.dispatch('homeLogin/login',res);
                    vm.$message.success('登录成功！');
                    vm.$router.push({ name: "home_user_default" });
                }else{
                    vm.$message.error(res.msg);
                }
            });
        },
        wechat_login(){
            window.location.href="/api/oauth/weixinweb"
        }
    },
    created: function() {
        var _this = this;
        // 判断token是否失效
        this.$get(this.$api.homeCheckLogin).then(function(res) {
            // console.log(res);
            if (res.code == 200) {
                _this.$router.push({ name: "home_user_default" });
            }
        });
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.login_block{
    text-align: right;
    .login_btn_b{
        text-align: right;
        font-size: 12px;
        margin-top: 15px;
        margin-bottom: 40px;
        a{
            margin:0 8px;
            color:#999;
        }
    }
    .other_login_block{
        cursor: pointer;
        text-align: center;
        line-height: 30px;
        i{
            font-size: 30px;
        }
    }
    .login_item{
        width: 400px;
        background: #fff;
        height: 450px;
        float: right;
        box-sizing: border-box;
        padding: 40px;
        margin-top: 50px;
        text-align: left;
        .login_btn{
            cursor: pointer;
            color:#fff;
            background: #ca151e;
            line-height: 35px;
            width: 100%;
            text-align: center;
            font-size: 16px;
        }
        .login_input{
            margin-top: 30px;
            .input_block{
                margin: 15px auto;
                width: 320px;
                input{
                    width: 100%;
                    border:1px solid #e1e1e1;
                    height: 35px;
                    text-indent: 6px;
                    outline: none;
                }
            }
        }
        .login_title{
            ul{
                margin-left: 55px;
                &:after{
                    content:'';
                    display: block;
                    clear:both;
                }
            }
            ul li{
                cursor: pointer;
                float: left;
                margin-right: 20px;
                font-size: 20px;
                &.red{
                    color:#ca151e;
                }
                &:hover{
                    color:#ca151e;
                }
            }
        }
    }
}
.home_login{
    height: 550px;
}
</style>