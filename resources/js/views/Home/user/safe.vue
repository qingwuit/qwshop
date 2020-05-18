<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title">
            账户安全
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="user_safe">
            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i class="font iconfont success">&#xe646;</i>
                </div>
                <div class="user_safe_title">
                    登录密码
                    <p>互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="$router.push('/user/safe/password')">修改</div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i class="font iconfont success">&#xeb90;</i>
                </div>
                <div class="user_safe_title">
                    支付密码
                    <p>安全认证，保证余额。</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="$router.push('/user/safe/pay_password')">修改</div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i :class="'font iconfont'+(user_info.email ==''?'':' success')">&#xe6eb;</i>
                </div>
                <div class="user_safe_title">
                    邮箱认证
                    <p>验证后，可用于快速找回登录密码，接收账户余额变动提醒等。</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="check_email">{{user_info.email==''?'立即验证':'修改邮箱'}}</div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i :class="'font iconfont'+(user_info.phone ==''?'':' success')">&#xe650;</i>
                </div>
                <div class="user_safe_title">
                    手机认证
                    <p>您的手机已验证，若已丢失或停用，请立即更换，避免账户被盗</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="check_phone">{{user_info.phone==''?'立即验证':'修改手机'}}</div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i :class="'font iconfont'+(is_check ==''?'':' success')">&#xeb9a;</i>
                </div>
                <div class="user_safe_title">
                    身份认证
                    <p>您还未实名认证该账户，立即实名认证可加快提现速度。</p>
                </div>
                <div class="user_safe_btn">
                    <div  @click="$router.push('/user/safe/card')">{{is_check==''?'立即认证':'修改认证'}}</div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <!-- 手机验证 -->
        <el-dialog title="手机验证" :visible.sync="phoneCheck">
            <div class="user_safe_phone">
                <label>手机号码:</label>
                <input type="text" v-model="phone">
                <div class="clear"></div>
            </div>
            <div class="user_safe_phone">
                <label>验证码:</label>
                <input class="code" type="text" v-model="code">
                <div class="user_phone_send" @click="send_sms()">{{send_msg}}</div>
                <div class="clear"></div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="phoneCheck = false">取 消</el-button>
                <el-button type="danger" @click="phone_check()">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 邮箱验证 -->
        <el-dialog title="邮箱验证" :visible.sync="emailCheck">
            <div class="user_safe_phone">
                <label>邮箱号码:</label>
                <input type="text" v-model="email">
                <div class="clear"></div>
            </div>
            <div class="user_safe_phone">
                <label>验证码:</label>
                <input class="code" type="text" v-model="code">
                <div class="user_phone_send" @click="send_email()">{{send_msg}}</div>
                <div class="clear"></div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="emailCheck = false">取 消</el-button>
                <el-button type="danger" @click="email_check()">确 定</el-button>
            </span>
        </el-dialog>

                
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          user_info:{},
          is_check:false, // 是否认证
          phoneCheck:false,
          emailCheck:false,
          phone:'',
          code:'',
          email:'',
          send_msg:'发送验证码',
          auto_num:0,
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        get_user_info:function(){
            this.$get(this.$api.homeGetUserInfo).then(res=>{
                this.user_info = res.data;
            })
        },
        get_user_check_info:function(){
            this.$get(this.$api.homeGetUserCheckInfo).then(res=>{
                if(res.code == 200){
                    this.is_check = true;
                }
            })
        },
        check_phone:function(){
            this.phoneCheck = true;
        },
        check_email:function(){
            this.emailCheck = true;
        },
        send_sms:function(){
            if(this.$isEmpty(this.phone)){
                return this.$message.error('请先填写手机号码');
            }

            if(this.auto_num>0){
                return this.$message.error('等等待60s后再发送');
            }
            this.$post(this.$api.homeSendSms,{phone:this.phone,is_type:3}).then(res=>{
                if(res.code == 200){
                    this.auto_num = 60;
                    let times = setInterval(()=>{
                        this.send_msg = this.auto_num+'s';
                        if(this.auto_num<=0){
                            this.send_msg = '发送验证码';
                            clearInterval(times);
                        }
                        this.auto_num -= 1;
                        
                    },1000);
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        // 手机验证
        phone_check:function(){
            this.$post(this.$api.homeEditUserInfo,{code:this.code,phone:this.phone}).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }
                this.$message.success('修改成功');
                // this.$router.go(-1);
                this.phoneCheck=false;
                this.get_user_info();
                this.get_user_check_info();
                
            });
        },
        send_email:function(){
            if(this.$isEmpty(this.email)){
                return this.$message.error('请先填写手机号码');
            }

            if(this.auto_num>0){
                return this.$message.error('等等待60s后再发送');
            }
            this.$post(this.$api.homeSendEmail,{email:this.email,is_type:4}).then(res=>{
                if(res.code == 200){
                    this.auto_num = 60;
                    let times = setInterval(()=>{
                        this.send_msg = this.auto_num+'s';
                        if(this.auto_num<=0){
                            this.send_msg = '发送验证码';
                            clearInterval(times);
                        }
                        this.auto_num -= 1;
                        
                    },1000);
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        // 邮箱验证
        email_check:function(){
            this.$post(this.$api.homeEditUserInfo,{code:this.code,email:this.email}).then(res=>{
                if(res.code == 500){
                    return this.$message.error(res.msg);
                }
                this.$message.success('修改成功');
                // this.$router.go(-1);
                this.emailCheck=false;
                this.get_user_info();
                this.get_user_check_info();
                
            });
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
.user_safe_phone{
    margin-bottom: 20px;
    label{
        margin-right: 15px;
        width: 90px;
        display: block;
        text-align: right;
        float: left;
        line-height: 30px;
    }
    input{
        outline: none;
        width: 220px;
        border:1px solid #efefef;
        border-radius: 3px;
        height: 30px;
        padding:0 5px;
        float: left;
    }
    input.code{
        width: 100px;
    }
    .user_phone_send{
        width: 110px;
        text-align: center;
        color:#fff;
        background: #333;
        line-height: 30px;
        float: left;
        margin-left: 10px;
    }
}
.user_safe{
    .user_safe_item{
        border-bottom: 1px solid #efefef;
        .user_safe_icon{
            line-height: 90px;
            margin-right: 40px;
            margin-left: 15px;
            float: left;
            i{
                font-size: 24px;
                color: #ca151e; //42b983  ca151e
                
            }
            i.success{
                color: #42b983;
            }
        }
        .user_safe_title{
            float: left;
            font-size: 16px;
            font-weight: bold;
            padding-top: 20px;
            line-height: 25px;
            p{
                font-size: 14px;
                color:#666;
                font-weight: normal;
            }
        }
        .user_safe_btn{
            float: right;
            margin-top: 28px;
            margin-right: 15px;
            div{
                border: 1px solid #efefef;
                width: 100px;
                line-height: 30px;
                background: #fff;
                text-align: center;
            }
            div:hover{
                border-color:#ca151e;
                color:#ca151e;
            }
        }
    }
}

</style>