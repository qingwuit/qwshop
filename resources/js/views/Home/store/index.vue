<template>
    <div class="hotme_store">
        <div class="mbx w1200">
            <a-breadcrumb>
                <a-breadcrumb-item><a href="/">首页</a></a-breadcrumb-item>
                <a-breadcrumb-item >店铺街</a-breadcrumb-item>
            </a-breadcrumb>
        </div>
         <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">筛选排序：</div>
                <div class="list">
                    <div class="other">
                        <ul>
                            <li @click="sortChange('')" :class="($isEmpty(base64Decode.sort_type) || base64Decode.sort_type=='')?'red':''"
                                >默认
                                <div class="sorts">
                                    <a-icon :class="(($isEmpty(base64Decode.sort_order) || base64Decode.sort_order=='asc') && ($isEmpty(base64Decode.sort_type) || base64Decode.sort_type==''))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && ($isEmpty(base64Decode.sort_type) || base64Decode.sort_type==''))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                            <li @click="sortChange('distance')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='distance')?'red':''">
                                距离
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='distance'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='distance'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                            <!-- <li @click="sortChange('goods_sale')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_sale')?'red':''">
                                销量
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                            <li @click="sortChange('goods_comment')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_comment')?'red':''">
                                收藏
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_comment'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_comment'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 搜索条件 E -->

        <div class="home_store_list w1200" v-if="list.length>0">
            <ul>
                <li v-for="(v,k) in list" :key="k">
                    <div class="left_block" @click="$router.push('/store/'+v.id)">
                        <img v-lazy="v.store_logo" alt="">
                        <div class="right_item">
                            <div class="title">{{v.store_name||''}}</div>
                            <div class="qy"><span class="stitle">公司：</span>{{v.store_company_name||''}}</div>
                            <div class="address" title="12"><span class="stitle">地址：</span>{{(v.area_info||'')+(v.store_address||'')}}</div>
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
                <a-pagination v-model="params.page" :default-page-size="params.per_page" :total="params.total" @change="onChange" />
            </div>
        </div>
        <a-empty style="margin-top:40px" v-else />
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          params:{
              page:1,
              per_page:30,
              total:0,
          },
          base64Decode:{},
          base64Code:'',
          list:[{name:1}],
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 初始化数据
        onload(){
            this.params.params = this.base64Code;
            this.$get(this.$api.homeStore,this.params).then(res=>{
                if(res.code == 200){
                    this.params.total = res.data.total;
                    this.params.per_page = res.data.per_page;
                    this.params.current_page = res.data.current_page;
                    this.list = res.data.data;
                    // console.log(this.params);
                }else{
                    this.$message.error(res.msg);
                }
                
            })
            // console.log(this.base64Decode)
        },
        onChange(e){
            this.params.page = e;
            this.onload();
        },
        sortChange(e=''){
            this.params.page = 1;
            if(e == ''){
                if(this.base64Decode.sort_order== 'desc'){
                    this.base64Decode.sort_order= 'asc';
                }else{
                    this.base64Decode.sort_order= 'desc';
                }
                if(this.base64Decode.sort_type != undefined){
                    this.base64Decode.sort_order= 'asc';
                }
                this.base64Decode.sort_type = undefined
            }else{
                if(this.base64Decode.sort_type == undefined || this.base64Decode.sort_type != e){
                    this.base64Decode.sort_order= 'asc';
                }else{
                    if(this.base64Decode.sort_order== 'desc'){
                        this.base64Decode.sort_order= 'asc';
                    }else{
                        this.base64Decode.sort_order= 'desc';
                    }
                }
                
                this.base64Decode.sort_type= e;
                console.log(this.base64Decode.sort_type)
                this.base64Code = window.btoa(JSON.stringify(this.base64Decode))
            }
            this.onload();
        },
    },
    created() {
        this.onload();
    },
    mounted() {}
};
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
        img{width: 80px;height: 80px;border:1px solid #efefef;float: left;margin-right: 15px;}
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
</style>