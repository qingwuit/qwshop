<template>
    <div class="qwit">
        <table-view :options="options" :handleWidth="210" :searchOption="searchOptions" :dialogParam="dialogParam">
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
import {reactive,ref,getCurrentInstance} from "vue"
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
        // 配置字段
        const options = reactive([
            {label:'头像',value:'avatar',type:'avatar',perView:true},
            {label:'昵称',value:'nickname'},
            {label:'用户名',value:'username',type:'tags'},
            {label:'余额',value:'money',type:'tags'},
            {label:'冻结资金',value:'frozen_money',type:'tags'},
            {label:'积分',value:'integral',type:'tags'},
            {label:'邀请人',value:'inviter_id',type:'dict_tags',labelName:'nickname',valueName:'id',},
            {label:'登陆时间',value:'last_login_time'},
            {label:'创建时间',value:'created_at'},
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'昵称',value:'nickname',where:'like'},
            {label:'用户名',value:'username',where:'like'},
            {label:'时间',value:'created_at',type:'daterange'},
        ])

        // 表单配置 
        const addColumn = [
            {label:'昵称',value:'nickname'},
            {label:'用户名',value:'username'},
            {label:'手机号',value:'phone'},
            {label:'密码',value:'password',type:'password'},
            {label:'支付密码',value:'pay_password',type:'password'},
            {label:'邀请人',value:'inviter_id',type:'table_select',viewType:'dict_tags',labelName:'nickname',valueName:'id',params:{},span:24,
            pageUrl:'/Admin/users',
            searchOptions:[
                {label:'昵称',value:'nickname',where:'like'},
                {label:'用户名',value:'username',where:'like'}
            ],
            options:[
                {label:'头像',value:'avatar',type:'avatar',perView:true},
                {label:'昵称',value:'nickname'},
                {label:'用户名',value:'username',type:'tags'},
                {label:'余额',value:'money',type:'tags'},
                {label:'冻结资金',value:'frozen_money',type:'tags'},
                {label:'积分',value:'integral',type:'tags'},
                {label:'登陆时间',value:'last_login_time'},
            ]},
            {label:'头像',value:'avatar',type:'avatar',perView:true,option:JSON.stringify({width:150,height:150})},
        ]
        let viewColumn = _.cloneDeep(addColumn)
        viewColumn = viewColumn.filter(item=>!item.type || item.type.indexOf('password') == -1)
        const dialogParam = reactive({
            rules:{
                username:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            dict:[
                {name:'inviter_id',url:'/Admin/users',selectDictByColumId:true,isPageDict:true},
            ],
            view:{column:viewColumn},
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        // 金额处理
        const moneyOpen = (e)=>{
            id.value = e
            editVis.value = !editVis.value
        }
        const updateData = ()=>{
            loading.value = true
            proxy.R.post('/Admin/users/money/handle',{id:id.value,is_type:selectType.value,money:money.value}).then(res=>{
                if(!res.code) location.reload()
            }).finally(()=>{
                loading.value = false
                editVis.value = false
            })
        }

        return {options,searchOptions,dialogParam,Coin,editVis,selectType,money,loading,moneyOpen,updateData}
    }
}
</script>

