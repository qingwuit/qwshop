<template>
    <div class="user_main table_lists">
        <div class="block_title">
            关注/收藏
            <span><div class="btn" @click="openAdd">{{data.isGoods?'店铺关注':'商品收藏'}}</div></span>
        </div>
        <div class="x20 clear_line"></div>
        <table-view v-if="data.isGoods" :params="{is_type:0}" :handleWidth="150" :options="options" :btnConfig="btnConfigs" >
            <template #table_handleright_hook="{rows}">
                <el-button :title="$t('btn.view')"  :icon="View" @click="$router.push((rows.is_type==0?'/goods/':'/store/')+rows.out_id)" />
            </template>
        </table-view>
        <table-view v-if="!data.isGoods" :params="{is_type:1}" :handleWidth="150" :options="options2" :btnConfig="btnConfigs" >
            <template #table_handleright_hook="{rows}">
                <el-button :title="$t('btn.view')"  :icon="View" @click="$router.push((rows.is_type==0?'/goods/':'/store/')+rows.out_id)" />
            </template>
        </table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import { View  } from '@element-plus/icons'
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const data = reactive({
            isGoods:true,
        })
        
        // 配置字段
        const options = reactive([
            {label:'商品图片',value:'goods_master_image',type:'avatar'},
            {label:'商品名称',value:'goods_name',overFlow:true},
            {label:'商品价格',value:'goods_price'},
            {label:'创建时间',value:'created_at'},
        ]);
        const options2 = reactive([
            {label:'店铺图标',value:'store_logo',type:'avatar'},
            {label:'店铺名称',value:'store_name',overFlow:true},
            {label:'创建时间',value:'created_at'},
        ]);
   

        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            show:{show:false},
            search:{show:false},
            export:{show:false},
            destroy:{show:false},
            destroy:{show:false},
            deletes:{show:true},
        })

        const openAdd = async ()=>{
            data.isGoods = !data.isGoods
        }

        return {View,options,options2,btnConfigs,data,openAdd}
    }
}
</script>
<style lang="scss" scoped>

</style>