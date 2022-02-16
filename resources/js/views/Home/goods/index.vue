<template>
    <div class="goods_list_temp">
        <div class="w1200 breadcrumb">
            <el-breadcrumb>
                <el-breadcrumb-item separator="/">首页</el-breadcrumb-item>
                <el-breadcrumb-item v-if="!R.isEmpty(data.base64Decode.keywords)"><em style="color:#ca151e">搜索 "{{decodeURIComponent(data.base64Decode.keywords||'')}}" 结果列表</em></el-breadcrumb-item>
                <el-breadcrumb-item v-else>商品列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">商品分类：</div>
                <div class="list">
                    <div class="first">
                        <ul>
                            <li :class="(R.isEmpty(data.base64Decode.pid) || data.base64Decode.pid==0)?'red':''" @click="classChange(0,{},3)">全部</li>
                            <li :class="(!R.isEmpty(data.base64Decode.pid) && data.base64Decode.pid==v.id)?'red':''" v-for="(v,k) in data.common.classes" :key="k" @click="classChange(v.id,v)">{{v.name}}</li>
                        </ul>
                    </div>
                    <div class="sec" v-for="(v,k) in data.common.classes" :key="k">
                        <ul v-if="(!R.isEmpty(data.base64Decode.pid) && data.base64Decode.pid==v.id)">
                            <li :class="(!R.isEmpty(data.base64Decode.sid) && data.base64Decode.sid==vo.id)?'red':''" v-for="(vo,key) in v.children" @click="classChange(v.id,vo,1)" :key="key">{{vo.name}}</li>
                        </ul>
                        <div v-if="(!R.isEmpty(data.base64Decode.pid) && data.base64Decode.pid==v.id)">
                            <div  v-for="(vo,key) in v.children" :key="key">
                                <ul style="margin-top:0" v-if="(!R.isEmpty(data.base64Decode.sid) && data.base64Decode.sid==vo.id)">
                                    <li :class="(!R.isEmpty(data.base64Decode.tid) && data.base64Decode.tid==voo.id)?'red':''" v-for="(voo,keys) in vo.children" :key="keys" @click="classChange(v.id,voo,2,vo.id)">{{voo.name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="sec" v-for="(v,k) in data.common.classes" :key="k">
                        <ul v-if="(!R.isEmpty(base64Decode.pid) && base64Decode.pid==v.id)">
                            <li  v-for="(vo,key) in v.children" :key="key">{{vo.name}}</li>
                        </ul>
                    </div> -->
                </div>
            </div>
            <div class="item">
                <div class="title">品牌筛选：</div>
                <div class="list">
                    <div class="first">
                        <ul>
                            <li :class="(R.isEmpty(data.base64Decode.brand_id) || data.base64Decode.brand_id==0)?'red':''" @click="brandChange(0)" >全部</li>
                            <li :class="(!R.isEmpty(data.base64Decode.brand_id) && data.base64Decode.brand_id==v.id)?'red':''" v-for="(v,k) in data.common.brands" :key="k"  @click="brandChange(v.id)">{{v.name}}</li>
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
                    <dl><router-link :to="'/goods/'+v.id">
                        <dt><el-image class="el_image" lazy :src="v.goods_master_image" :alt="v.goods_name" /></dt>
                        <dd class="title">{{v.goods_name}}</dd>
                        <dd class="price">{{$t('btn.money')}}{{v.goods_price}}</dd>
                        <dd>
                            <span>立即购买</span>
                            <span>{{v.order_comment_count}} 人评论</span>
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
            common:{
                classes:computed(()=>store.state.init.common.classes),
                brands:computed(()=>store.state.init.common.brands)
            },
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
            _push('/s/'+window.btoa(JSON.stringify(data.base64Decode)))
        }

        const loadData = async ()=>{
            data.params.params = data.base64Code;
            const resp = await proxy.R.get('/goods',data.params)
            if(!resp.code){
                data.list = resp.data
                data.params.total = parseInt(resp.total)
                data.params.last_page = parseInt(resp.last_page)
                data.params.per_page = parseInt(resp.per_page)
                data.params.current_page = parseInt(resp.current_page)
            }
        }

        // 页面切换
        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }

        // 分类改变
        const classChange = (pid,info,deep=0,sid=0)=>{
            data.base64Decode.class_id = []
            data.base64Decode.pid = pid
            if(deep == 0){
                info.children.forEach(item=>{
                    if(!proxy.R.isEmpty(item.children)){
                        item.children.forEach(item2=>{
                            data.base64Decode.class_id.push(item2.id)
                        })
                    }
                })
                data.base64Decode.sid = undefined
                data.base64Decode.tid = undefined
            }
            if(deep == 1){
                data.base64Decode.sid = info.id
                if(!proxy.R.isEmpty(info.children)){
                    info.children.forEach(item=>{
                        data.base64Decode.class_id.push(item.id)
                    })
                }else{
                    data.base64Decode.class_id = [0]
                }
                data.base64Decode.tid = undefined
            }
            if(deep == 2){
                data.base64Decode.sid = info.pid
                data.base64Decode.tid = info.id
                data.base64Decode.class_id.push(info.id)
            }
            if(deep == 3){
                data.base64Decode.pid = undefined
                data.base64Decode.tid = undefined
                data.base64Decode.tid = undefined
                data.base64Decode.class_id = undefined
            }
            _push('/s/'+window.btoa(JSON.stringify(data.base64Decode)))
        }

        // 品牌改变
        const brandChange = (e)=>{
            data.base64Decode.brand_id = e
            _push('/s/'+window.btoa(JSON.stringify(data.base64Decode)))
        }

        onMounted( async ()=>{
            data.base64Code = route.params.params
            if(!proxy.R.isEmpty(data.base64Code)){
                data.base64Decode = JSON.parse(window.atob(data.base64Code))
            }
            loadData()
        })

        watch(()=>route.params,(e)=>{
            if(!proxy.R.isEmpty(e) && !proxy.R.isEmpty(e.params)){
                data.base64Decode = JSON.parse(window.atob(e.params))
                data.base64Code = e.params
                loadData()
            }
        },{deep:true})
  
        return {
            data,
            handleCurrentChange,
            sortChange,
            classChange,
            brandChange,
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
    &:nth-child(5n){
        margin-right: 0;
    }
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