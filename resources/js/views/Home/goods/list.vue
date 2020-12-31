<template>
    <div class="goods_list_temp">
        <div class="mbx w1200">
            <a-breadcrumb>
                <a-breadcrumb-item><a href="/">首页</a></a-breadcrumb-item>
                <a-breadcrumb-item v-if="!$isEmpty(base64Decode.keywords)"><font color="#ca151e">搜索 "{{decodeURIComponent(base64Decode.keywords)}}" 结果列表</font></a-breadcrumb-item>
                <a-breadcrumb-item v-else>商品列表</a-breadcrumb-item>
            </a-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">商品分类：</div>
                <div class="list">
                    <div class="first">
                        <ul>
                            <li :class="($isEmpty(base64Decode.pid) || base64Decode.pid==0)?'red':''" @click="classChange(0,{},3)">全部</li>
                            <li :class="(!$isEmpty(base64Decode.pid) && base64Decode.pid==v.id)?'red':''" v-for="(v,k) in common.classes" :key="k" @click="classChange(v.id,v)">{{v.name}}</li>
                        </ul>
                    </div>
                    <div class="sec" v-for="(v,k) in common.classes" :key="k">
                        <ul v-if="(!$isEmpty(base64Decode.pid) && base64Decode.pid==v.id)">
                            <li :class="(!$isEmpty(base64Decode.sid) && base64Decode.sid==vo.id)?'red':''" v-for="(vo,key) in v.children" @click="classChange(v.id,vo,1)" :key="key">{{vo.name}}</li>
                        </ul>
                        <div v-if="(!$isEmpty(base64Decode.pid) && base64Decode.pid==v.id)">
                            <div  v-for="(vo,key) in v.children" :key="key">
                                <ul style="margin-top:0" v-if="(!$isEmpty(base64Decode.sid) && base64Decode.sid==vo.id)">
                                    <li :class="(!$isEmpty(base64Decode.tid) && base64Decode.tid==voo.id)?'red':''" v-for="(voo,keys) in vo.children" :key="keys" @click="classChange(v.id,voo,2,vo.id)">{{voo.name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="sec" v-for="(v,k) in common.classes" :key="k">
                        <ul v-if="(!$isEmpty(base64Decode.pid) && base64Decode.pid==v.id)">
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
                            <li :class="($isEmpty(base64Decode.brand_id) || base64Decode.brand_id==0)?'red':''" @click="brandChange(0)" >全部</li>
                            <li :class="(!$isEmpty(base64Decode.brand_id) && base64Decode.brand_id==v.id)?'red':''" v-for="(v,k) in common.brands" :key="k"  @click="brandChange(v.id)">{{v.name}}</li>
                        </ul>
                    </div>
                </div>
            </div>
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
                            <li @click="sortChange('goods_price')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_price')?'red':''">
                                价格
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_price'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_price'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                            <li @click="sortChange('goods_sale')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='goods_sale')?'red':''">
                                销量
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='goods_sale'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                            <li @click="sortChange('order_comment_count')" :class="(!$isEmpty(base64Decode.sort_type) && base64Decode.sort_type=='order_comment_count')?'red':''">
                                评论
                                <div class="sorts">
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='asc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'" type="caret-up" />
                                    <a-icon :class="((!$isEmpty(base64Decode.sort_order) && base64Decode.sort_order=='desc') && (!$isEmpty(base64Decode.sort_type)  &&  base64Decode.sort_type=='order_comment_count'))?'caret red':'caret'" type="caret-down" />
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 搜索条件 E -->

        <div class="s_goods_content w1200" v-if="params.total>0">
            <!-- 产品列表 S -->
            <div class="s_goods_list">
                <div class="item" v-for="(v,k) in list" :key="k">
                    <dl><router-link :to="'/goods/'+v.id">
                        <dt><img width="180px" height="180px" v-lazy="v.goods_master_image" :alt="v.goods_name"></dt>
                        <dd class="title">{{v.goods_name}}</dd>
                        <dd class="price">￥{{v.goods_price}}</dd>
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
                <a-pagination v-model="params.page" :default-page-size="params.per_page" :total="params.total" @change="onChange" />
            </div>
        </div>
        <a-empty style="margin-top:40px" v-else />
 
    </div>
</template>

<script>
import {mapState} from 'vuex'
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
          list:[],
          base64Code:'',
          base64Decode:{},
      };
    },
    watch: {},
    computed: {...mapState('homeCommon',['common'])},
    methods: {
        // 初始化数据
        onload(){
            this.params.params = this.base64Code;
            this.$post(this.$api.homeGoods+'/search/all',this.params).then(res=>{
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
            console.log(this.base64Decode)
        },
        onChange(e){
            this.params.page = e;
            this.onload();
        },
        // 分类改变
        classChange(pid,info,deep=0,sid=0){
            this.base64Decode.class_id = [];
            this.base64Decode.pid = pid;
            if(deep == 0){
                info.children.forEach(item=>{
                    if(!this.$isEmpty(item.children)){
                        item.children.forEach(item2=>{
                            this.base64Decode.class_id.push(item2.id);
                        })
                    }
                })
                this.base64Decode.sid = undefined;
                this.base64Decode.tid = undefined;
            }
            if(deep == 1){
                this.base64Decode.sid = info.id;
                if(!this.$isEmpty(info.children)){
                    info.children.forEach(item=>{
                        this.base64Decode.class_id.push(item.id);
                    })
                }else{
                    this.base64Decode.class_id = [0];
                }
                this.base64Decode.tid = undefined;
            }
            if(deep == 2){
                this.base64Decode.sid = info.pid;
                this.base64Decode.tid = info.id;
                this.base64Decode.class_id.push(info.id);
            }
            if(deep == 3){
                this.base64Decode.pid = undefined;
                this.base64Decode.tid = undefined;
                this.base64Decode.tid = undefined;
                this.base64Decode.class_id = undefined;
            }
            this.$router.push('/s/'+window.btoa(JSON.stringify(this.base64Decode)))
        },
        // 品牌改变
        brandChange(e){
            this.base64Decode.brand_id = e;
            this.$router.push('/s/'+window.btoa(JSON.stringify(this.base64Decode)))
        },
        // 排序
        sortChange(e=''){
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
            }
            this.$router.push('/s/'+window.btoa(JSON.stringify(this.base64Decode)))

        }
    },
    created() {
        if(this.$route.params.params != undefined){
            this.base64Code = this.$route.params.params;
            this.base64Decode = JSON.parse(window.atob(this.base64Code));
            this.onload();
        }
    },
    mounted() {},
    beforeRouteUpdate (to, from, next) {
        if(from.params.params != to.params.params){
            this.params.page = 1;
            this.base64Code = to.params.params;
            this.base64Decode = JSON.parse(window.atob(to.params.params));
            this.onload();
            // this.goods_id = to.params.id;
            // this.get_goods_info();
        }
        next();
        // react to route changes...
        // don't forget to call next()
    }
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
</style>