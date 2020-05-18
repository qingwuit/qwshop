<template>
    <div class="shop_register">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        <div class="shop_login_bg" :style="'background:url('+login_adv+')'">
            <div class="shop_login_block width_center_1200">
                <div class="login_left"><router-link to="/">&nbsp;</router-link></div>
                <div class="login_right">
                    <div class="login_block">
                        <div class="login_title">
                            <ul>
                                <li @click="$router.push('/user/register')">注册账号</li>
                                <li>|</li>
                                <li @click="$router.push('/user/login')">账号登录</li>
                            </ul>
                        </div>
                        <div class="login_input">
                            <div class="input_block"><input v-model="info.phone" type="text" placeholder="手机号"></div>
                            <div class="input_block"><input v-model="info.password" type="password" placeholder="新密码"></div>
                            <div class="input_block"><input v-model="info.password_comp" type="password" placeholder="确认密码"></div>
                            <div class="input_block"><input v-model="info.code" type="text" class="yzm" placeholder="短信验证码"><input class="send" type="button" @click="send_sms()" value="发送验证码"></div>
                        </div>
                        <div class="login_btn" @click="to_register">
                            修改密码
                        </div>

                        <div class="login_btn_b">
                            <router-link to="/user/register">没有账号？</router-link>
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
          info:{
              phone:'',
              password:'',
              inviter_id:0,
              password_comp:'',
              code:'',
          }
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
            this.$post(this.$api.homeSendSms,{phone:this.info.phone,is_type:5}).then(res=>{
                if(res.code == 200){
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        to_register:function(){
            if(this.$isEmpty(this.info.phone) || this.$isEmpty(this.info.password) || this.$isEmpty(this.info.password_comp) || this.$isEmpty(this.info.code)){
                return this.$message.error('请先认真填写完整');
            }
            if(this.info.password != this.info.password_comp){
                return this.$message.error('两次密码不相同');
            }

            this.$post(this.$api.homeForgetPaasword,this.info).then(res=>{
                if(res.code == 200){
                    this.$message.success(res.msg);
                    this.$router.push('/user/login');
                }else{
                    return this.$message.error(res.msg);
                }
            });
        }

    },
    created() {
        
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
            ul li:hover{
                color:#ca151e;
            }
            ul li.colors{
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