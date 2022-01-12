<template>
    <div class="banner">
        <el-carousel :height="height+'px'">
            <el-carousel-item v-for="(v,k) in banner" :key="k">
                <div class="shop_banner" :style="'background-position: center;background-image:url('+v.image+');background-size: cover;'"><router-link :to="v.url||'#'"><div :style="'width:1200px;height:'+height+'px;display:block;'"></div></router-link></div>
            </el-carousel-item>
        </el-carousel>
    </div>
</template>

<script>
export default {
    props:{
        height:{
            type:String,
            default:'450'
        },
        banner:{
            type:Array,
            default:()=>{
                return []
            }
        }
    }
}
</script>

<style>

</style>