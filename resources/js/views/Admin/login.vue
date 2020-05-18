<template>
    <div class="login_bg">
        <div class="login_bg3">
            <div class="login_bg2">
                <div class="login_hg_left">
                    <img :src="require('@/public/login/login_bg.png')" >
                </div>
                <div class="login_black_hg">
                    <div class="head_log">
                        后台管理系统
                        <p>ELECTRICITY SYSTEM</p>
                    </div>
                    <div class="unline" style="margin-bottom:30px;"></div>
                    <div class="form-group">
                        <el-input
                            size="small"
                            v-model="username"
                            @keyup.enter.native="login"
                            placeholder="用户名"
                        ></el-input>
                    </div>
                    <div class="form-group">
                        <el-input
                            type="password"
                            size="small"
                            v-model="password"
                            @keyup.enter.native="login"
                            class="login_input"
                            placeholder="密码"
                        ></el-input>
                    </div>
                    <div class="form-group" style="font-size:12px;">
                        <el-checkbox name="isCheck" v-model="isCheck" label="1">
                            <font style="font-size:13px;">我已同意</font>
                        </el-checkbox>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="must_rad" href="/index.php/Admin/Test/mustRead">《系统用户使用协议》</a>（必读）
                    </div>
                    <el-button size="mini" class="login_btn" @click="login" type="primary">登 录</el-button>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    name: "login",
    data() {
        return {
            username: "",
            password: "",
            isCheck: true
        };
    },
    methods: {
        // 登录
        login: function() {
            // 重新赋值vm使 axios可用vue实例
            var vm = this;
            if (!this.isCheck) {
                this.$message.error("请先认真阅读本站协议！");
                return;
            }

            if (this.username == "" || this.password == "") {
                this.$message.error("用户名和密码不能为空！");
                return;
            }

            this.$post(this.$api.login, {
                username: this.username,
                password: this.password
            }).then(function(res) {
                if (res.code == 200) {
                    // console.log(res);
                    // 存储用户的token
                    localStorage.setItem("token", res.token);
                    localStorage.setItem('user_info',JSON.stringify(res.user_info));
                    vm.$message({ message: "登录成功！", type: "success" });
                    vm.$router.push({ name: "admin_default" });
                }
            });
        }
    },
    created: function() {
        var _this = this;
        // 判断token是否失效
        this.$get(this.$api.checkUserLogin).then(function(res) {
            // console.log(res);
            if (res.code == 200) {
                _this.$router.push({ name: "admin_default" });
            }
        });
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.login_bg {
    background-position: center;
    background-size: 100%;
    height: 100%;
    background-color: #f1f1f1;
    background-image: linear-gradient(to right, #5d80fe , #56c9ff);
}
.head_log{
    text-align: center;
    line-height: 20px;
    font-size: 30px;
}
.head_log p{
    color:#666;
    line-height: 40px;
    font-size: 12px;
    margin-bottom: 10px;
}
.login_bg2{
    position: relative;
    top: 50%;
    width: 1000px;
    margin: 0 auto;
    /* margin-top: -250px; */
    display: block;
}
.login_bg3{
    height: 100%;
    background: url('/login/bg.png');
    background-position: top right;
    background-repeat: no-repeat;
}
.login_bg2:after{
    display: block;
    content:'';
    clear: both;
}
.login_black_hg {
    background: #fff;
    /* width: 400px; */
    border-radius: 6px;
    padding: 0px 40px 20px 40px;
    box-sizing: border-box;
    -moz-box-sizing: border-box; /* Firefox */
    -webkit-box-sizing: border-box; /* Safari */
    padding-top: 40px;
    float: left;
    position: absolute;
    right: 30px;
    top:-180px;
}
.login_hg_left{
    float: left;
    position: absolute;
    left: -180px;
    top:-390px;
    
}
.login_input {
    margin: 20px 0 20px 0;
}

.login_btn {
    margin-top: 20px;
    width: 100%;
    font-size: 14px;
}
.must_rad {
    font-size: 12px;
}
</style>

