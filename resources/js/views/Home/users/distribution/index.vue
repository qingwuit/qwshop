<template>
    <div class="user_main table_lists">
        <div class="block_title">
            分销链接
        </div>
        <div class="x20"></div>
        <div class="user_safe">
            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <img :src="require('@/assets/Home/link.png').default" alt="">
                </div>
                <div class="user_safe_title">
                    邀请注册链接
                    <p >{{data.userInfo.link}}</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="copy()" class="inviter_link" :data-clipboard-text="data.userInfo.link">复制链接</div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="qrcode">
                <div class="title">推广公众号二维码：</div>
                <div class="myimg img_width"><qrcode-vue :value="data.userInfo.id" :size="320" class="qrcode_class" /></div>
            </div>
        </div>
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import QrcodeVue from 'qrcode.vue'
import Clipboard from 'clipboardjs'
import { useStore } from 'vuex'
export default {
    components:{QrcodeVue},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()

        let url = window.location.protocol+"//"+window.location.host

        const data = reactive({
            userInfo:{}
        })
       

        const loadData = async ()=>{
            let user = await store.dispatch('login/getUserSer')
            data.userInfo = user
            data.userInfo.link = url+'/register?inviter_id='+user.id
        }

        const copy = ()=>{
            var clipboard = new Clipboard('.inviter_link');
            clipboard.on('success', () => {  
                proxy.$message.success(proxy.$t('msg.success'));//这里你如果引入了elementui的提示就可以用，没有就注释即可
                  // 释放内存  
                  clipboard.destroy()  
            });
            clipboard.on('error', () => { 
                proxy.$message.success(proxy.$t('msg.error'));
                // 不支持复制  
                // 释放内存  
                clipboard.destroy()  
            }); 
            
        }

        onMounted( async ()=>{
            loadData()
        })

        return {data,copy}
    }
}
</script>
<style lang="scss" scoped>
.user_safe{
    .qrcode{
        margin-top:20px;
        .myimg{
            margin-top: 20px;
            margin-left: 20%;
        }
        .img_width{
            width: 150px;
        }
    }
    .user_safe_item{
        border-bottom: 1px solid #efefef;
        .user_safe_icon{
            line-height: 90px;
            margin-right: 40px;
            margin-left: 15px;
            float: left;
            img{width: 34px;}
        }
        .user_safe_title{
            float: left;
            font-size: 16px;
            font-weight: bold;
            padding-top: 20px;
            line-height: 25px;
            p{
                font-size: 14px;
                color:#999;
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