<template>
    <div class="table_select">
        <el-dialog v-model="vis" destroy-on-close :title="params.label" :width="params.width||'60%'" :fullscreen="params.fullscreen||true">
            <table-view :pageUrl="params.pageUrl" :options="params.options" :handleHide="params.handleHide||false" :searchOption="params.searchOptions" :params="params.params||[]" :btnConfig="btnConfigs" >
                <template #table_topleft_hook={multipleSelection,multipleSelectionData}>
                    <el-button type="success" :icon="Finished" @click="selectRow(multipleSelection,multipleSelectionData.value)">{{$t('btn.determine')}}</el-button>
                </template>
            </table-view>
        </el-dialog>
        <el-button :icon="Finished" @click="openTabel">{{$t('btn.select')}}</el-button>
        <span :style="{marginLeft:'10px'}">{{data.showName}}</span>
    </div>
</template>

<script>
import {ref,reactive,watch,onBeforeMount ,getCurrentInstance} from "vue"
import { Finished } from '@element-plus/icons'
import tableView from "@/components/common/table"
export default {
    props:['params','value','showName'],
    components:{},
    setup(props,{emit}) {
        const {proxy} = getCurrentInstance()
        const vis = ref(false)
        const btnConfigs = reactive({
            update:{show:false},
            destroy:{show:false},
            store:{show:false},
        })
        const data = reactive({
            id:[],
            showName:'',
            defaultShowName:'name'
        })

        watch(()=>props.value,(e)=>{
            // console.log(e)
            emit('update:value',e)
        })

        const selectRow = (e,rows)=>{
            if(!e.value || e.value.length<=0) return proxy.$message.error(proxy.$t('msg.selectErr'))
            if(!props.params.many){
                emit('changeVla',e.value[0])
                if(proxy.R.isEmpty(rows[0][data.defaultShowName])) data.defaultShowName = 'goods_name'
                if(proxy.R.isEmpty(rows[0][data.defaultShowName])) data.defaultShowName = 'nickname'
                if(!props.params.showName){
                    data.showName = rows[0][data.defaultShowName]
                }else{
                    data.showName = rows[0][props.params.showName]
                }
            }else{
                emit('changeVla',e.value.join(','))
                let rowName = []
                if(proxy.R.isEmpty(rows[0][data.defaultShowName])) data.defaultShowName = 'goods_name'
                if(proxy.R.isEmpty(rows[0][data.defaultShowName])) data.defaultShowName = 'nickname'
                if(!props.params.showName){
                    rows.map((e)=>{
                        rowName.push(e[data.defaultShowName])
                    })
                }else{
                    rows.map((e)=>{
                        rowName.push(e[props.params.showName])
                    })
                }
                data.showName = rowName.join(',')
            }
            data.id = e.value
            vis.value = false
        }

        const openTabel = ()=>{
            vis.value = true
        }

        onBeforeMount(() => {
            proxy.$options.components.tableView = tableView
        })

        return {data,Finished,btnConfigs,vis,selectRow,openTabel}
    }
}
</script>

