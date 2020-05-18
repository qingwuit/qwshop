<template>
    <div class="user_content_blcok">
        <div class="user_content_blcok_title">
            账号绑定
        </div>
        <div class="user_content_blcok_line"></div>

        <div class="user_safe">
            <div class="user_safe_item">
                <div class="user_safe_icon">
                    <i class="font iconfont success">&#xe73b;</i>
                </div>
                <div class="user_safe_title">
                    微信绑定
                    <p>绑定后可直接使用微信登录，方便快捷。</p>
                </div>
                <div class="user_safe_btn">
                    <div @click="$router.push('/')">{{is_bind?'修改绑定':'立即绑定'}}</div>
                </div>
                <div class="clear"></div>
            </div>

        </div>

                
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          user_info:{},
          is_bind:false, // 是否认证
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        get_user_info:function(){
            this.$get(this.$api.homeGetUserInfo).then(res=>{
                this.user_info = res.data;
            })
        },
        get_user_check_info:function(){
            this.$get(this.$api.homeGetUserCheckInfo).then(res=>{
                if(res.code == 200){
                    this.is_check = true;
                }
            })
        },
    },
    created() {
        this.get_user_info();
        this.get_user_check_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_safe{
    .user_safe_item{
        border-bottom: 1px solid #efefef;
        .user_safe_icon{
            line-height: 90px;
            margin-right: 40px;
            margin-left: 15px;
            float: left;
            i{
                font-size: 34px;
                color: #ca151e; //42b983  ca151e
                
            }
            i.success{
                color: #42b983;
            }
        }
        .user_safe_title{
            float: left;
            font-size: 16px;
            font-weight: bold;
            padding-top: 20px;
            line-height: 25px;
            p{
                font-size: 14px;
                color:#666;
                font-weight: normal;
            }
        }
        .user_safe_btn{
            float: right;
            margin-top: 28px;
            margin-right: 15px;
            div{
                border: 1px solid #efefef;
                width: 100px;
                line-height: 30px;
                background: #fff;
                text-align: center;
            }
            div:hover{
                border-color:#ca151e;
                color:#ca151e;
            }
        }
    }
}

</style>