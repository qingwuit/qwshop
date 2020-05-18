<template>
    <div class="left_store_info">
        <div class="store_info">
            <div class="store_info_title">
                <div class="store_info_tag">店铺</div>
                <div class="store_info_name">{{store_info.store_name||'加载中...'}}</div>
            </div>
            <div class="store_start_all">
                <font>综合评分：</font><div class="store_star_in"><el-rate disabled :value ="parseFloat(store_info.store_score)"></el-rate></div><font color="#ca151e">{{store_info.store_score}}分</font>
            </div>
            <div class="store_star_info">
                <p>店铺评分：</p>
                <div class="other_star">
                    <font>描述相符</font><div class="store_star_in"><el-rate disabled :value ="parseFloat(store_info.store_agree)"></el-rate></div><div>{{store_info.store_agree}}分</div>
                </div>
                <div class="other_star">
                    <font>服务态度</font><div class="store_star_in"><el-rate disabled :value ="parseFloat(store_info.store_service)"></el-rate></div><div>{{store_info.store_service}}分</div>
                </div>
                <div class="other_star">
                    <font>发货速度</font><div class="store_star_in"><el-rate disabled :value ="parseFloat(store_info.store_speed)"></el-rate></div><div>{{store_info.store_speed}}分</div>
                </div>
            </div>
            <div class="store_info_company">
                <div class="company_nam">
                    公司名称：<font>{{store_info.store_company_name}}</font>
                </div>
                <div class="company_nam">
                    公司地址：<font>{{store_info.area_info+store_info.store_address}}</font>
                </div>
            </div>
            <div class="store_info_btn">
                <div class="store_coll" @click="to_store()">
                    进入店铺
                </div>
                <div class="store_coll">
                    联系店家
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        store_info:{
            type:Object,
            default:function(){
                return {store_name:'加载中...'}
            },
        }
    },
    data() {
      return {
      };
    },
    watch: {},
    computed: {},
    methods: {
        to_store:function(){
            this.$router.push('/store/'+this.store_info.user_id);
        }
    },
    created() {
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
  .store_info{
    margin-bottom: 15px;
    margin-top: 20px;
    border:1px solid #f1f1f1;
    .store_info_title{
        background: #fafafa;
        padding:5px 10px;
        border-bottom: 1px solid #efefef;
        .store_info_tag{
            font-size: 14px;
            background: #ca151e;
            color:#fff;
            width: 50px;
            text-align: center;
            line-height: 24px;
            border-radius: 3px;
            float: left;
            margin-right: 10px;
        }
        .store_info_name{
            line-height: 24px;
            font-size: 14px;
            width: 150px;
            height: 24px;
            overflow: hidden;
        }
    }
    .store_info_title:after{
        content:'';
        display: block;
        clear: both;
    }
    .store_start_all{
        height: 40px;
        font-size: 14px;
        color:#666;
        border-bottom: 1px solid #efefef;
        line-height: 40px;
        padding-left: 10px;
        font{
            float: left;
        }
        .store_star_in{
            float: left;
            margin-top: 10px;
        }
    }
    .store_start_all:after{
        content:'';
        display: block;
        clear: both;
    }
    .store_star_info{
        line-height: 40px;
        font-size: 14px;
        padding-left: 10px;
        border-bottom: 1px solid #efefef;
        .other_star{
            height: 30px;
            font-size: 14px;
            color:#666;
            line-height: 30px;
            font{
                float: left;
                margin-right: 10px;
            }
            .store_star_in{
                float: left;
                margin-top: 5px;
            }
        }
    }
    .store_info_company{
        color:#666;
        font-size: 14px;
        line-height: 20px;
        padding:0 10px;
        border-bottom: 1px solid #efefef;
        padding-bottom: 10px;
        .company_nam{
            margin-top:10px;
            font{
                color:#999;
            }
        }
    }
    .store_info_btn{
        color:#666;
        font-size: 14px;
        line-height: 20px;
        .store_coll{
            box-sizing: border-box;
            width: 50%;
            text-align: center;
            line-height: 40px;
            border-right: 1px solid #efefef;
            float: left;
            i{
                font-size: 16px;
            }
        }
        .store_coll:last-child{
            border-right: none;
        }
        .store_coll.red_color{
            color:#fff;
            background: #ca151e;
        }
        .store_coll:hover{
            color:#fff;
            background: #ca151e;
        }
    }
    .store_info_btn:after{
        content:'';
        display: block;
        clear: both;
    }
    
    
}
</style>