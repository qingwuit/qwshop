<template>
    <div :class="changeColor?'leftbar2':'leftbar'">
        <ul>
            <li class="left_bar_block" v-for="(v,k) in data.goodsClass" :key="k">
                <div class="class_1"><a @click="toNav(v.id,v)" href="javascript:;">{{v.name}}</a></div>
                <div class="class_2">
                    <ul>
                        <li v-for="(vo,key) in v.children" :key="key" v-show="key<3"><a @click="toNav(v.id,vo,1)">{{vo.name}}</a></li>
                    </ul>
                </div>
                <div class="subbar">
                    <div class="subbar_top">
                        <ul>
                            <!-- <li v-for="(tag_item,tag_key) in v.tags.split(',')" :key="tag_key"><router-link :to="'/goods/params/class_id.'+v.id">{{tag_item}}</router-link></li> -->
                        </ul>
                    </div>
                    <div class="subbar_right">
                        <ul>
                            <li v-for="(goods_brand_item,goods_brand_key) in data.goodsBrand||[]" :key="goods_brand_key"><img width="100px" height="50px" :src="goods_brand_item.thumb||''" alt=""></li>
                        </ul>
                        <div class="subbar_right_adv">
                            <!-- <router-link :to="goods_brand_adv['adv'][0]['adv_link']"><img width="203px" height="96" :src="goods_brand_adv['adv'][0]['adv_image']" :alt="goods_brand_adv['adv'][0]['adv_title']"></router-link> -->
                        </div>
                    </div>
                    <div class="subbar_subnav">
                        <div class="class2_title"  v-for="(vo,key) in v.children" :key="key">
                            <h4>{{vo.name}}</h4>
                            <ul>
                                <li v-for="(item,index) in vo.children" :key="index"><a @click="toNav(v.id,item,2)">{{item.name}}</a></li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </li>
        </ul>
        
    </div>
</template>

