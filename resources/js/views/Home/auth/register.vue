<template>
    <div class="home_login" :style="'background: url('+ require('@/asset/login/user_login__bgs.png') +');'">
        <div class="login_block w1200">
            <div class="login_item">
                <div class="login_title">
                    <ul>
                        <li class="red">注册账号</li>
                        <li>|</li>
                        <li @click="$router.push('/user/login')">账号登录</li>
                    </ul>
                </div>
                <div class="login_input">
                    <div class="input_block"><input type="text" v-model="username" placeholder="手机" @keyup.enter="register"></div>
                    <div class="input_block"><input type="password" v-model="password" placeholder="密码" @keyup.enter="register"></div>
                    <div class="input_block"><input type="password" v-model="re_password" placeholder="确认密码" @keyup.enter="register"></div>
                    <div class="input_block">
                        <input class="yzm" type="code" v-model="code" placeholder="验证码" @keyup.enter="register">
                        <span :class="math>0?'yzmbtn dis':'yzmbtn'" @click="send_sms">{{code_text}}</span>
                    </div>
                </div>

                <div class="login_btn" @click="register">注 册</div>

                <div class="login_btn_b">
                    <router-link to="/user/forget_password">忘记密码？</router-link>
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
          re_password: "",
          code: "",
          code_text:'发送验证码',
          timeObj:null,
          math:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 登录
        register: function() {
            // 重新赋值vm使 axios可用vue实例
            var vm = this;

            if (this.username == "" || this.password == "") {
                this.$message.error("用户名和密码不能为空！");
                return;
            }

            this.$post(this.$api.homeRegister, {
                phone: this.username,
                password: this.password,
                re_password: this.re_password,
                code: this.code
            }).then(function(res) {
                if (res.code == 200) {
                    // console.log(res);
                    // 存储用户的token
                    localStorage.setItem("token", res.data.token);
                    vm.$store.dispatch('homeLogin/login',res);
                    vm.$message.success('注册成功！');
                    vm.$router.push({ name: "home_user_default" });
                }else{
                    vm.$message.error(res.msg);
                }
            });
        },
        // 发送短信
        send_sms(){
            if(this.username == ''){
                return this.$message.error('手机不能为空.');
            }
            if(this.math>0){
                return this.$message.error('不要频繁发送短信.');
            }

            // 发送
            this.$get(this.$api.homeSendSms,{phone:this.username,name:'register'}).then(res=>{
                if(res.code == 200){
                    this.math = 60;
                    this.timeObj = setInterval(()=>{
                        this.math--;
                        this.code_text = this.math+'s'
                        if(this.math<=0){
                            this.code_text = '发送验证码'
                            clearInterval(this.timeObj);
                        }
                    },1000);
                }
                return this.$returnInfo(res);
            })

            
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
                .yzmbtn{
                    cursor: pointer;
                    width: 140px;
                    height: 35px;
                    line-height: 35px;
                    display: inline-block;
                    text-align: center;
                    background: #333;
                    color:#fff;
                    &.dis{
                        background: #ccc;
                        color:#666;
                    }
                }
                input{
                    width: 100%;
                    border:1px solid #e1e1e1;
                    height: 35px;
                    text-indent: 6px;
                    outline: none;
                    &.yzm{
                        width: 50%;
                        margin-right: 5%;
                    }
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