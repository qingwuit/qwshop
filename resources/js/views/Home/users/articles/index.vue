<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                <span style="float:right;font-size:12px;color:#999;padding-right:10px;padding-left:20px;line-height:22px">编辑时间：{{data.info.updated_at}}</span>
                <span style="float:right;font-size:12px;color:#999;padding-right:20px;line-height:22px;border-right:1px solid #efefef;">点击量：{{data.info.click}}</span>
                {{data.info.name||'帮助中心'}}
            </div>
            <div class="x20"></div>
            
            <div v-html="editorHandle(data.info.content)"></div>
        </div>

    </div>
</template>

<script>
import {reactive,getCurrentInstance,watch} from "vue"
import {useRoute} from "vue-router"
import {editorHandle} from '@/plugins/config'
export default {
    setup(props) {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const data = reactive({
            info:{}
        })

        const loadData = ()=>{
            proxy.R.get('/article/'+route.params.name).then(res=>{
                if(!res.code) data.info = res
            })
        }

        loadData()

        watch(()=>route.params.name,(e)=>{
            if(!e) return
            loadData()
        })

        return {
            data,editorHandle
        }
    }
}
</script>

<style scoped>
.user_main{
    min-height: 650px;
}
</style>