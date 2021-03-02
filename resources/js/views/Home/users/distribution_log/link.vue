<template>
         
    <div class="distruction">
        <div class="user_main">
            <div class="block_title">
                分销链接
            </div>
            <div class="x20"></div>
            <div class="user_safe">
                <div class="user_safe_item">
                    <div class="user_safe_icon">
                        <a-icon type="link" />
                    </div>
                    <div class="user_safe_title">
                        邀请注册链接
                        <p >{{info.link}}</p>
                    </div>
                    <div class="user_safe_btn">
                        <div @click="copy()" class="inviter_link" :data-clipboard-text="info.link">复制链接</div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="qrcode">
                    <div class="title">推广公众号二维码：</div>
                    <div class="myimg img_width"><img :src="info.qrcode" /></div>
                </div>

                <!-- <div class="qrcode">
                    <div class="title">推广海报：</div>
                    <div class="myimg"><el-image :src="info.poster" lazy></el-image></div>
                </div> -->

            </div>

        </div>
        
    </div>
</template>

<script>
import Clipboard from 'clipboard';  
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{
              link:'*************',
              qrcode:'',
              poster:'',
              user_id:0,
          },
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        get_info:function(){
            this.$get(this.$api.homeDistributionLink).then(res=>{
                this.info.qrcode = res.data.qrcode;
                // this.info.poster = res.data.poster;
                // this.info.user_id = res.data.user_id;
                this.info.link = res.data.link;
            })
        },
        copy:function(){
            var clipboard = new Clipboard('.inviter_link');
            clipboard.on('success', () => {  
                this.$message.success("复制成功");//这里你如果引入了elementui的提示就可以用，没有就注释即可
                  // 释放内存  
                  clipboard.destroy()  
            });
            clipboard.on('error', () => { 
                this.$message.error("不支持"); 
                // 不支持复制  
                // 释放内存  
                clipboard.destroy()  
            }); 
            
        },
        
    },
    created() {
        
        this.get_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_safe{
    .qrcode{
        margin-top:20px;
        .myimg{
            margin-top: 20px;
            margin-left: 20%;
        }
        .img_width{
            width: 150px;
        }
    }
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
                color: #ca151e;
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
                color:#999;
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