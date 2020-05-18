<template>
    <div class="seckill width_center_1200">
        <div class="seckill_title">商城秒杀<!-- <span><router-link to="/">查看更多</router-link></span> --></div>
        <div class="seckill_left">
            <div class="seckill_end_time">
                {{info.name}}
                <p><i class="icon iconfont">&#xe60c;</i></p>
                <p class="font_skill">距离{{fontSkill}}还有</p>
                <ul>
                    <li>{{hour}}</li>
                    <li>{{min}}</li>
                    <li>{{secs}}</li>
                </ul>
            </div>
        </div>
        <div class="seckill_right">
                <ul>
                    <li v-for="(v,k) in list" :key="k"><router-link :to="'/goods/info/'+v.goods.id">
                        <dl>
                            <dt><el-image style="width: 180px; height: 180px" :src="v.goods.image" :alt="v.goods.goods_name" lazy></el-image></dt>
                            <dd class="product_title" :title="v.goods_name">{{v.goods.goods_name}}</dd>
                            <dd class="product_subtitle">{{v.goods.goods_subname}}</dd>
                            <dd class="product_price">￥{{v.goods.goods_price}}<span>{{v.goods.goods_market_price}}</span></dd>
                        </dl>
                    </router-link></li>
                    
                </ul>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        list:{
            type:Array,
        },
        info:{
            type:Object,
        }
    },
    data() {
      return {
          hour:0,
          min:0,
          secs:0,
          intvalobj:null,
          fontSkill:'开始',
      };
    },
    watch: {
        
    },
    computed: {},
    methods: {
        
    },
    created() {
        this.intvalobj = setInterval(()=>{
            let start_time = this.info.start_time;
            let end_time = this.info.end_time;
            let timestamp = Date.parse(new Date())/1000;
            if(start_time>timestamp){
                let sec = start_time-timestamp;
                this.hour = Math.floor(sec/3600);
                this.min = Math.floor((sec-(this.hour*3600))/60);
                this.secs = sec-(this.hour*60+this.min)*60;
            }else if(end_time>timestamp){
                let sec2 = end_time-timestamp;
                this.hour = Math.floor(sec2/3600);
                this.min = Math.floor((sec2-(this.hour*3600))/60);
                this.secs = sec2-(this.hour*60+this.min)*60;
                this.fontSkill = '结束';
            }else{
                this.hour = 0;
                this.min = 0;
                this.secs = 0;
                clearInterval(this.intvalobj);
            }
        },1000);
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.seckill{
    .seckill_left{
        width: 234px;
        height: 340px;
        box-sizing: border-box;
        background: #f1eded;
        border-top: 1px solid #ca151e;
        float: left;
        // box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .seckill_end_time{
            padding-top:45px;
            text-align: center;
            color:#ca151e;
            font-size: 24px;
            p{
                padding-top: 60px;
            }
            p.font_skill{
                font-size: 12px;
                color:#666;
                padding-top: 30px;
                padding-bottom: 30px;
            }
            i{
                font-size: 40px;
            }
            ul{
                margin-left: 45px;
            }
            ul li{
                float: left;
                width: 40px;
                height: 40px;
                background: #333;
                color:#fff;
                margin-right: 10px;
                text-align: center;
                line-height: 40px;
            }
        }
        
    }
    .seckill_left:after{
        display: block;
        content:'';
        clear:both;
    }
    .seckill_title{
        font-size: 22px;
        margin-bottom: 20px;
        span{
            float:right;
            font-size: 14px;
            padding-right: 15px;
        }
        a:hover{
            color:#ca151e;
        }
    }
    .seckill_right{
        float: left;
        ul li{
            width: 220px;
            height: 340px;
            background: #fff;
            margin-left: 20px;
            float: left;
            box-sizing: border-box;
            -webkit-transition: all .2s linear;
            transition: all .2s linear;
            dl dt{
                width: 180px;
                height: 180px;
                margin:0 auto;
                margin-top: 20px;
            }
            dl dt img{
                width: 180px;
                height: 180px;
            }
            dl dd{
                width: 220px;
                overflow: hidden;
                text-align: center;
                width: 190px;
                margin:0 auto;
            }
            dl dd.product_title{
                font-size: 14px;
                margin-top: 25px;
                height: 30px;
                line-height: 30px;
            }
            dl dd.product_subtitle{
                margin-top: 0px;
                font-size: 12px;
                color:#b0b0b0;
                line-height: 14px;
            }
            dl dd.product_price{
                margin-top: 10px;
                font-size: 16px;
                color:#ca151e;
                line-height: 34px;
                span{
                    font-size: 14px;
                    color:#b0b0b0;
                    margin-left: 8px;
                    text-decoration: line-through;
                }
            }
        }
        ul li:hover{
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
            margin-top:-3px;
        }
        ul li:nth-child(1){
            border-top:1px solid #00c0a5;
        }
        ul li:nth-child(2){
            border-top:1px solid #ffac13;
        }
        ul li:nth-child(3){
            border-top:1px solid #83c44e;
        }
        ul li:nth-child(4){
            border-top:1px solid #2196f3;
        }
    }
}
</style>