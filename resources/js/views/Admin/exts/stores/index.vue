<template>
    <div class="qwit">
        <table-view :options="options" :btnConfig="btnConfigs" :params="params" :searchOption="searchOptions" :dialogParam="dialogParam"  :handleWidth="210">
            <template #table_topleft_hook="{dialogParams,listCount}">
                <el-badge class="badge_item">
                    <el-button :type="tabs.active == 4?'primary':null " @click="tabsClick(4,dialogParams)" >{{$t('store.verifyStatus.passVerify')}}</el-button>
                </el-badge>
                <el-badge :value="listCount.wait||0" :hidden="listCount.wait && listCount.wait>0 ?false:true" class="badge_item" :max="99">
                    <el-button :type="tabs.active == 2?'primary':null " @click="tabsClick(2,dialogParams)">{{$t('store.verifyStatus.verifying')}}</el-button>
                </el-badge>
                <el-badge :value="listCount.error||0" :hidden="listCount.error && listCount.error>0 ?false:true" class="badge_item" :max="99">
                    <el-button :type="tabs.active == 3?'primary':null " @click="tabsClick(3,dialogParams)">{{$t('store.verifyStatus.verifyError')}}</el-button>
                </el-badge>
                <el-badge :value="listCount.create||0" :hidden="listCount.create && listCount.create>0 ?false:true" class="badge_item" :max="99">
                    <el-button :type="tabs.active == 1?'primary':null " @click="tabsClick(1,dialogParams)">{{$t('store.verifyStatus.createStore')}}</el-button>
                </el-badge>
            </template>
            <template #table_handleright_hook="{rows}">
                <el-button type="success" @click="moneyOpen(rows.id)" :title="$t('btn.add')" :icon="Coin"  />
            </template>
        </table-view>
        <!-- 资金处理 -->
        <el-dialog destroy-on-close  custom-class="table_dialog_class" v-model="editVis" :title="$t('btn.edit')" :width="'30%'">
                <el-row :gutter="20">
                    <el-col :span="24"><div class="table-form-content_money">
                            <el-input v-model="money" type="number">
                                <template #prepend>
                                    <el-select v-model="selectType" placeholder="Select" style="width: 110px">
                                    <el-option :label="$t('user.money')" :value="0"></el-option>
                                    <el-option :label="$t('user.frozen_money')" :value="1"></el-option>
                                    <el-option :label="$t('user.integral')" :value="2"></el-option>
                                    </el-select>
                                </template>
                                <template #append>
                                    {{$t('btn.money')}}
                                </template>
                            </el-input>
                    </div></el-col>
                </el-row>
                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24"  style="margin-top:20px;">
                        <div style="text-align:center">
                            <el-button :loading="loading" type="primary" @click="updateData">{{$t('btn.determine')}}</el-button>
                            <el-button @click="editVis = false">{{$t('btn.cancel')}}</el-button>
                        </div>
                    </el-col>
                </el-row>
        </el-dialog>
    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import { Coin } from '@element-plus/icons'
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const editVis = ref(false)
        const loading = ref(false)
        const selectType = ref(0)
        const money = ref(1)
        const id = ref(0)
        const options = reactive([
            {label:'店铺图标',value:'store_logo',type:'avatar'},
            {label:'店铺名称',value:'store_name'},
            {label:'地区',value:'area_info'},
            {label:'状态',value:'store_status',type:'dict_tags'},
            {label:'审核',value:'store_verify',type:'dict_tags'},
            {label:'创建时间',value:'created_at'},
        ]);

        // tabs
        const tabs = reactive({
            active:4,
        })

        // 搜索字段
        const searchOptions = reactive([
            {label:'店铺名称',value:'store_name',where:'like'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'店铺名称',value:'store_name'},
            {label:'店铺图标',value:'store_logo',type:'avatar',span:24},
            {label:'产品栏目',value:'class_id',span:24,type:'cascader',props:{multiple:true,label:'name',value:'id'}},
            {label:'公司名称',value:'store_company_name'},
            {label:'公司地址',value:'area',type:'cascader',props:{emitPath:true,label:'name',value:'id'}},
            {label:'详细地址',value:'store_address',span:24},
            {label:'店铺详情',value:'store_description',span:24},
            {label:'营业执照',value:'business_license',span:24,type:'image',name:'store'},
            {label:'统一社会信用代码',value:'business_license_no'},
            {label:'法人',value:'legal_person'},
            {label:'法人联系方式',value:'store_phone'},
            {label:'身份证号码',value:'id_card_no'},
            {label:'身份证(上)',value:'id_card_t',type:'image'},
            {label:'身份证(下)',value:'id_card_b',type:'image'},
            {label:'紧急联系人',value:'emergency_contact'},
            {label:'紧急联系人电话',value:'emergency_contact_phone'},
            {label:'状态',value:'store_status',type:'select',viewType:'dict'},
            {label:'审核',value:'store_verify',type:'select',viewType:'dict'},
        ]

        let viewColumn = _.cloneDeep(addColumn)
        viewColumn[4] = {label:'公司地址',value:'area_info'}
        viewColumn.push({label:'资金余额',value:'store_money'})
        viewColumn.push({label:'冻结资金',value:'store_frozen_money'})
        const dialogParam = reactive({
            labelWidth:130,
            dict:[
                {name:'area',url:'/load_areas'}
            ],
            dictData:{
                store_status:[{label:proxy.$t('config.web.open'),value:1},{label:proxy.$t('config.web.close'),value:0}],
                store_verify:[
                    {label:proxy.$t('store.verifyStatus.createStore'),value:1},
                    {label:proxy.$t('store.verifyStatus.verifying'),value:2},
                    {label:proxy.$t('store.verifyStatus.verifyError'),value:3},
                    {label:proxy.$t('store.verifyStatus.passVerify'),value:4},
                ],
            },
            rules:{
                store_name:[{required:true,message:'不能为空'}],
                class_id:[{required:true,message:'不能为空'}]
            },
            view:{column:viewColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        const btnConfigs = reactive({
            store:{show:false}
        })

        onMounted(async () => {
            dialogParam.dictData.class_id = await proxy.R.get('/load_goods_classes')
        })

        const params = reactive({
            store_verify:4,
            isResource:'Admin',
        })

        const tabsClick = (e,dialogParams)=>{
            tabs.active = e
            params.store_verify = e
            dialogParams.reloadData()
        }

        // 金额处理
        const moneyOpen = (e)=>{
            id.value = e
            editVis.value = !editVis.value
        }
        const updateData = ()=>{
            loading.value = true
            proxy.R.post('/Admin/users/money/handle',{id:id.value,is_type:selectType.value,money:money.value,is_belong:1}).then(res=>{
                if(!res.code) location.reload()
            }).finally(()=>{
                loading.value = false
                editVis.value = false
            })
        }

        return {options,dialogParam,searchOptions,params,btnConfigs,tabs,Coin,editVis,loading,selectType,money,tabsClick,updateData,moneyOpen}
    }
}
</script>

<style>

</style>