<template>
    <div class="home">
        <base-view>
            <template #main>
                <head-view />
                <router-view></router-view>
            </template>
        </base-view> 
    </div>
</template>

<script>
import baseView from "@/components/home/base"
import headView from "@/components/home/head"

import {reactive,getCurrentInstance} from "vue"
export default {
    components:{baseView,headView},
}
</script>

<style lang="scss" scoped>
.index_bg{
    background: #f5f5f5;
    padding: 20px 0 60px 0;
    margin-top: 40px;
}
</style>