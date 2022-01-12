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
                    <li><router-link style="color:#ca151e;display:flex;align-items: center;" to="/"><img width="16" style="margin-top:4px;margin-right:4px;" :src="require('@/assets/order/address_pos3.png').default" >{{' '+data.city+' '||' - '}}</router-link></li>
                </ul>
            </div>
            <div class="top_shop_right">
                <ul>
                    <li v-show="!isLogin"><router-link to="/login">登录</router-link></li>
                    <li v-show="!isLogin"><router-link to="/register">注册</router-link></li>
                    <li v-show="isLogin">欢迎您，<router-link to="/" style="color:#ca151e">{{data.users.nickname||'-'}}</router-link></li>
                    <li v-show="isLogin">|</li>
                    <li v-show="isLogin"><router-link to="/user" >个人中心</router-link></li>
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
import { useStore } from 'vuex'
import {ref,reactive,watch,computed,onMounted,getCurrentInstance} from "vue"
export default {
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const routeUriIndex = store.state.load.routeUriIndex
        const data = reactive({
            users:computed( () => store.state.login.userData[routeUriIndex]),
            city:'北京市',
        })
        const isLogin = computed( () => store.state.login.loginData[routeUriIndex].isLogin )
        const amapKey = computed( () => store.state.init.common.common.amap.key )
        const ip = computed( () => store.state.init.common.ip )

        const logout = ()=>{
            store.commit('login/logout')
        }

        const loadCity = ()=>{
            const ipLocal = localStorage.getItem('ip')
            const city = localStorage.getItem('ip')
            if(ip.value == ipLocal && city) return
            localStorage.setItem('ip',ip.value)
            if(!navigator.geolocation) return console.log('geolocation not allow')
            if(!amapKey) return
            const amapUrl = 'https://restapi.amap.com/v3/geocode/regeo?parameters'
            const optionsMap = {
                enableHighAccuracy:true,
                timeout:10000,
                maximumAge:60000
            }
            navigator.geolocation.getCurrentPosition((position)=>{
                var x = position.coords.longitude+''
                var y = position.coords.latitude+''
                x = x.substr(0,7)
                y = y.substr(0,7)
                console.log("经度为:"+x+"纬度为:"+y)
                proxy.R.get(amapUrl,{key:amapKey.value,location:x+','+y}).then(res=>{
                    if(res.status == 0) return
                    data.city = res.regeocodes.formatted_address.addressComponent.city
                    localStorage.setItem('city',data.city)
                    localStorage.setItem('lonlat',x+','+y)
                })
            },(err)=>{
                let errorTypes={
                    1:"用户拒绝定位服务",
                    2:"获取不到定位信息",
                    3:"获取定位信息超时"
                }
                console.error(errorTypes[err.code]);
            },optionsMap)
            
        }

        onMounted(()=>{
            setTimeout(()=>{
                loadCity()
            },300)
        })

        return {
            isLogin,
            data,
            logout
        }
    },
   
    
};
</script>
<style lang="scss" scoped>
.head{
  border-bottom: 1px solid #efefef;
  height: 30px;
  background: #f9f9f9;
  font-size: 12px;
  line-height: 30px;
  position: fixed;
  z-index: 666;
  width: 100%;

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