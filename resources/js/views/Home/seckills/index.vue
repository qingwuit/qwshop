<template>
    <div class="home_seckills">

        <div class="mbx w1200">
            <el-breadcrumb>
                <el-breadcrumb-item><a href="/">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item>秒杀现场</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <el-row justify="center" align="middle" :gutter="12">
                <el-col :span="6">
                    <div :class="data.timeIndex==0?'item ck':'item'" @click="timeChange(0)">
                        <span class="title">{{data.timeList[0]||'00'}}:00 场</span>
                        <span class="name">距离结束 {{data.timeFormat}}</span>
                    </div>
                </el-col>
                <el-col :span="6">
                    <div :class="data.timeIndex==1?'item ck':'item'" @click="timeChange(1)">
                        <span class="title">{{data.timeList[1]||'00'}}:00 场</span>
                        <span class="name">预约中</span>
                    </div>
                </el-col>
                <el-col :span="6">
                    <div :class="data.timeIndex==2?'item ck':'item'" @click="timeChange(2)">
                        <span class="title">{{data.timeList[2]||'00'}}:00 场</span>
                        <span class="name">预约中</span>
                    </div>
                </el-col>
                <el-col :span="6">
                    <div :class="data.timeIndex==3?'item ck':'item'" @click="timeChange(3)">
                        <span class="title">{{data.timeList[3]||'00'}}:00 场</span>
                        <span class="name">预约中</span>
                    </div>
                </el-col>
            </el-row>
        </div>
        <!-- 搜索条件 E -->

        <!-- 产品列表 S -->
        <div class="goods_list w1200" v-if="data.list.length>0">
            <ul>
                <li v-for="(v,k) in data.list" :key="k"><router-link :to="'/goods/'+v.id">
                    <div class="product_act_in">
                        <dl>
                            <dt><img :src="v.goods_master_image" :alt="v.goods_name" /></dt>
                            <dd class="product_title" :title="v.goods_name">{{v.goods_name}}</dd>
                            <dd class="product_subtitle">{{v.goods_subname||'-'}}</dd>
                            <dd class="product_price">￥{{v.goods_price}}<span>{{v.goods_market_price}}元</span></dd>
                        </dl>
                    </div>
                </router-link></li>
            </ul>
            <div class="clear"></div>
            <div class="fy" style="margin-top:30px">
                <el-pagination background 
                layout="total, prev, pager, next" 
                :page-size="data.params.per_page" 
                @current-change="handleCurrentChange"
                :page-count="data.params.last_page"
                :current-page="data.params.current_page"
                :total="data.params.total">
                </el-pagination>
            </div>
        </div>
        <a-empty v-else style="margin-top:250px" />
        <!-- 产品列表 E -->
    </div>
</template>

