<template>
    <div class="adv" :style="{marginTop:'40px',marginBottom:'40px'}">
        <router-link :to="adv.url||'#'"><el-image lazy :src="adv.image||''" :style="'height:'+height+';width:'+width+';'" fit="cover"></el-image></router-link>
    </div>
</template>

<script>
export default {
    props:{
        width:{
            type:String,
            default:'100%'
        },
        height:{
            type:String,
            default:'100%'
        },
        adv:{
            type:Object,
            default:()=>{
                return {}
            }
        }
    }
}
</script>
