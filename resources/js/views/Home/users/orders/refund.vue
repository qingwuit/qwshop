<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                申请售后
            </div>
            <div class="x20"></div>

            <el-form class="el_form" v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="data.formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="data.formData[v.value]" />
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content">
                        <el-form-item >
                            <el-tag style="margin-right:10px" type="info" v-for="(v,k) in data.error_list" :key="k" @click="changeTag(v)">{{v}}</el-tag>
                        </el-form-item>
                    </div></el-col>
                </el-row>

                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button color="#e50e19" :loading="loading" type="primary" @click="editData">{{$t('btn.determine')}}</el-button>
                            <el-button @click="$refs['addForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
            <el-empty v-else />

        </div>

    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import {useRoute,useRouter} from "vue-router"
export default {
    components:{},
    setup() {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const router = useRouter()
        const loading = ref(false)
        const data = reactive({
            formData:{
                order_id:0,
                refund_type:0,
                refund_remark:'',
            },
            error_list:[
                '物品破损',
                '尺码错误',
                '颜色不对',
                '其他原因',
            ],
        })
        // 表单配置 
        const addColumn = [
             {label:'售后类型',value:'refund_type',type:'radio'},
             {label:'售后原因',value:'refund_remark',type:'textarea'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            dictData:{
                refund_type:[{label:'退款',value:0},{label:'退货',value:1}]
            },
            rules:{
                refund_type:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                refund_remark:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            add:{column:addColumn},
        })

        const editData = ()=>{
            proxy.$refs.addForm.validate( async (valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    const resp = await proxy.R.post('/user/order/refund',data.formData)
                    if(!resp.code){
                        proxy.$message.success(proxy.$t('msg.success'))
                        router.push('/user/orders')
                    }
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const changeTag = (e)=>{
            data.formData.refund_remark = e
        }

        const loadData = async ()=>{
            data.formData.order_id = route.params.id
        }

        onMounted( async ()=>{
            loadData()
        })

        return {dialogParams,loading,data,editData,changeTag}
    },
   
};
</script>
<style lang="scss" scoped>

</style>