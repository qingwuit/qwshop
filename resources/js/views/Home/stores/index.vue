<template>
    <div>
        <Banner height="420" :banner="[{image:require('@/assets/Home/store_join_banner.jpg').default,name:'#'}]" />
        <div class="store_join w1200">
            <div class="store_join_btn float_left">
                <h4>入驻申请</h4>
                <p>未填写入驻申请资料时进行入驻资料填写</p>
                <div class="btn" @click="nextStep">我要入驻</div>
            </div>
            <div class="store_join_btn float_right">
                <h4>入驻进度</h4>
                <p>店铺还未开通时了解店铺开通的最新状况</p>
                <div class="btn" @click="nextStep">查看入驻进度</div>
            </div>
            <div class="clear"></div>
            <div class="join_title">
                <el-divider style="margin-bottom:5px;"><em style="font-size:26px;">入驻流程</em></el-divider>
                <p>ADVANCE REGISTRATION PROCESS</p>
            </div>
            <div class="step">
                <div class="item check"><el-icon :size="16"><Reading /></el-icon>阅读协议</div>
                <div class="item"><el-icon :size="16"><List /></el-icon>填写资料</div>
                <div class="item"><el-icon :size="16"><SetUp /></el-icon>等待审核</div>
                <div class="item"><el-icon :size="16"><CircleCheckFilled /></el-icon>审核通过</div>
            </div>
            <div class="classes">
                <div class="item">家用电器</div>
                <div class="item">手机、数码、通信</div>
                <div class="item">家居、家具、家装、厨具</div>
                <div class="item">男装、女装、内衣</div>
                <div class="item">鞋靴、箱包、钟表、奢侈品</div>
                <div class="item">个人化妆、清洁用品</div>
                <div class="item">增值服务 & 其他</div>
                <div class="item">其他</div>
                <div class="clear"></div>
            </div>
            
        </div>
    </div>
    
</template>

<script>
import Banner from '@/components/home/banner'
import { Reading,CircleCheckFilled,List,SetUp } from '@element-plus/icons'
import {reactive,onMounted,getCurrentInstance} from "vue"
import router from '@/plugins/router'
export default {
    components: {Banner,Reading,CircleCheckFilled,List,SetUp},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            info:{}
        })
        onMounted(async ()=>{
            data.info = await proxy.R.get('/store')
        })
        const nextStep = ()=>{
            if(data.info.store_verify > 1){
                router.push('/store/step_3')
            }else{
                router.push('/store/step_1')
            }
        }
        return {
            data,
            nextStep
        }
    }
};
</script>
<style lang="scss" scoped>
.classes{
    font-size: 16px;
    width: 100%;
    margin:0 auto;
    .item{
        text-align: center;
        width: 270px;
        margin-right: 30px;
        float: left;
        border:1px solid #efefef;
        line-height: 50px;
        margin-bottom: 30px;
        cursor: pointer;
        display: block;
        box-sizing: border-box;
        &:hover{
            background: #333;
            color:#fff;
        }
        &:nth-child(4){
            margin-right: 0px;
        }
    }
}
.step{
    height: 46px;
    line-height: 46px;
    background: #F5F7FA;
    margin-bottom: 50px;
    display: flex;
    .item{
        flex: 1;
        font-size: 16px;
        color:#C0C4CC;
        // float: left;
        // width: 25%;
        text-align: center;
        border-right: 4px solid #fff;
        justify-content: center;
        align-items: center;
        display: flex;
        i{
            margin-right: 10px;
        }
        &.check{
            color:#333;
            font-weight: bold;
        }
        &:last-child{
            margin-right: 0px;
        }
    }
}
.join_title{
    width:310px;
    text-align: center;
    margin:85px auto 50px auto;
    p{
        font-size: 12px;
        color: #999;
        margin-top: 15px;
    }
}
.store_join_btn{
    box-sizing: border-box;
    width: 50%;
    border-right: 1px solid #efefef;
    padding:30px 0;
    text-align: center;
    h4{
        line-height: 40px;
        font-size: 22px;
        font-weight: normal;
    }
    p{
        color: #999;
        line-height: 40px;
        font-size: 16px;
    }
    .btn{
        color: #fff;
        width: 200px;
        line-height: 40px;
        margin: 30px auto 0 auto;
        background: #f70;
        font-size: 16px;
        cursor: pointer;
    }
    &:nth-child(2){
        border-right: none;
    }
}
</style>