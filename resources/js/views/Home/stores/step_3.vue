<template>
    <div class="store_join w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item success"><el-icon :size="16"><Reading /></el-icon>阅读协议</div>
                <div class="item success"><el-icon :size="16"><List /></el-icon>填写资料</div>
                <div :class="{item:true,check:data.info.store_verify==2,success:data.info.store_verify==3 ||data.info.store_verify==4 }"><el-icon :size="16"><SetUp /></el-icon>等待审核</div>
                <div :class="{item:true,check:data.info.store_verify==3  ,success:data.info.store_verify==4}"><el-icon :size="16"><CircleCheckFilled /></el-icon>{{data.info.store_verify==3?"审核失败":"审核通过"}}</div>
            </div>
        </div>
        <el-result
            :icon="data.resultIcon"
            :title="data.resultText"
            :sub-title="data.subTitle"
        >
        </el-result>
        <div class="agreement_btn" v-if="data.info.store_verify==3"><el-button color="#e50e19" type="primary" @click="nextStep">入驻资料,前往修改</el-button></div>
        <div class="agreement_btn" v-if="data.info.store_verify==4"><el-button color="#e50e19" type="primary" @click="nextStep">店铺配置,登录店铺</el-button></div>
    </div>
</template>

<script>
import {reactive,onMounted,getCurrentInstance} from "vue"
import router from '@/plugins/router'
import { Reading,CircleCheckFilled,List,SetUp } from '@element-plus/icons'
export default {
    components: {Reading,CircleCheckFilled,List,SetUp},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            info:{},
            resultIcon:'success',
            subTitle:null,
            resultText:'审核成功，前往店铺设置',
        })
        onMounted( async ()=>{
            data.info = await proxy.R.get('/store')
            if(data.info.store_verify == 2){
                data.resultText = '等待管理员审核'
                data.resultIcon = 'warning'
            }
            if(data.info.store_verify == 3){
                data.resultText = '审核失败，请在工作人员协助下完善资料'
                data.subTitle = data.info.store_refuse_info
                data.resultIcon = 'error'
            }
            if(data.info.store_verify == 4){
                data.resultText = '审核成功，前往店铺设置'
                data.resultIcon = 'success'
            }
        })
        const nextStep = ()=>{
            router.push('/Seller/login')
        }
        return {nextStep,data}
    }
}
</script>

<style lang="scss" scoped>
.store_join{
    margin-top:40px;
}
.agreement_btn{
    margin:20px 0 10px 0;
    text-align: center;
}
.agreement_content{
    padding:20px;
    height: 600px;
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
        &.success{
            color:#67C23A;
            font-weight: bold;
        }
        &:last-child{
            margin-right: 0px;
        }
    }
}
</style>>

