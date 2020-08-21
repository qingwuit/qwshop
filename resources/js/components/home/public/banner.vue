<template>
    <div class="banner">
        <a-carousel v-if="list.length<=0" ><div :style="'background:#efefef;height:'+height+'px;display:block;width:100%'"></div></a-carousel>
        <a-carousel v-if="list.length>0" autoplay>
            <div  v-for="(v,k) in list" :key="k" class="shop_banner" :style="'background-position: center;background-image:url('+v.image_url+');'"><router-link :to="v.url||'#'"><div :style="'width:1200px;height:'+height+'px;display:block;'"></div></router-link></div>
        </a-carousel>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        list:{
            type:Array,
            default:[],
        },
        height:{
            type:Number,
            default:450,
        },
    },
    data() {
        return{}
    },
    watch: {},
    computed: {},
    methods: {},
    created() {},
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>