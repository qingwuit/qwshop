<template>
    <div class="home_default">
        <top />
        <div class="padding_content"></div>
        <slot name="main"></slot>
        <foot />
    </div>
</template>

<script>
import {onMounted} from "vue"
import { useStore } from 'vuex'
import top from "@/components/home/top"
import foot from "@/components/home/foot"
export default {
    components:{top,foot},
    setup() {
        const store = useStore()
        onMounted( async ()=>{
            await store.dispatch('init/loadCommon') // 加载网站数据
        })
    }
}
</script>

<style>
.padding_content{height: 228px;position:relative ;}
</style>