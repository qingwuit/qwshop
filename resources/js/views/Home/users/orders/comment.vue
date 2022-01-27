<template>
    <div class="user_main table_lists">
        <div class="block_title">
            订单评论
        </div>
        <div class="x20"></div>
        <el-form class="el_form" v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="formData" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
            <el-row :gutter="20">
                
                <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                    <el-form-item :label="v.label" :prop="v.value">
                        <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData[v.value]" />
                    </el-form-item>
                </div></el-col>
                <el-col :span="24"><div class="table-form-content-rate">
                    <el-form-item label="上传图片" prop="image">
                        <el-upload
                            :file-list="data.images"
                            :class="'uploader'"
                            :action="'/api'+uploadPath+'uploads'"
                            :list-type="'picture-card'"
                            :headers="{Authorization:Token}"
                            :data="{option:{},name:'comment'}"
                            :limit="4"
                            :on-remove="removeImg"
                            :on-success="handleAvatarSuccess"
                        >
                            <el-icon class="avatar-uploader-icon"><plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                </div></el-col>
                <el-col :span="24"><div class="table-form-content-rate">
                    <el-form-item label="综合评分" prop="score">
                        <el-rate v-model="formData.score" :score-template="'{value} 分'" text-color="#F7BA2A" show-score />
                    </el-form-item>
                </div></el-col>
                <el-col :span="24"><div class="table-form-content-rate">
                    <el-form-item label="描述相符" prop="agree">
                        <el-rate v-model="formData.agree" :score-template="'{value} 分'" text-color="#F7BA2A" show-score />
                    </el-form-item>
                </div></el-col>
                <el-col :span="24"><div class="table-form-content-rate">
                    <el-form-item label="服务态度" prop="service">
                        <el-rate v-model="formData.service" :score-template="'{value} 分'" text-color="#F7BA2A" show-score />
                    </el-form-item>
                </div></el-col>
                <el-col :span="24"><div class="table-form-content-rate">
                    <el-form-item label="发货速度" prop="speed">
                        <el-rate v-model="formData.speed" :score-template="'{value} 分'" text-color="#F7BA2A" show-score />
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
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
import {getToken,getUploadPath} from '@/plugins/config'
import { Plus } from '@element-plus/icons'
import { useStore } from 'vuex'
import { useRoute,useRouter } from 'vue-router'
export default {
    components:{Plus},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const store = useStore()
        const route = useRoute()
        const router = useRouter()
        const loading = ref(false)
        const formData = reactive({
            score:5,
            agree:5,
            service:5,
            speed:5,
            image:[],
        })

        const data = reactive({
            images:[]
        })
        // 表单配置 
        const addColumn = [
             {label:'评论内容',value:'content',type:'textarea'},
        ]

        const dialogParams = reactive({
            labelWidth:'180px',
            rules:{
                content:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            add:{column:addColumn},
        })

        const editData = ()=>{
            proxy.$refs.addForm.validate( async (valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    formData.order_id = route.params.id
                    const servUser = await proxy.R.post('/user/comments',formData)
                    if(!servUser.code){
                        proxy.$message.success(proxy.$t('msg.success'))
                        router.push('/user/orders')
                    }
                    loading.value = false
                } catch (error) {
                    loading.value = false
                }
            })
        }

        const handleAvatarSuccess = (e)=>{
            if(e.code == 200) formData.image.push(e.data)
        }

        const removeImg = (file)=>{
            if(file.response && file.response.data){
                formData.image.splice(formData.image.indexOf(file.response.data),1)
            }else{
                formData.image.splice(formData.image.indexOf(file.url),1)
            }
        }

    

        onMounted( async ()=>{
            // loadData()
        })

        const Token = getToken()
        const uploadPath = getUploadPath()

        return {data,dialogParams,loading,formData,editData,handleAvatarSuccess,removeImg,Token,uploadPath}
    }
}
</script>
<style lang="scss">
.table-form-content-rate{
    width: 529px;
    .el-form-item__content{
        line-height: 0;
    }
}
</style>