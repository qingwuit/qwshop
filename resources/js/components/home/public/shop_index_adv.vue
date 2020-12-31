<template>
    <div class="shop_index_adv width_center_1200">
        <img width="1200px" height="120px" v-lazy="adv.adv_image"   />
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        custom:{
            type:Boolean,
            default:false,
                
        },
        adv:{
            type:Object,
            default:()=>{
                return {adv_image:require('@/asset/pc/adv.jpg'),adv_link:'',adv_title:'加载中...'};
            }
        }
    },
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {
        
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.shop_index_adv{
    margin-top:40px;
    margin-bottom:40px;
}
</style>