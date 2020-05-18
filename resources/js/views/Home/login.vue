<template>
    <div class="shop_login">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div class="shop_login_bg" :style="'background:url('+login_adv+')'">
            <div class="shop_login_block width_center_1200">
                <div class="login_left"><router-link to="/">&nbsp;</router-link></div>
                <div class="login_right">
                    <div class="login_block">
                        <div class="login_title">
                            <ul>
                                <li class="colors">帐号登录</li>
                                <li>|</li>
                                <li>扫码登录</li>
                            </ul>
                        </div>
                        <div class="login_input">
                            <div class="input_block"><input type="text" @keyup.enter.native="to_login" v-model="info.phone" placeholder="手机号"></div>
                            <div class="input_block"><input type="password" @keyup.enter.native="to_login" v-model="info.password" placeholder="密码"></div>
                            <div class="input_block" v-show="error_num>=5"><input v-model="info.code" @keyup.enter.native="to_login" type="text" class="yzm" placeholder="短信验证码"><input @click="send_sms" class="send" type="button" value="发送验证码"></div>
                        </div>
                        <div class="login_btn" @click="to_login">
                            <router-link to="#">登录</router-link>
                        </div>

                        <div class="login_btn_b">
                            <router-link to="/user/register">立即注册</router-link> | <router-link to="/user/forget_password">忘记密码？</router-link>
                        </div>

                        <el-divider>其他登录方式</el-divider>
                        <div class="other_login">
                            <ul>
                                <li @click="wechat_login"><i class="icon iconfont" style="color:#50b674">&#xe73b;</i></li>
                                <li><i class="icon iconfont" style="color:#06b4fd">&#xe60b;</i></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <shop-foot></shop-foot>
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
          login_adv:"https://x.dscmall.cn/storage/data/afficheimg/1564453243544166182.jpg",
          error_num:0,
          info:{
              phone:'',
              password:'',
              code:'',
          },
          oauth:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 发送短信
        send_sms:function(){
            if(this.$isEmpty(this.info.phone)){
                return this.$message.error('请先填写手机号码');
            }
            this.$post(this.$api.homeSendSms,{phone:this.info.phone,is_type:2}).then(res=>{
                if(res.code == 200){
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        to_login:function(){
            this.error_num = this.get_error_num();
            if(this.$isEmpty(this.info.phone) || this.$isEmpty(this.info.password)){
                return this.$message.error('账号密码不能为空');
            }
            if(this.error_num>=5 && this.$isEmpty(this.info.code)){
                return this.$message.error('请填写短信验证码');
            }
            this.$post(this.$api.homeLogin,this.info).then(res=>{
                if(res.code == 500){
                    localStorage.setItem('login_error_num',parseInt(this.error_num)+1);
                    return this.$message.error(res.msg);
                }else{
                    this.$message.success('登录成功');
                    localStorage.setItem('token',res.token);
                    localStorage.setItem('user_info',JSON.stringify(res.user_info));
                    localStorage.removeItem('login_error_num');
                    this.$router.push('/');
                }
            });
        },
        // 获取本地存储登录失败次数
        get_error_num:function(){
            let error_num = localStorage.getItem('login_error_num');
            if(this.$isEmpty(error_num)){
                error_num = 0;
            }
            return error_num;
        },
        wechat_login:function(){
            location.href="https://open.weixin.qq.com/connect/qrconnect?appid="+this.oauth.appid+"&redirect_uri=%2fuser%2fwechat_login&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"
        },
        // 获取配置数据
        get_oauth_config:function(){
            this.$get(this.$api.homeGetOauthConfig).then(res=>{
                this.oauth = res.data;
            });
        }
        
    },
    created() {
        this.error_num = this.get_error_num(); // 获取登录失败次数;
        this.get_oauth_config();
        // console.log(localStorage.getItem('user_info'));
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.login_left{
    float: left;
    width: 50%;
    box-sizing: border-box;
    height: 550px;
}
.login_right{
    float: right;
    width: 50%;
    box-sizing: border-box;
    .login_block{
        width: 400px;
        background: #fff;
        height: 450px;
        float: right;
        box-sizing: border-box;
        padding: 40px;
        margin-top:50px;
        .login_title{
            width: 217px;
            margin:0 auto;
            ul:after{
                clear:both;
                display:block;
                content:'';
            }
            ul li{
                float: left;
                margin-right: 20px;
                font-size: 20px;
                color:#444;
                
            }
            ul li.colors{
                color:#ca151e;
            }
            ul li:hover{
                color:#ca151e;
            }
            ul li:last-child{
                margin-right: 0;
            }
        }
        .login_input{
            margin-top: 30px;
            .input_block{
                margin:15px auto;
                width: 320px;
                input{
                    height: 35px;
                    width: 320px;
                    border:1px solid #e1e1e1;
                    outline:none;
                    padding:0 10px;
                    box-sizing: border-box;
                }
                input.yzm{
                    width: 160px;
                    float: left;
                }
                input.send{
                    background: #333;
                    color:#fff;
                    width: 140px;
                    margin-left: 20px;
                    border: none;
                }
            }
        }
        .login_btn{
            color:#fff;
            background: #ca151e;
            width: 100%;
            height: 40px;
            line-height: 40px;
            text-align: center;
            a{
                color:#fff;
            }
        }
        .el-divider__text{
            color:#999;
        }
        .login_btn_b{
            text-align: right;
            font-size: 12px;
            color:#666;
            line-height: 45px;
            a{
                color:#666;
            }
            a:hover{
                color:#ca151e;
            }
        }
        .other_login{
            width: 82px;
            margin:0 auto;
            ul li{
                float: left;
                margin-right: 20px;
                i{
                    font-size: 28px;
                }
            }
            ul li:last-child{
                margin-right: 0;
            }
        }
        
    }
}
</style>