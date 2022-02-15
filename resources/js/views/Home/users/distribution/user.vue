<template>
    <div class="favorite">
        <div class="user_main">
            <div class="block_title">
                分销佣金
            </div>
            <div class="x20"></div>

            <div class="home_search_block">
                <div class="check_types">
                    <ul>
                        <li :class="params.lev==0?'red':''" @click="onChangeLev(0)">一级分销</li>
                        <li :class="params.lev==1?'red':''" @click="onChangeLev(1)">二级分销</li>
                        <li :class="params.lev==2?'red':''" @click="onChangeLev(2)">三级分销</li>
                    </ul>
                </div>
            </div>
            <div class="admin_table_list" >
                <table-view :handleWidth="80" :handleHide="false" ref="table_view" @tableView="getTabel" :pageUrl="'/user/distributions'" :options="options" :params="params" :btnConfig="btnConfigs" :dialogParam="dialogParam"></table-view>
            </div>

        </div>
        
    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()

        const tableViews = reactive({
            obj:null
        })
        const options = reactive([
            {label:'用户昵称',value:'nickname'},
            {label:'登录时间',value:'login_time'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 表单配置 

  

        const btnConfigs = reactive({
            store:{show:false},
            show:{show:false},
            search:{show:false},
            update:{show:false},
            export:{show:false},
            destroy:{show:false},
        })
        const dialogParam = reactive({
        })

        const params = reactive({
            lev:0,
        })
       
        const onChangeLev = (e)=>{
            params.lev = e
            tableViews.obj.reloadData()
            
        }

        const getTabel = (e)=>{
            tableViews.obj = e
        }



        return {options,dialogParam,params,btnConfigs,onChangeLev,getTabel}
    }
}
</script>
<style lang="scss" scoped>
.check_types{
    margin-bottom: 20px;
    ul:after{
        clear: both;
        display: block;
        content:'';
    }
    ul li{
        width: 120px;
        line-height: 35px;
        text-align: center;
        display: block;
        float: left;
        cursor: pointer;
        border-left:1px solid #efefef;
        border-top:1px solid #efefef;
        border-bottom:1px solid #efefef;
        &:last-child{
            border-right: 1px solid #efefef;
        }
        &:hover{
            background: #ca151e;
            color:#efefef;
            border-color: #ca151e;
        }
        &.red{
            background: #ca151e;
            color:#efefef;
            border-color: #ca151e;
        }
    }
}
</style>