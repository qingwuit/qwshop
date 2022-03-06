<template>
    <div class="store_join w1200">
        <div class="step_bar">
            <div class="step">
                <div class="item success"><el-icon :size="16"><Reading /></el-icon>阅读协议</div>
                <div class="item check"><el-icon :size="16"><List /></el-icon>填写资料</div>
                <div class="item"><el-icon :size="16"><SetUp /></el-icon>等待审核</div>
                <div class="item"><el-icon :size="16"><CircleCheckFilled /></el-icon>审核通过</div>
            </div>
        </div>
        <el-divider style="width:420px;margin:0 auto;"><em style="font-size:20px;">入驻资料填写</em></el-divider>

        <div class="join_form">
            <el-form ref="editForm" label-position="right" :label-width="'150px'" :rules="data.dialogParams.rules||null" :model="data.formData">
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in data.dialogParams.column" :key="k" :span="v.span||12"><div class="table-form-content">
                        <el-form-item  :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="data.dialogParams.dictData||[]" v-model:formData="data.formData[v.value]" />
                        </el-form-item>
                    </div></el-col>
                </el-row>
            </el-form>
        </div>

        <div class="agreement_btn"><el-button color="#e50e19" type="primary" :loading="loading" @click="nextStep">确认资料，提交审核</el-button></div>
    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import router from '@/plugins/router'
import { Reading,CircleCheckFilled,List,SetUp } from '@element-plus/icons'
export default {
    components: {Reading,CircleCheckFilled,List,SetUp},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)
        const data = reactive({
            info:{},
            dialogParams:{
                dictData:{
                    class_id:[],
                    area:[],
                },
                rules:{
                    store_logo:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    store_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    class_id:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    store_company_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    area:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    store_address:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    business_license:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    business_license_no:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    legal_person:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    store_phone:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    id_card_no:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    id_card_t:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    id_card_b:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    emergency_contact:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                    emergency_contact_phone:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                },
                column:[
                    {label:'店铺图标',value:'store_logo',type:'avatar',option:JSON.stringify({width:150,height:150}), span:24},
                    {label:'店铺名称',value:'store_name'},
                    {label:'产品栏目',value:'class_id',type:'cascader',props:{multiple:true,label:'name',value:'id'}},
                    {label:'公司名称',value:'store_company_name'},
                    {label:'公司地址',value:'area',type:'cascader',props:{emitPath:true,label:'name',value:'id'}},
                    {label:'详细地址',value:'store_address',span:24,type:'textarea' ,maxlength:50},
                    {label:'营业执照',value:'business_license',span:24,type:'image',name:'store'},
                    {label:'统一社会信用代码',value:'business_license_no'},
                    {label:'法人',value:'legal_person'},
                    {label:'法人联系方式',value:'store_phone'},
                    {label:'身份证号码',value:'id_card_no'},
                    {label:'身份证(上)',value:'id_card_t',type:'image'},
                    {label:'身份证(下)',value:'id_card_b',type:'image'},
                    {label:'紧急联系人',value:'emergency_contact'},
                    {label:'紧急联系人电话',value:'emergency_contact_phone'},
                ],
              
            },
            formData:{},
            // editForm:{},
        })

        onMounted( async ()=>{
            const classData = await proxy.R.get('/load_goods_classes')
            const areaData = await proxy.R.get('/load_areas')
            data.formData = await proxy.R.get('/store')
            data.dialogParams.dictData.class_id = classData
            data.dialogParams.dictData.area = areaData
        })
        
        const nextStep = ()=>{
            
            proxy.$refs.editForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                data.formData.class_id = data.formData.class_id.filter((e)=>e.length>=3)
                // if(data.formData.area.length!=3) return proxy.$message.error(proxy.$t('msg.dataThr'))

                loading.value = true
                try {
                    data.formData.province_id = data.formData.area[0]||0
                    data.formData.city_id = data.formData.area[1]||0
                    data.formData.region_id = data.formData.area[2]||0
                    proxy.R.put('/store',data.formData).then(res=>{
                        loading.value = false
                        if(!res.code){
                            router.push('/store/step_3')
                        }
                    }).catch((err)=>{
                        console.error(err)
                    }).finally(()=>{
                        loading.value = false
                    })
                } catch (error) {
                    loading.value = false
                }
            })
        }
        return {nextStep,data,loading}
    }
}
</script>

<style lang="scss" scoped>
.join_form{
    margin:0 auto;
    margin-top: 80px;
}
.store_join{
    margin-top:40px;
}
.agreement_btn{
    margin:20px 0 10px 0;
    text-align: center;
}
.agreement_content{
    padding:20px;
    height: 600px;
}
.step{
    height: 46px;
    line-height: 46px;
    background: #F5F7FA;
    margin-bottom: 50px;
    display: flex;
    .item{
        flex: 1;
        font-size: 16px;
        color:#C0C4CC;
        text-align: center;
        border-right: 4px solid #fff;
        justify-content: center;
        align-items: center;
        display: flex;
        i{
            margin-right: 10px;
        }
        &.check{
            color:#333;
            font-weight: bold;
        }
        &.success{
            color:#67C23A;
            font-weight: bold;
        }
        &:last-child{
            margin-right: 0px;
        }
    }
}
</style>>

