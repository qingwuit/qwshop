<template>
    <div class="goods_list_temp">
        <div class="mbx w1200">
            <a-breadcrumb>
                <a-breadcrumb-item><a href="/">首页</a></a-breadcrumb-item>
                <a-breadcrumb-item><a href="/integral/index">积分商城</a></a-breadcrumb-item>
                <a-breadcrumb-item>积分商品</a-breadcrumb-item>
            </a-breadcrumb>
        </div>

        <!-- 搜索条件 S -->
        <div class="goods_where w1200">
            <div class="item">
                <div class="title">商品分类：</div>
                <div class="list">
                    <div class="first">
                        <ul>
                            <li :class="($isEmpty(base64Decode.cid) || base64Decode.cid==0)?'red':''" @click="classChange(0)">全部</li>
                            <li :class="(!$isEmpty(base64Decode.cid) && base64Decode.cid==v.id)?'red':''" v-for="(v,k) in classes" :key="k" @click="classChange(v.id)">{{v.name}}</li>
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
                    <dl><router-link :to="'/integral/goods/'+v.id">
                        <dt><img width="180px" height="180px" v-lazy="v.goods_master_image" :alt="v.goods_name"></dt>
                        <dd class="title">{{v.goods_name}}</dd>
                        <dd class="price"><a-font type="iconjifen" />{{v.goods_price}}</dd>
                        <dd>
                            <span class="integral">立即兑换</span>
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
          base64Decode:{
              cid:0,
          },
          classes:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 初始化数据
        onload(){
            this.params.params = this.base64Code;
            this.$post(this.$api.homeIntegral+'/search',this.params).then(res=>{
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
        classChange(cid){
            this.base64Decode.cid = cid;
            this.$router.push('/integral/search/'+window.btoa(JSON.stringify(this.base64Decode)))
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
            this.$router.push('/integral/search/'+window.btoa(JSON.stringify(this.base64Decode)))

        },
        get_goods_class(){
            this.$get(this.$api.homeIntegral+'/goods_class').then(res=>{
                this.classes = res.data
            })
        }
    },
    created() {
        if(this.$route.params.params != undefined){
            this.base64Code = this.$route.params.params;
            this.base64Decode = JSON.parse(window.atob(this.base64Code));
        }
        this.onload();
        this.get_goods_class();
        
    },
    mounted() {},
    beforeRouteUpdate (to, from, next) {
        if(from.params.params != to.params.params){
            this.params.page = 1;
            this.base64Code = to.params.params;
            this.base64Decode = JSON.parse(window.atob(to.params.params));
            this.onload();
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