<template>
    <div class="table_select">
        <el-dialog v-model="vis" destroy-on-close :title="params.label" :width="params.width||'60%'" :fullscreen="params.fullscreen||true">
            <table-view :pageUrl="params.pageUrl" :options="params.options" :handleHide="params.handleHide||false" :params="params.params||[]" :btnConfig="btnConfigs" >
                <template #table_topleft_hook={multipleSelection}>
                    <el-button type="success" :icon="Finished" @click="selectRow(multipleSelection)">{{$t('btn.determine')}}</el-button>
                </template>
            </table-view>
        </el-dialog>
        <el-button :icon="Finished" @click="openTabel">{{$t('btn.select')}}</el-button>
    </div>
</template>

<script>
import {ref,reactive,watch,onBeforeMount ,getCurrentInstance} from "vue"
import { Finished } from '@element-plus/icons'
import tableView from "@/components/common/table"
export default {
    props:['params','value'],
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
        })

        watch(()=>props.value,(e)=>{
            // console.log(e)
            emit('update:value',e)
        })

        const selectRow = (e)=>{
            if(!e.value || e.value.length<=0) return proxy.$message.error(proxy.$t('msg.selectErr'))
            if(!props.params.many){
                emit('changeVla',e.value[0])
            }else{
                emit('changeVla',e.value.join(','))
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

        return {Finished,btnConfigs,vis,selectRow,openTabel}
    }
}
</script>

