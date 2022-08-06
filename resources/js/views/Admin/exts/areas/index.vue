<template>
    <div class="qwit">
        <table-view :options="options" :dialogParam="dialogParam" :searchOption="searchOptions" :tableCfg="{lazy:true,pid:'code'}">
            <template #table_topleft_hook>
                <el-button :icon="MagicStick" :loading="loading" @click="clearCache">{{$t('btn.clearCache')}}</el-button>
            </template>
        </table-view>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
import { MagicStick } from '@element-plus/icons'
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const loading = ref(false)
        const options = reactive([
            {label:'地区',value:'name'},
            {label:'编码',value:'code',type:'tags'},
            {label:'创建时间',value:'created_at'},
        ]);
        // 搜索字段
        const searchOptions = reactive([
            {label:'地区名称',value:'name',where:'likeRight'},
        ])
        // 表单配置 
        const addColumn = [
            {label:'上级地区',value:'pid',type:'cascader',props:{emitPath:false,checkStrictly: true,label:'name',value:'code'}},
            {label:'地区',value:'name'},
            {label:'编码',value:'code'},
        ]
        const viewColumn = [
            {label:'地区',value:'name'},
            {label:'编码',value:'code'},
        ]
        const dialogParam = reactive({
            dict:[{name:'pid',url:'/load_areas?deep=2',addSelect:{name:proxy.$t('btn.default'),code:'0',id:0}}],
            rules:{
                pid:[{required:true,message:'不能为空'}],
                name:[{required:true,message:'不能为空'}],
                code:[{required:true,message:'不能为空'}]
            },
            view:{column:viewColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        const clearCache = ()=>{
            loading.value = true
            proxy.R.get('/Admin/clear_area').then(()=>{
                proxy.$message.success(proxy.$t('msg.success'))
            }).catch(()=>{
                proxy.$message.error(proxy.$t('msg.error'))
            }).finally(()=>{
                loading.value=false
            })
        }
        return {MagicStick,loading,options,dialogParam,searchOptions,clearCache}
    }
}
</script>

<style>

</style>