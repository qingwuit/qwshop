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
                    <li>|</li>
                    <li><router-link style="color:#ca151e" to="/"><img width="16px" :src="require('@/asset/order/address_pos3.png')" >{{' '+city+' '||' - '}}</router-link></li>
                </ul>
            </div>
            <div class="top_shop_right">
                <ul>
                    <li v-show="!isLogin"><router-link to="/user/login">登录</router-link></li>
                    <li v-show="!isLogin"><router-link to="/user/register">注册</router-link></li>
                    <li v-show="isLogin">欢迎您，<router-link to="/user" style="color:#ca151e">{{userInfo.nickname}}</router-link></li>
                    <li v-show="isLogin">|</li>
                    <li v-show="isLogin"><router-link to="/user" >个人中心</router-link></li>
                    <li v-show="isLogin">|</li>
                    <li v-show="isLogin" @click="logout()">注销账号</li>
                    <li>|</li>
                    <li><router-link to="/store/join/index" style="color:#ca151e">商家入驻</router-link></li>
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
window.VueAMap.initAMapApiLoader({
  // 高德key
  key: 'f7619d49a4aea5cb76631ce884ea1817',
  // 插件集合 （插件按需引入）
  plugin: ['AMap.Geolocation', 'AMap.ToolBar','AMap.Geocoder']
});
import {mapState} from 'vuex'
export default {
    components: {},
    props: {},
    data() {
      return {
          city:'',
          amapUrl:'',
      };
    },
    watch: {},
    computed: {...mapState('homeLogin',['isLogin','userInfo'])},
    methods: {
        logout:function(){
            this.$get(this.$api.homeLogout).then(()=>{
                localStorage.removeItem('token');
                this.$store.dispatch('homeLogin/clear_login');
                this.$router.push('/user/login');

            });
        },
        get_position(){
            // 修改地址
            var self = this;
            window.VueAMap.lazyAMapApiLoaderInstance.load().then(() => {
               
                AMap.plugin('AMap.Geolocation',  ()=> {
                    var geolocation  = new AMap.Geolocation({timeout: 5000});
                    geolocation.getCurrentPosition()
                    AMap.event.addListener(geolocation, 'complete', onComplete)
                    AMap.event.addListener(geolocation, 'error', onError)
                    // 定位成功
                    function onComplete (data) {
                        // console.log(data)
                        if(data.addressComponent !=undefined && data.addressComponent.city!=undefined){
                            let city = data.addressComponent.city
                            let lng = data.position.lng
                            let lat = data.position.lat
                            self.city = city;
                            sessionStorage.setItem('qw_city',city);
                            sessionStorage.setItem('qw_lng',lng);
                            sessionStorage.setItem('qw_lat',lat);
                        }else{
                            console.log('定位异常.')
                            self.city = '北京市';
                            sessionStorage.setItem('qw_city','北京市');
                            sessionStorage.setItem('qw_lng','116.20');
                            sessionStorage.setItem('qw_lat','39.56');
                        }
                        
                    }
                    // 定位出错
                    function onError (data) {
                        console.log('定位失败.')
                        console.log(data)
                        self.city = '北京市';
                        sessionStorage.setItem('qw_city','北京市');
                        sessionStorage.setItem('qw_lng','116.20');
                        sessionStorage.setItem('qw_lat','39.56');
                    }
                })
            });
            //判断是否支持 获取本地位置
            // let _this = this;
            // if (navigator.geolocation) {
            //     var n = navigator.geolocation.getCurrentPosition(function(res){
            //         let lat = res.coords.latitude;
            //         let lng = res.coords.longitude;
            //         console.log(_this.amapUrl+lng+','+lat)
            //         _this.$get(_this.amapUrl+lng+','+lat).then(item=>{
            //             // console.log(item)
            //         })
            //         // console.log(res); // 需要的坐标地址就在res中
            //     });
            // } else {
            //     this.$message.error('该浏览器不支持定位');
            // }
        }
    },
    created() {
        this.get_position();
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