<script>
import {reactive,watch,onMounted,getCurrentInstance} from "vue"
import {useRouter} from 'vue-router'
import dayjs from "dayjs"
import {formatTime} from '@/plugins/config'
export default {
    components: {},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const router = useRouter()
        const data = reactive({
            timeList:[],
            timeFormat:' 00 : 00 : 00 ',
            timeIndex:0,
            list:[],
            params:{
                page:1,
                per_page:30,
                last_page:1,
                total:0,
                start_time:0,
            },
        })  

        const loadData = async ()=>{
            data.timeList = [dayjs().get('h'),dayjs().add(1,'hours').get('h'),dayjs().add(2,'hours').get('h'),dayjs().add(3,'hours').get('h')]
            timing()
            const resp = await proxy.R.get('/seckills',data.params)
            if(!resp.code){
                data.list = resp.data
                data.params.total = parseInt(resp.total)
                data.params.last_page = parseInt(resp.last_page)
                data.params.per_page = parseInt(resp.per_page)
                data.params.current_page = parseInt(resp.current_page)
            }
        }

        const timing = ()=>{
            let endTime = dayjs().add(1,'hours').format('YYYY-MM-DD HH')+':00:00'
            if(data.timeObj != null) clearInterval(data.timeObj)
            data.timeObj = setInterval(()=>{
                let timeVal = dayjs(endTime).diff(dayjs(),'s')
                data.timeFormat = formatTime(timeVal,false)// 时间戳转换
                if(dayjs(endTime).unix()<dayjs().unix()){
                    clearInterval(data.timeObj);
                    router.go(0);
                }
            },1000)
        }

        const timeChange = (e)=>{
            data.params.start_time = e;
            data.params.page = 1;
            data.timeIndex = e;
            loadData()
        }

        const handleCurrentChange = ()=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }

        loadData()
        return {
            data,timeChange,handleCurrentChange
        }
    },
    // props: {},
    // data() {
    //   return {
    //       timeList:[],
    //       timeFormat:' 00 : 00 : 00 ',
    //       timeIndex:0,
    //       list:[],
    //       params:{
    //           page:1,
    //           per_page:30,
    //           total:0,
    //           start_time:0,
    //       },
    //   };
    // },
    // watch: {},
    // computed: {},
    // methods: {
    //     onload(){
    //         this.timeList = [moment().format('H'),moment().add(1,'hours').format('H'),moment().add(2,'hours').format('H'),moment().add(3,'hours').format('H')]
    //         this.get_list();
    //     },
    //     get_list(){
    //         this.$get(this.$api.homeSeckill,this.params).then(res=>{
    //             this.params.total = res.data.total;
    //             this.list = res.data.data;
    //         })
    //     },
    //     onChange(e){
    //         this.params.page = e;
    //         this.onload();
    //     },
    //     timeChange(e){
    //         this.params.start_time = e;
    //         this.page = 1;
    //         this.timeIndex = e;
    //         this.onload();
    //     },
    //     // 定时器
    //     timing(){
    //         let endTime = moment().add(1,'hours').format('YYYY-MM-DD HH')+':00:00';
    //         let obj = setInterval(()=>{
    //             let timeVal = moment(endTime).format('X') - moment().format('X');
    //             // 时间戳转换
    //             var d = Math.floor(timeVal / (24 * 3600));
    //             var h = Math.floor((timeVal - 24 * 3600 * d) / 3600);
    //             var m = Math.floor((timeVal - 24 * 3600 * d - h * 3600) / 60);
    //             var s = Math.floor((timeVal - 24 * 3600 * d - h * 3600 - m * 60));
    //             // console.log(d + '天' + hh + '时' + mm + '分' + ss + '秒'); // 打印出转换后的时间
    //             //  当时分秒小于10的时候补0
    //             var hh = h < 10 ? '0' + h : h;
    //             var mm = m < 10 ? '0' + m : m;
    //             var ss = s < 10 ? '0' + s : s;
    //             // this.seckills.format_time =  d + '天' + hh + '时' + mm + '分' + ss + '秒';
    //             this.timeFormat = ' '+hh+' : '+mm+' : '+ss
    //             if(moment(endTime).valueOf()<moment().valueOf()){
    //                 clearInterval(obj);
    //                 this.$router.go(0);
    //             }
    //         },1000)
            
    //     },
    // },
    // created() {
    //     this.onload();
    //     this.timing();
    // },
    // mounted() {}
};
</script>
<style lang="scss" scoped>
.home_seckills{
    min-height: 600px;
    .goods_list{
        margin-top: 30px;
        ul li{
            width: 220px;
            height: 300px;
            margin-bottom: 14px;
            margin-right: 20px;
            box-sizing: border-box;
            &:nth-child(4n){
                margin-right: 0;
            }
            
            float: left;
            .product_act_in{
                width: 220px;
                height: 300px;
                background: #fff;
                box-sizing: border-box;
                -webkit-transition: all .2s linear;
                transition: all .2s linear;
                // background: #fafafa;
                border:1px solid #f1f1f1;
            }
            dl dt{
                width: 140px;
                height: 140px;
                margin:0 auto;
                padding-top: 20px;
            }
            dl dt img{
                width: 140px;
                height: 140px;
            }
            dl dd{
                width: 190px;
                overflow: hidden;
                text-align: center;
                margin:0 auto;
                line-height: 30px;
            }
            dl dd.product_title{
                font-size: 14px;
                margin-top: 30px;
                height: 30px;
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
        
        ul li:hover .product_act_in{
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
            margin-top:-3px;
        }
    }
    .goods_where{
        border: none;
        line-height: 50px;
        font-size: 14px;
        .item{
            cursor: pointer;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            // text-align: center;
            .title{
                font-size: 20px;
                font-weight: bold;
                line-height: 50px;
                margin-right: 10px;
                color:#666;
            }
            .name{
                font-size: 14px;
                line-height: 50px;
                color:#666;
            }
            &.ck{
                background: #ca151e;
                color:#fff;
                .title{
                    font-weight: bold;
                    color:#fff;
                }
                .name{
                    color:#fff;
                }
            }
        }
    }
    .mbx{margin-top:30px;margin-bottom: 30px;}
}
</style>