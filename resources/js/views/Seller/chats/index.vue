<template>
    <!-- 聊天 -->
    <base-view>
        <template #main_view>
            <chat ref="chat" :closeVis="false" :params="data.chatParams" />
        </template>
    </base-view>
</template>

<script>
import {reactive,onMounted,nextTick,getCurrentInstance} from "vue"
import Chat from "@/components/common/chat"
import baseView from "@/components/common/base"
export default {
    components:{baseView,Chat},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            chatParams:{provider:'users',rid:null,rtype:null,token:localStorage.getItem('seller_token')}
        })

        onMounted(()=>{
            proxy.$refs.chat.openChat()
        })

        return {
            data,
        }
    }
}
</script>

<style lang="scss" scoped>
.chat_main{
    height: 600px;
}
</style>

