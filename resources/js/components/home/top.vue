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

        let localCity = localStorage.getItem('city')
        if(!proxy.R.isEmpty(localCity)) data.city = localCity
        
        const logout = ()=>{
            store.commit('login/logout')
        }

        const loadCity = ()=>{
            try{
                const ipLocal = localStorage.getItem('ip')
                const city = localStorage.getItem('city')
                if(ip.value == ipLocal && city) return
                localStorage.setItem('ip',ip.value)
                // if(!navigator.geolocation) return console.log('geolocation not allow')
                if(!amapKey) return
                const amapUrl = 'https://restapi.amap.com/v3/ip?parameters'
                proxy.R.get(amapUrl,{key:amapKey.value,ip:ip.value,type:4},true).then(res=>{
                    if(res.data.status == 0){
                        return console.log(res.data.info)
                    }
                    localStorage.setItem('city',res.data.city)
                    localStorage.setItem('lonlat',res.data.location)
                    data.city = res.data.city
                })
            }catch(error){
                console.error(error)
            }
        }

        onMounted(()=>{
            setTimeout(()=>{
                loadCity()
            },1000)
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