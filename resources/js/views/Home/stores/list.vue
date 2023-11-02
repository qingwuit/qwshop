<template>
    <div class="store_list w1200">
        <div class="breadcrumb">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>Home</el-breadcrumb-item>
                <el-breadcrumb-item>店铺街</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">筛选排序：</div>
                <div class="list">
                    <div class="other">
                        <ul>
                            <li @click="sortChange('')" :class="'red'"
                                >默认
                                <div class="sorts">
                                    <el-icon :class="((R.isEmpty(data.base64Decode.sort_order) || data.base64Decode.sort_order=='asc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'"><CaretTop /></el-icon> 
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (R.isEmpty(data.base64Decode.sort_type) || data.base64Decode.sort_type==''))?'caret red':'caret'"><CaretBottom /></el-icon> 
                                </div>
                            </li>
                            <li @click="sortChange('distance')" :class="'red'">
                                距离
                                <div class="sorts">
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='asc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='distance'))?'caret red':'caret'"><CaretTop /></el-icon> 
                                    <el-icon :class="((!R.isEmpty(data.base64Decode.sort_order) && data.base64Decode.sort_order=='desc') && (!R.isEmpty(data.base64Decode.sort_type) && data.base64Decode.sort_type=='distance'))?'caret red':'caret'"><CaretBottom /></el-icon> 
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 搜索条件 E -->

        <div class="home_store_list w1200" v-if="data.list.length>0">
            <ul>
                <li v-for="(v,k) in data.list" :key="k">
                    <div class="left_block" @click="$router.push('/store/'+v.id)">
                        <el-image class="el_image" lazy :src="v.store_logo" :alt="v.store_name||''" />
                        <div class="right_item">
                            <div class="title">{{v.store_name||''}}</div>
                            <div class="qy"><span class="stitle">公司：</span>{{v.store_company_name||''}}</div>
                            <div class="address" title="12"><span class="stitle">地址：</span>{{(v.area_info||'')+' '+(v.store_address||'')}}</div>
                        </div>
                        <div class="comment_rate"></div>
                    </div>
                    <div class="center_block">
                        好评率：100%
                    </div>
                    <div class="right_block">
                        <div class="jl">距离：<span style="color:#ca151e;font-size:16px">{{v.distance}}</span></div>
                        <div class="btn" @click="$router.push('/store/'+v.id)">进入店铺</div>
                    </div>
                </li>
            </ul>

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
import {reactive,onMounted,getCurrentInstance} from "vue"
import { CaretTop,CaretBottom } from '@element-plus/icons'
export default {
    components:{CaretTop,CaretBottom},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            list:[],
            params:{
                per_page:30,// 每页大小
                total:0,
                last_page:1,
                page:1
            },
            base64Decode:{},
            base64Code:'',
        })

        const sortChange = (e)=>{
            data.params.page = 1;
            if(e == ''){
                if(!proxy.R.isEmpty(data.base64Decode.sort_type)){
                    data.base64Decode.sort_order= 'asc';
                }else{
                    if(data.base64Decode.sort_order== 'desc'){
                        data.base64Decode.sort_order= 'asc';
                    }else{
                        data.base64Decode.sort_order= 'desc';
                    }
                }
                data.base64Decode.sort_type = undefined
            }else{
                if(data.base64Decode.sort_type == undefined || data.base64Decode.sort_type != e){
                    data.base64Decode.sort_order= 'asc';
                }else{
                    if(data.base64Decode.sort_order== 'desc'){
                        data.base64Decode.sort_order= 'asc';
                    }else{
                        data.base64Decode.sort_order= 'desc';
                    }
                }
                
                data.base64Decode.sort_type= e;
                data.base64Code = window.btoa(JSON.stringify(data.base64Decode))
            }
            loadData();
        }

        // 获取经纬度
        const lnglat = localStorage.getItem('lonlat')
        if(lnglat){
            let lnglatArr = lnglat.split(',')
            data.params.lng = lnglatArr[0]
            data.params.lat = lnglatArr[1]
        }

        const loadData = async ()=>{
            data.params.params = data.base64Code
            const resp = await proxy.R.get('/stores',data.params)
            if(!resp.code){
                data.list = resp.data
                data.params.total = resp.total;
                data.params.per_page = resp.per_page;
                data.params.current_page = resp.current_page;
                data.params.last_page = resp.last_page;
            }
        }

        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }

        onMounted( async ()=>{
            loadData()
        })
  
        return {
            data,
            handleCurrentChange,
            sortChange,
        }
    }
}
</script>

<style lang="scss" scoped>
.home_store_list{
    ul{margin-bottom: 30px;}
    ul li{padding:30px 0;border-bottom: 1px solid #efefef;;&:after{content:'';display: block;clear:both;}}
    ul li:hover .right_item .title{color:#ca151e}
    .center_block{line-height: 80px;float: left;width: 200px;text-align: center;}
    .right_block{float: right;width: 200px;padding-top: 4px;.btn{padding: 5px 20px;color:#fff;background: #ca151e;display: inline-block;margin-top: 10px;cursor: pointer;}}
    .left_block{
        cursor: pointer;
        float: left;
        width: 500px;
        .el_image{width: 80px;height: 80px;border:1px solid #efefef;float: left;margin-right: 15px;}
        .right_item{
            width: 380px;
            float: left;
            .title{font-weight: bold;padding-top: 3px;font-size: 16px;margin-bottom: 5px;}
            .stitle{color:#666;}
            .qy,.address{color:#999;width: 335px;text-overflow:ellipsis;overflow:hidden; white-space: nowrap;height: 21px;}
        }
        .comment_rate{clear:both;}
    }
}

</style>>

