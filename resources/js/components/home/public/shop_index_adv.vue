<template>
    <div class="shop_index_adv width_center_1200">
        <el-image width="1200px" :src="adv.adv_image" alt="" lazy></el-image>
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
                return {adv_image:'http://127.0.0.1:8000/Uploads/adv/2019_11_14/15737142019047.jpg',adv_link:'',adv_title:'加载中...'};
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