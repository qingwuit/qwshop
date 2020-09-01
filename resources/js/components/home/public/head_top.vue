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
                    <li v-show="isLogin">欢迎您，<router-link to="/user/index" style="color:#ca151e">{{userInfo.nickname}}</router-link></li>
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
import {mapState} from 'vuex'
export default {
    components: {},
    props: {},
    data() {
      return {
          amapUrl:'https://restapi.amap.com/v3/geocode/regeo?&key=79f3a628c906e1fc7384a6f19d478ae3&location=',
      };
    },
    watch: {},
    computed: {...mapState('homeLogin',['isLogin','userInfo'])},
    methods: {
        logout:function(){
            this.$get(this.$api.homeLogout).then(()=>{
                localStorage.removeItem('token');
                this.$router.push('/user/login');

            });
        },
        get_position(){
            //判断是否支持 获取本地位置
            let _this = this;
            if (navigator.geolocation) {
                var n = navigator.geolocation.getCurrentPosition(function(res){
                    let lat = res.coords.latitude;
                    let lng = res.coords.longitude;
                    _this.$get(_this.amapUrl+lng+','+lat).then(item=>{
                        console.log(item)
                    })
                    // console.log(res); // 需要的坐标地址就在res中
                });
            } else {
                this.$message.error('该浏览器不支持定位');
            }
        }
    },
    created() {
        // this.get_position();
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