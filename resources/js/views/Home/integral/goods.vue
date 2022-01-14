<template>
    <div class="goods_list_temp">
        <div class="w1200 breadcrumb">
            <el-breadcrumb>
                <el-breadcrumb-item separator="/">首页</el-breadcrumb-item>
                <el-breadcrumb-item v-if="!R.isEmpty(data.base64Decode.keywords)"><em style="color:#ca151e">搜索 "{{decodeURIComponent(data.base64Decode.keywords||'')}}" 结果列表</em></el-breadcrumb-item>
                <el-breadcrumb-item v-else>积分商品</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">商品分类：</div>
                <div class="list">
                    <div class="first">
                        <ul>
                            <li :class="(R.isEmpty(data.base64Decode.cid) || data.base64Decode.cid==0)?'red':''" @click="classChange(0)">全部</li>
                            <li :class="(!R.isEmpty(data.base64Decode.cid) && data.base64Decode.cid==v.id)?'red':''" v-for="(v,k) in data.class" :key="k" @click="classChange(v.id)">{{v.name}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="title">筛选排序：</div>
                <div class="list">
                    <div class="other">
                        <ul>
                            <li @click="sortChange('')" :class="(R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type=='')?'red':''"
                                >默认
                                <div class="sorts">
                                    <el-icon :class="((R.isEmpty(data.base64Decode.sort_order) || data.base64Decode.sort_order=='asc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'" type="caret-up" ><CaretTop /></el-icon>
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'" type="caret-down" ><CaretBottom /></el-icon> 
                                </div>
                            </li>
                            <li @click="sortChange('goods_price')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price')?'red':''">
                                价格
                                <div class="sorts">
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price'))?'caret red':'caret'" type="caret-up" ><CaretTop /></el-icon>
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_price'))?'caret red':'caret'" type="caret-down" ><CaretBottom /></el-icon> 
                                </div>
                            </li>
                            <li @click="sortChange('goods_sale')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='goods_sale')?'red':''">
                                销量
                                <div class="sorts">
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-up" ><CaretTop /></el-icon>
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-down" ><CaretBottom /></el-icon> 
                                </div>
                            </li>
                            <li @click="sortChange('order_comment_count')" :class="(!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='order_comment_count')?'red':''">
                                评论
                                <div class="sorts">
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'"><CaretTop /></el-icon> 
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type)  &&  data.base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'"><CaretBottom /></el-icon> 
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 搜索条件 E -->

        <div class="s_goods_content w1200" v-if="data.params.total>0">
            <!-- 产品列表 S -->
            <div class="s_goods_list">
                <div class="item" v-for="(v,k) in data.list" :key="k">
                    <dl><router-link :to="'/integral/goods/'+v.id">
                        <dt><el-image class="el_image" lazy :src="v.goods_master_image" :alt="v.goods_name" /></dt>
                        <dd class="title">{{v.goods_name}}</dd>
                        <dd class="price">{{$t('btn.money')}}{{v.goods_price}}</dd>
                        <dd>
                            <span class="integral">立即兑换</span>
                        </dd></router-link>
                    </dl>
                </div>
                <div class="clear"></div>
            </div>
            <!-- 产品列表 E -->

            <div class="fy">
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
        <el-empty style="margin-top:40px" v-else />
 
    </div>
</template>

