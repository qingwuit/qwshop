<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                申请售后
            </div>
            <div class="x20"></div>

            <el-form class="el_form"  ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="data.formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
                <el-row :gutter="20">
                    <el-col  :span="24"><div class="table-form-content">
                        <el-form-item label="售后类型">
                            {{data.info.refund_type==0?'退款':(data.info.refund_type==1?'退换货':'售后结束')}}
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content">
                        <el-form-item label="售后状态">
                            <el-tag v-if="data.info.refund_verify==0">等待审核</el-tag>
                            <el-tag type="success" v-if="data.info.refund_verify==1">审核成功</el-tag>
                            <el-tag type="danger" v-if="data.info.refund_verify==2">申请拒绝</el-tag>
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content">
                        <el-form-item label="售后进度">
                            <el-tag type="danger" v-if="data.info.refund_verify==0">等待审核</el-tag>
                            <el-tag type="danger" v-if="data.info.refund_verify==2">拒绝申请</el-tag>
                            <el-tag type="warning" v-if="data.info.refund_step==0 && data.info.refund_verify==1">等待用户发货</el-tag>
                            <el-tag type="warning" v-if="data.info.refund_step==1">等待确认发货</el-tag>
                            <el-tag v-if="data.info.refund_step==2">等待用户确认</el-tag>
                            <el-tag type="success" v-if="data.info.refund_step==3">售后处理完成</el-tag>
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content" v-if="data.info.refund_type==1 && data.info.refund_verify==1  && data.info.refund_step==0">
                        <el-form-item label="填写寄回物流">
                            <el-input v-model="data.formData.delivery_no" />
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content" v-if="data.info.refund_verify==2">
                        <el-form-item label="拒绝原因">
                            {{data.info.refuse_remark}}
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content" v-if="data.info.delivery_no!=''">
                        <el-form-item label="用户寄回单号">
                            {{data.info.delivery_no}}
                        </el-form-item>
                    </div></el-col>
                    <el-col  :span="24"><div class="table-form-content" v-if="data.info.re_delivery_no!=''">
                        <el-form-item label="商家发货单号">
                            {{data.info.re_delivery_no}}
                        </el-form-item>
                    </div></el-col>
                </el-row>

                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button color="#e50e19" :loading="loading" type="primary" v-if="data.info.refund_step==0 && data.info.refund_type==1 && data.info.refund_verify==1" @click="editData">{{$t('btn.determine')}}</el-button>
                            <el-button :loading="loading" type="success" v-if="data.info.refund_step==2" @click="editData2">{{$t('btn.determine')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>

        </div>

    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import {useRoute,useRouter} from "vue-router"
export default {
    setup() {
        const {proxy} = getCurrentInstance()
        const route = useRoute()
        const router = useRouter()
        const loading = ref(false)
        const data = reactive({
            formData:{
                order_id:0,
            },
            info:{},
           
        })
        // 表单配置 
        const addColumn = [
    
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
                    const resp = await proxy.R.put('/user/order/refund/'+data.formData.order_id,data.formData)
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

        const editData2 = ()=>{
           proxy.R.put('/user/order/refund/'+data.formData.order_id,{refund_step:3}).then(res=>{
               if(!res.code){
                    proxy.$message.success(proxy.$t('msg.success'))
                    router.push('/user/orders')
                }
           })
        }



        const loadData = async ()=>{
            data.formData.order_id = route.params.id
            proxy.R.get('/user/order/refund/'+data.formData.order_id).then(res=>{
                if(!res.code) data.info = res
            })
        }

        onMounted( async ()=>{
            await loadData()
        })

        return {dialogParams,loading,data,editData,editData2}
    },
   
};
</script>
<style lang="scss" scoped>

</style>