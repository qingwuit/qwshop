<template>
    <div class="user_main table_lists">
        <div class="block_title">
            资金提现
            <span><div class="btn" @click="openAdd">申请提现</div></span>
        </div>
        <div class="x20 clear_line"></div>
        <table-view :handleWidth="80" :options="options" :handleHide="false" :dialogParam="dialogParams"  :btnConfig="btnConfigs" ></table-view>

        <!-- 编辑 -->
        <el-dialog destroy-on-close ref="addDialog" custom-class="table_dialog_class" v-model="data.addVis" :title="$t('btn.add')" :width="dialogParams.width||'50%'">
            <el-form v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="data.formData" :label-width="dialogParams.labelWidth" >
                <el-row :gutter="20">
                    <el-col :span="dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="'真实姓名'">{{data.check.name||'-'}}</el-form-item>
                    </div></el-col>
                    <el-col :span="dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="'银行名称'">{{data.check.bank_name||'-'}}</el-form-item>
                    </div></el-col>
                    <el-col :span="dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="'银行卡号'">{{data.check.card_no||'-'}}</el-form-item>
                    </div></el-col>
                    <el-col :span="dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="'可提现'"><span style="color:red">{{data.user.money||'0.00'}}</span></el-form-item>
                    </div></el-col>
                    <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="data.formData[v.value]" />
                        </el-form-item>
                    </div></el-col>
                </el-row>

                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item >
                            <el-button color="#e50e19" :loading="data.loading" type="primary" @click="storeData">{{$t('btn.determine')}}</el-button>
                            <el-button @click="data.addVis = false">{{$t('btn.cancel')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
            <el-empty v-else />
        </el-dialog>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const data = reactive({
            addVis:false,
            loading:false,
            check:{},
            user:{},
            formData:{
               money:0,
            },
        })
        
        // 配置字段
        const options = reactive([
            {label:'银行名称',value:'bank_name'},
            {label:'银行卡号',value:'card_no'},
            {label:'真实姓名',value:'name'},
            {label:'提现金额',value:'money',type:"tags"},
            {label:'状态',value:'cash_status',type:"dict_tags",tag_type:true},
            {label:'创建时间',value:'created_at'},
        ]);
   

        const btnConfigs = reactive({
            store:{show:false},
            update:{show:false},
            show:{show:false},
            search:{show:false},
            export:{show:false},
            destroy:{show:false},
        })

        const openAdd = async ()=>{
            let user = await store.dispatch('login/getUserSer')
            data.user = user
            data.check = await proxy.R.get('/user/check')
            if(!user.check){
                proxy.$message.error(proxy.$t('home.check.notCheck'))
                return proxy.$router.push('/user/check')
            }
            data.addVis = true
        }
        const storeData = ()=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                data.loading = true
                try {
                    proxy.R.post('/user/cashes',data.formData).then(res=>{
                        data.loading = false
                        if(!res.code){
                            data.addVis = false
                            proxy.$message.success(proxy.$t('msg.success'))
                             location.reload()
                        }
                    }).catch((err)=>{
                        console.log(err)
                    }).finally(()=>{
                        data.loading = false
                    })
                } catch (error) {
                    data.loading = false
                }
            })
        }

        // 表单配置 
        const addColumn = [
             {label:'提现金额',value:'money'},
        ]

        const dialogParams = reactive({
            span:12,
            labelWidth:'100px',
            rules:{
                money:[
                    {required:true,message:proxy.$t('msg.requiredMsg')}
                ]
            },
            dictData:{
                cash_status:[{label:'审核中',value:0},{label:'提现成功',value:1},{label:'拒绝提现',value:2}]
            },
            add:{column:addColumn},
        })

        return {options,btnConfigs,dialogParams,storeData,data,openAdd}
    }
}
</script>
<style lang="scss" scoped>

</style>