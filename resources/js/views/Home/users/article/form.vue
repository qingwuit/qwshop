<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                <span style="float:right;font-size:12px;color:#999;padding-right:10px;padding-left:20px;line-height:22px">编辑时间：{{info.updated_at}}</span>
                <span style="float:right;font-size:12px;color:#999;padding-right:20px;line-height:22px;border-right:1px solid #efefef;">点击量：{{info.click_num}}</span>
                {{info.name||'帮助中心'}}
            </div>
            <div class="x20"></div>
            
            <div v-html="info.content"></div>


        </div>

    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
             click_num:0, 
             updated_at:'0000-00-00 00:00:00', 
          },
          ename:'',
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_info(){
            this.$get(this.$api.homeArticle+'/'+this.ename).then(res=>{
            if(res.code == 200){
                this.info = res.data;
            }else if(res.code==401){
                this.$router.push('/user/login');
            }else{
                return this.$router.go(-1);
            }
        })
        }
    },
    created() {
        this.ename = this.$route.params.ename;
        this.get_info();
    },
    mounted() {},
    beforeRouteUpdate (to, from, next) {
        console.log(to,from);
        if(from.params.ename != to.params.ename){
            this.ename = to.params.ename;
            this.get_info();
            document.documentElement.scrollTop = 0
        }
        next();
        // react to route changes...
        // don't forget to call next()
    }
};
</script>
<style lang="scss" scoped>

</style>