<template>
    <div class="user_main table_lists">
        <div class="block_title">
            用户资料
        </div>
        <div class="x20"></div>
        <div class="safe_block" >
            <ul>
                <li>
                    <div class="safe_icon "><img :src="require('@/assets/Home/safe_pwd.png').default" alt=""></div>
                    <div class="safe_text">登陆密码<p>互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。</p></div>
                    <div class="safe_btn" @click="$router.push('/user/safe/password')">修改</div>
                </li>
                <li>
                    <div class="safe_icon "><img :src="require('@/assets/Home/safe_pay.png').default" alt=""></div>
                    <div class="safe_text">支付密码<p>安全认证，保证余额。</p></div>
                    <div class="safe_btn" @click="$router.push('/user/safe/pay_password')">修改</div>
                </li>
                <li>
                    <div class="safe_icon "><img :src="require('@/assets/Home/safe_tel.png').default" alt=""></div>
                    <div class="safe_text">手机认证<p>您的手机已验证，若已丢失或停用，请立即更换，避免账户被盗。</p></div>
                    <div class="safe_btn" @click="$router.push('/user/safe/phone')">修改</div>
                </li>
                <li>
                    <div class="safe_icon"><img :src="data.userInfo.check?require('@/assets/Home/safe_card.png').default:require('@/assets/Home/safe_card2.png').default" alt=""></div>
                    <div class="safe_text">身份认证<p>您还未实名认证该账户，立即实名认证可加快提现速度。</p></div>
                    <div class="safe_btn" @click="$router.push('/user/safe/check')">{{data.userInfo.check?'查看认证':'立即认证'}}</div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
export default {
    components:{},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()

        const data = reactive({
            userInfo:{
                check:false,
            }
        })
       

        const loadData = async ()=>{
            let user = await store.dispatch('login/getUserSer')
            data.userInfo = user
        }

        onMounted( async ()=>{
            loadData()
        })

        return {data}
    }
}
</script>
<style lang="scss" scoped>
.safe_block{
    min-height: 600px;
    li{clear:both;border-bottom: 1px solid #f1f1f1;}
    ul li:after{
        clear:both;
        display: block;
        content:'';
    }
    &:after{
        clear:both;
        display: block;
        content:'';
    }
    .safe_icon{
        line-height: 90px;
        margin-right: 40px;
        margin-left: 15px;
        margin-top: 10px;
        float: left;
        img{
            text-align: center;
            margin-top: 20px;
        }
    }
    .safe_text{
        float: left;
        font-size: 16px;
        font-weight: bold;
        padding-top: 20px;
        line-height: 25px;
        p{
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
    }
    .safe_btn{
        border: 1px solid #efefef;
        width: 100px;
        line-height: 30px;
        background: #fff;
        text-align: center;
        float: right;
        margin-top: 28px;
        margin-right: 15px;
        cursor: pointer;
        &:hover{
            color:#ca151e;
            border-color: #ca151e;
        }
    }
}
</style>