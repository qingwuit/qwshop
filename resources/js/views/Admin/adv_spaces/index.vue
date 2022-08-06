<template>
    <div class="qwit">
        <table-view :options="options" ref="adv_table" :searchOption="searchOptions" :dialogParam="dialogParam" :handleWidth="210">
            <template #table_handleright_hook="{rows}">
                <el-button type="success" @click="openAdv(rows.id)" :title="$t('btn.add')" :icon="Plus"  />
            </template>
            <template #table_show_bottom_hook="{formData}">
                <table-view :options="advOptions" :dialogParam="advParams" :handleHide="false" :pageUrl="'/Admin/advs?pid='+formData.view.id" :btnConfig="advBtnConfigs"></table-view>
            </template>
        </table-view>
        <!-- 增加广告 -->
        <el-dialog destroy-on-close  custom-class="table_dialog_class" v-model="editVis" :title="$t('btn.edit')" :width="'40%'">
            <el-form v-if="advParams.column.length>0" ref="advForms" label-position="right" :rules="advParams.rules||null" :model="advForm" :label-width="120" >
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in advParams.column" :key="k" :span="v.span||12"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="advParams.dictData||[]" v-model:formData="advForm[v.value]" />
                        </el-form-item>
                    </div></el-col>
                </el-row>
                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button :loading="loading" type="primary" @click="updateData">{{$t('btn.determine')}}</el-button>
                            <el-button @click="editVis = false">{{$t('btn.cancel')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import { Plus } from '@element-plus/icons'
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const editVis = ref(false)
        const loading = ref(false)
        const advForm = reactive({
            name:''
        })
        const options = reactive([
            {label:'广告位',value:'name'},
            {label:'宽度',value:'width'},
            {label:'高度',value:'height'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'广告位',value:'name',where:'likeRight'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'广告位',value:'name'},
            {label:'宽度',value:'width',type:'number'},
            {label:'高度',value:'height',type:'number'},
        ]
        const dialogParam = reactive({
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}]
            },
            view:{column:addColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        const advParams = reactive({
            dict:[
                {name:'pid',url:'/Admin/adv_spaces?per_page=100',isPageDict:true}

            ],
            dictData:{
                status:[{label:proxy.$t('config.web.open'),value:1},{label:proxy.$t('config.web.close'),value:0}],
            },
            column:[
                {label:'广告位',value:'pid',type:'select',labelName:'name',valueName:'id'},
                {label:'名称',value:'name'},
                {label:'链接',value:'url'},
                {label:'类型',value:'is_type'},
                {label:'图片',value:'image',span:24,type:'image',perView:true,name:'adv'},
                {label:'开始时间',value:'adv_start',type:'datetime'},
                {label:'结束时间',value:'adv_end',type:'datetime'},
                {label:'状态',value:'status',type:'select'},
                {label:'排序',value:'is_sort',type:'number'},
            ],
            dictData:{
                status:[{label:proxy.$t('config.web.open'),value:1},{label:proxy.$t('config.web.close'),value:0}],
            },
            rules:{
                pid:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                status:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                adv_start:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                adv_end:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                status:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}]
            },
        })

        const advOptions = reactive([
            {label:'广告位',value:'pid',type:'dict_tags',labelName:'name',valueName:'id'},
            {label:'图片',value:'image',type:'avatar',perView:true},
            {label:'名称',value:'name'},
            {label:'开始时间',value:'adv_start'},
            {label:'结束时间',value:'adv_end'},
            {label:'状态',value:'status',type:'dict'},
            {label:'创建时间',value:'created_at'},
        ])

        const advBtnConfigs = reactive({
            show:{show:false},
            store:{show:false},
            update:{show:false},
            destroy:{show:false},
            export:{show:false},
            search:{show:false},
        })
        
        const openAdv = async (e)=>{
            editVis.value = true
            let list = await proxy.R.get('/Admin/adv_spaces?per_page=100')
            advForm.pid = e
            advParams.dictData['pid'] = list.data
            
        }
        const updateData = ()=>{
            proxy.$refs.advForms.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.post('/Admin/advs',advForm).then(res=>{
                        loading.value = false
                        if(!res.code || !res.data || !res.msg){
                            editVis.value = false
                            proxy.$message.success(proxy.$t('msg.success'))
                        }
                    }).catch((err)=>{
                        console.log(err)
                    }).finally(()=>{
                        loading.value = false
                    })
                } catch (error) {
                    loading.value = false
                }
            })
        }
        return {options,searchOptions,dialogParam,editVis,advParams,advOptions,advForm,advBtnConfigs,openAdv,updateData,loading,Plus}
    }
}
</script>

<style>

</style>