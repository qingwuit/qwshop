<template>
    <div class="head">
        <div class="head_in">
            <div class="top_shop_left">
                <ul>
                    <li><router-link to="/">青梧官网</router-link></li>
                    <li>|</li>
                    <li><router-link to="/">商城官网</router-link></li>
                    <li>|</li>
                    <li><router-link to="/">联系方式</router-link></li>
                    <li>|</li>
                    <li><router-link to="/">关于我们</router-link></li>
                </ul>
            </div>
            <div class="top_shop_right">
                <ul>
                    <li v-show="!isLogin"><router-link to="/user/login">登录</router-link></li>
                    <li v-show="!isLogin"><router-link to="/user/register">注册</router-link></li>
                    <li v-show="isLogin">欢迎您，<router-link to="/user/index" style="color:#ca151e">{{user_info.nickname}}</router-link></li>
                    <li v-show="isLogin">|</li>
                    <li v-show="isLogin"><router-link to="/user/index" >个人中心</router-link></li>
                    <li v-show="isLogin">|</li>
                    <li v-show="isLogin" @click="logout()">注销账号</li>
                    <li>|</li>
                    <li><router-link to="/store/join" style="color:#ca151e">商家入驻</router-link></li>
                    <li>|</li>
                    <li><router-link to="/">手机端</router-link></li>
                    <li>|</li>
                    <li><router-link to="/">APP下载</router-link></li>
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
          isLogin:false,
          user_info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        logout:function(){
            this.$get(this.$api.homeLogout).then(()=>{
                // console.log(res);
                localStorage.removeItem('user_info');
                localStorage.removeItem('token');
                this.$router.push('/user/login');

            });
        }
    },
    created() {
        let user_info = localStorage.getItem('user_info');
        if(!this.$isEmpty(user_info)){
            this.isLogin = true;
            this.user_info = JSON.parse(user_info);
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.head{
  border-bottom: 1px solid #efefef;
  height: 30px;
  background: #f9f9f9;
  font-size: 12px;
  line-height: 30px;

  .head_in{
      width: 1200px;
      margin:0 auto;
      .top_shop_left{
          float: left;
          ul li{
              float: left;
              padding:0 5px;
          }
      }
      .top_shop_right{
          float: right;
          ul li{
              float: left;
              padding:0 5px;
          }
          li:hover{
              color:#ca151e;
          }
      }
  }
  .head_in:after{
      display: block;
      clear: both;
      content:'';
  }
  .head_in a:hover{
      color:#ca151e;
  }
}
</style>