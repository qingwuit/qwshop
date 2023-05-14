<template>
    <div class="qwit">
        <table-view :options="options" :searchOption="searchOptions" :dialogParam="dialogParam" ></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const options = reactive([
            {label:'更新标题',value:'name'},
            {label:'版本名称',value:'version',type:'tags'},
            {label:'设备',value:'device',type:'dict_tags'},
            {label:'wgt',value:'is_wgt',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'更新标题',value:'name',where:'likeRight'},
            {label:'设备',value:'device',where:'like'},
            {label:'版本名称',value:'version',where:'like'},
            {label:'设备',value:'device',where:'like'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'更新标题',value:'name',span:24},
            {label:'版本名称',value:'version'},
            {label:'版本号',value:'version_code'},
            {label:'设备',value:'device',type:'select',viewType:'dict_tags'},
            {label:'Wgt',value:'is_wgt',type:'select',viewType:'dict_tags'},
            {label:'安装包',value:'url',type:'file',viewType:'text',span:24},
            {label:'苹果ID',value:'apple_id'},
            {label:'更新内容',value:'content',type:'editor',viewType:'html',span:24},
        ]

        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                version_code:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                device:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                is_wgt:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            dictData:{
                device:[
                    {label:'安卓',value:'android'},
                    {label:'苹果',value:'ios'},
                    {label:'小程序',value:'mini'},
                ],
                is_wgt:[
                    {label:proxy.$t('btn.yes'),value:1},{label:proxy.$t('btn.no'),value:0}
                ],
            },
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })
        return {options,searchOptions,dialogParam}
    }
}
</script>

<style>

</style>