<script>
import {reactive,computed,ref,onMounted,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
import router from '@/plugins/router'
export default {
    components: {},
    props: {
        changeColor:{
            type:Boolean,
            default:false,
        },
    },
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const data = reactive({
            goodsBrand:[],
            goodsBrandAdv:[],
            goodsClass:[],
        })
        data.goodsClass = computed(()=>store.state.init.common.classes)
        data.goodsBrand = computed(()=>store.state.init.common.brands)

        const toNav = (id,info,deep)=>{
            let params = {};
            params.pid = id; // 顶级栏目ID
            params.class_id = [];
            if(deep == 0){
                info.children.forEach(item=>{
                    if(!proxy.R.isEmpty(item.children)){
                        item.children.forEach(item2=>{
                            params.class_id.push(item2.id);
                        })
                    }
                })
            }
            if(deep == 1){
                params.sid = info.id;
                if(!proxy.R.isEmpty(info.children)){
                    info.children.forEach(item=>{
                        params.class_id.push(item.id);
                    })
                }
            }
            if(deep == 2){
                params.sid = info.pid;
                params.tid = info.id;
                params.class_id.push(info.id);
            }
            router.push('/s/'+window.btoa(JSON.stringify(params)))
        }

        return {
            data,toNav
        }
    },
   
};
</script>
<style lang="scss" scoped>
.leftbar{
    width: 240px;
    position: absolute;
    left: 0px;
    height: 450px;
    display: block;
    z-index: 998;
    color:#333;
    // background: rgba(255,255,255,.7);
    background:#fff;
    // overflow: hidden;
    // padding:0 15px;
    .class_1{
        padding: 8px 15px 0 15px;
        a{
            // font-weight: bold;
        }
    }
    
    .class_2{
        padding: 0 0 6px 15px;
        font-size: 12px;
        overflow: hidden;
        box-sizing: border-box;
        width: 240px;
        height: 24px;
        ul:after{
            display: block;
            clear: both;
            content:'';
        }
        ul li{
            line-height: 18px;
            float: left;
            a{
                color:#999;
                margin-right: 15px;
            }
        }
    }
    .class_2:after{
        display: block;
        clear: both;
        content:'';
    }
    
    .left_bar_block:hover{
        background: #f5f5f5;
        .subbar{
            display: block;
        }
    }
    .subbar{
        background: #f5f5f5;
        width: 960px;
        height: 450px;
        position: absolute;
        top:0;
        left: 240px;
        z-index: 999;
        display: none;
        // box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .subbar_top{
            padding: 20px 0 0 20px;
            width: 680px;
            float: left;
            ul li{
                float: left;
                line-height: 25px;
                padding: 0 8px;
                background: #5f4f4f;
                margin-right: 20px;
                a{
                    color:#fff;
                    font-size: 12px;
                }
            }
            :after{
                display: block;
                content: "";
                clear: both;
            }
        }
        .subbar_right{
            float: right;
            width: 220px;
            height: 450px;
            ul{
                margin-top:20px;
            }
            ul li{
                float: left;
                height: 50px;
                border:1px solid #eee;
                border-bottom: none;
            }
            ul li:nth-child(2n){
                border-left: none;
            }
        }
    }
    .subbar_subnav{
        margin-left: 20px;
        margin-top: 15px;
        width: 680px;
        display: block;
        float: left;
        .class2_title{
            h4{
                width: 60px;
                text-align: right;
                float: left;
                margin-right: 20px;
            }
            ul{
                float: left;
                width: 600px;
                li{
                    float: left;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 13px;
                    border-left: 1px solid #ddd;
                    line-height: 12px;
                    a{
                        font-size: 12px;
                        color:#999;
                        
                    }
                    a:hover{
                        color:#ca151e;
                    }
                    
                }
                li:last-child{
                    margin-bottom: 14px;
                }
                border-bottom: 1px dashed #ccc;
            }
            
        }
        .class2_title:after{
            display: block;
            clear: both;
            content:'';
        }
    }
    
}
.leftbar2{
    width: 240px;
    position: absolute;
    left: 0px;
    height: 450px;
    display: block;
    z-index: 998;
    color:#fff;
    background: rgba(0,0,0,.6);
    // overflow: hidden;
    // padding:0 15px;
    .class_1{
        padding: 8px 15px 0 15px;
        a{
            // font-weight: bold;
            color:#fff;
        }
    }
    
    .class_2{
        padding: 0 0 6px 15px;
        font-size: 12px;
        overflow: hidden;
        box-sizing: border-box;
        width: 240px;
        height: 24px;
        ul:after{
            display: block;
            clear: both;
            content:'';
        }
        ul li{
            line-height: 18px;
            float: left;
            a{
                color:#bfbfbf;
                margin-right: 15px;
            }
        }
    }
    .class_2:after{
        display: block;
        clear: both;
        content:'';
    }
    
    .left_bar_block:hover{
        background: #000;
        .subbar{
            display: block;
        }
    }
    .subbar{
        background: #fff;
        width: 960px;
        height: 450px;
        position: absolute;
        top:0;
        left: 240px;
        z-index: 999;
        display: none;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .subbar_top{
            padding: 20px 0 0 20px;
            width: 680px;
            float: left;
            ul li{
                float: left;
                line-height: 25px;
                padding: 0 8px;
                background: #5f4f4f;
                margin-right: 20px;
                a{
                    color:#fff;
                    font-size: 12px;
                }
            }
            :after{
                display: block;
                content: "";
                clear: both;
            }
        }
        .subbar_right{
            float: right;
            width: 220px;
            height: 450px;
            ul{
                margin-top:20px;
            }
            ul li{
                float: left;
                height: 50px;
                border:1px solid #eee;
                border-bottom: none;
            }
            ul li:nth-child(2n){
                border-left: none;
            }
        }
    }
    .subbar_subnav{
        margin-left: 20px;
        margin-top: 15px;
        width: 680px;
        display: block;
        float: left;
        .class2_title{
            h4{
                width: 60px;
                text-align: right;
                float: left;
                margin-right: 20px;
                color:#333;
            }
            ul{
                float: left;
                width: 600px;
                li{
                    float: left;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 13px;
                    border-left: 1px solid #ddd;
                    line-height: 12px;
                    a{
                        font-size: 12px;
                        color:#999;
                        
                    }
                    a:hover{
                        color:#ca151e;
                    }
                    
                }
                li:last-child{
                    margin-bottom: 14px;
                }
                border-bottom: 1px dashed #ccc;
            }
            
        }
        .class2_title:after{
            display: block;
            clear: both;
            content:'';
        }
    }
    
}
</style>