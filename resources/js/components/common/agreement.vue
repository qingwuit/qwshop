<template>
    <el-dialog
        v-model="data.centerDialogVisible"
        :title="data.agreement.name||$t('agreement.title')"
        width="80%"
        destroy-on-close
        center
    >
        
        <div class="agreeemnt_content" v-html="editorHtml(data.agreement.content)||''"></div>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="data.centerDialogVisible = false">{{$t('btn.cancel')}}</el-button>
                <el-button type="primary" @click="data.centerDialogVisible = false"
                >{{$t('btn.determine')}}</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>

<script setup>
import {reactive,getCurrentInstance} from "vue"
import {editorHandle} from '@/plugins/config'

const {proxy} = getCurrentInstance()
const data = reactive({
    centerDialogVisible:false,
    agreement:{},
})

const props = defineProps({
    ename:{
        type:String,
        default:'agreement'
    }
})

const loadData = async ()=>{
    let resp = await proxy.R.get('/agreements/'+props.ename)
    if(!resp.code) data.agreement = resp
}

const openDialog = ()=>{
    data.centerDialogVisible = true
    loadData()
}

// editor
const editorHtml = (e)=>{
    return editorHandle(e)
}

defineExpose({
    openDialog
})

</script>