<script>
import {reactive,onMounted,watch,computed,getCurrentInstance} from "vue"
import { CaretTop,CaretBottom } from '@element-plus/icons'
import { useStore } from 'vuex'
import {_push} from "@/plugins/config"
import {  useRoute } from 'vue-router'
export default {
    components: {CaretTop,CaretBottom},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const route = useRoute()
        const data = reactive({
            list:[],
            class:[],
            params:{
                per_page:30,// 每页大小
                total:0,
                last_page:1,
                page:1
            },
            base64Code:'',
            base64Decode:{},
        })

        const sortChange = (e='')=>{
            if(e == ''){
                if(data.base64Decode.sort_order== 'desc'){
                    data.base64Decode.sort_order= 'asc'
                }else{
                    data.base64Decode.sort_order= 'desc'
                }
                if(data.base64Decode.sort_type != undefined){
                    data.base64Decode.sort_order= 'asc'
                }
                data.base64Decode.sort_type = undefined
            }else{
                if(data.base64Decode.sort_type == undefined || data.base64Decode.sort_type != e){
                    data.base64Decode.sort_order= 'asc'
                }else{
                    if(data.base64Decode.sort_order== 'desc'){
                        data.base64Decode.sort_order= 'asc'
                    }else{
                        data.base64Decode.sort_order= 'desc'
                    }
                }
                
                data.base64Decode.sort_type= e
                // console.log(this.base64Decode.sort_type)
            }
            _push('/integral/search/'+window.btoa(JSON.stringify(data.base64Decode)))
        }

        const classChange = (cid)=>{
            data.base64Decode.cid = cid
            _push('/integral/search/'+window.btoa(JSON.stringify(data.base64Decode)))
        }

        const loadData = async ()=>{
            data.params.params = data.base64Code;
            const resp = await proxy.R.get('/integral/goods',data.params)
            if(!resp.code){
                data.list = resp.data
                data.params.total = parseInt(resp.total)
                data.params.last_page = parseInt(resp.last_page)
                data.params.per_page = parseInt(resp.per_page)
                data.params.current_page = parseInt(resp.current_page)
            }
            const resps = await proxy.R.get('/integral/class')
            if(!resps.code) data.class = resps
        }

        // 页面切换
        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }


        onMounted( async ()=>{
            data.base64Code = route.params.params
            if(!proxy.R.isEmpty(data.base64Code)){
                data.base64Decode = JSON.parse(window.atob(data.base64Code))
            }
            loadData()
        })

        watch(()=>route.params,(e)=>{
            if(!proxy.R.isEmpty(e) && !proxy.R.isEmpty(e.params) ){
                data.base64Decode = JSON.parse(window.atob(e.params))
                data.base64Code = e.params
                loadData()
            }
        },{deep:true})
  
        return {
            data,
            handleCurrentChange,
            sortChange,classChange
        }
    },


    
};
</script>
<style lang="scss" scoped>
.goods_where{
    border: 1px solid #efefef;
    line-height: 50px;
    font-size: 14px;
    .item{
        padding:0 20px;
        box-sizing: border-box;
        border-bottom: 1px solid #efefef;
        &:last-child{
            border-bottom: none;
        }
        &:after{
            clear:both;
            display: block;
            content:'';
        }
        .title{
            float: left;
            margin-right: 20px;
        }
        .list{
            float: left;
            ul li{
                cursor: pointer;
            }
            .first{
                &:after{
                    clear: both;
                    display: block;
                    content:'';
                }
                ul li{
                    background: #efefef;
                    line-height: 30px;
                    padding:0 10px;
                    margin-top: 10px;
                    text-align: center;
                    float: left;
                    margin-right: 20px;
                    border-radius: 3px;
                    &:hover{
                        color:#fff;
                        background-color:#ca151e;
                    }
                    &.red{
                       color:#fff;
                       background-color:#ca151e; 
                    }
                }
            }
            .sec{
                ul{
                    border-top: 1px dashed #e1e1e1;
                    margin-top: 20px;
                    &:after{
                        clear:both;
                        display: block;
                        content:'';
                    }
                }
                ul li{
                    float: left;
                    margin-right: 20px;
                    padding:0 10px; 
                    line-height: 40px;
                    color: #666;
                    font-size: 12px;
                    &:hover{
                        color:#ca151e;
                    }
                    &.red{
                       color:#ca151e;
                    }
                }
                &:after{
                    clear: both;
                    display: block;
                    content:'';
                }
            }
            .other{
                ul li{
                    float: left;
                    margin-right: 20px;
                    padding:0 10px;
                    position: relative;
                    &:hover{
                        color:#ca151e;
                    }
                    &.red{
                       color:#ca151e;
                    }
                    .sorts{
                        position: absolute;
                        top:0;
                        right: 0;
                        color:#666;
                        .caret{
                            font-size: 12px;
                            position: absolute;
                            -webkit-transform-origin-x: 0; //缩小后文字居左
                            -webkit-transform: scale(0.80);   //关键
                            &:first-child{
                               top:16px;
                               right:-5px; 
                            }
                            &:last-child{
                               top:22px;
                               right:-5px; 
                            }
                            &.red{
                                color:#ca151e;
                            }
                        }
                    }
             
                }
            }
        }
    }
}
.home .s_goods_list{
    margin-top: 40px;
    margin-bottom: 30px;
}
.home .s_goods_list .item{
    float: left;
    width: 224px;
    height: 364px;
    box-sizing: border-box;
    margin: 0 20px 20px 0;
}
.home .s_goods_list .item dl{
    border: 1px solid #efefef;
    box-sizing: border-box;
    width: 224px;
    height: 364px;
    transition: all 0.2s linear;
}
.home .s_goods_list .item dl dt{
    padding-top: 26px;
    padding-bottom: 26px;
    text-align: center;
}
.home .s_goods_list .item:hover dl{
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    margin-top:-3px;
}
.home .s_goods_list .item dl dd.title{
    color: #000;
    height: 38px;
    text-align: center;
    padding: 0 15px;
    overflow: hidden;
}
.home .s_goods_list .item dl dd.price{
    color: #e01d20;
    line-height: 50px;
    text-align: center;
    padding: 0 15px;
    font-size: 20px;
    overflow: hidden;
}
.home .s_goods_list .item dl dd.price font{
    color:#999;
    font-size: 12px;
}
.home .s_goods_list .item dl dd span{
    width: 50%;
    display: block;
    border-top: 1px solid #efefef;
    float: left;
    line-height: 41px;
    text-align: center;
}
.home .s_goods_list .item dl dd span.integral{
    width: 100%;
    display: block;
    border-top: 1px solid #efefef;
    float: left;
    line-height: 41px;
    text-align: center;
}
.home .s_goods_list .item dl dd span:first-child{
    box-sizing: border-box;
    border-right: 1px solid #efefef;
}
.home .s_goods_list .item dl dd span:first-child:hover{
    color:#fff;
    background: #ca151e;
}
.home .s_goods_list .item .el_image{
    width: 176px;
    height: 176px;
}
.breadcrumb{
    margin-top:30px;
    margin-bottom: 30px;
}
</style>