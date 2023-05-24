<template>
    <div class="filespace">
        <el-button type="primary" v-if="data.isDir" :icon="Plus" @click="openAddDialog">{{$t('btn.add')}}</el-button>
        <el-upload class="import_file" ref="import_file" v-if="!data.isDir" 
        :action="'/api'+uploadPath+'uploads'"
        :headers="{Authorization:Token}"
        :data="data.uploadOptions"
        :on-success="handleSuccess"
        :show-file-list="false" multiple >
            <el-button type="primary" :icon="Upload">{{$t('btn.import')}}</el-button>
        </el-upload>
        <el-button v-if="!data.isDir" @click="back">{{$t('btn.back')}}</el-button>
        <el-tag style="margin-left:10px">注意：上传商品主图时，请上传800*800</el-tag>
        <div class="file_dirs" v-if="data.isDir">
            <div class="items">
                <div class="dft_icon"><i class="fa fa-folder-open" /></div>
                <div class="btmtit" @click="toPic({id:0,name:'临时目录'})">临时目录</div>
            </div>
            <div class="items" v-for="(v,k) in data.dir" :key="k">
                <div class="dft_icon"><i class="fa fa-folder-open" /></div>
                <div class="handle_icon">
                    <i class="fa fa-edit" @click="openEditDialog(v)" />
                    <i class="fa fa-trash" @click="delDir(v.id)" />
                </div>
                <div class="btmtit" @click="toPic(v)">{{v.name}}</div>
            </div>
        </div>
        <div class="file_dirs" v-if="!data.isDir">
            <template  v-if="data.list.length > 0">
                <div class="items" v-for="(v,k) in data.list" :key="k">
                    <div class="handle_icon">
                        <i class="fa fa-trash" @click="delPic(v.id)" />
                    </div>
                    <el-image style="width:100%;height:100%" :src="v.url" fit="contain" hide-on-click-modal :preview-src-list="[v.url]"></el-image>
                    <div class="btmtit" :title="v.name">{{v.name}}</div>
                </div>
            </template>
            <el-empty style="text-align:center;width: 100%;" v-else />
        </div>

        <div class="tabel_pagination" v-if="!data.isDir">
            <!-- <span class="demonstration">Change page size</span> -->
            <el-pagination background 
            layout="total, sizes, prev, pager, next, jumper" 
            :page-size="data.params.per_page" 
            :page-sizes="[ 30, 100, 200, 300, 400 ]" 
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :page-count="data.params.last_page"
            :current-page="data.params.page"
            :total="data.params.total">
            </el-pagination>
        </div>

        <!-- 添加 -->
        <el-dialog destroy-on-close  custom-class="table_dialog_class" v-model="addVis" :title="$t('btn.add')" :width="'40%'">
            <el-form v-if="customParams.column.length>0" ref="dirAddForms" label-position="right" :rules="customParams.rules||null" :model="addForm" :label-width="100" >
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in customParams.column" :key="k" :span="v.span||12"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="customParams.dictData||[]" v-model:formData="addForm[v.value]" />
                        </el-form-item>
                    </div></el-col>
                </el-row>
                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button :loading="loading" type="primary" @click="addData">{{$t('btn.determine')}}</el-button>
                            <el-button @click="addVis = false">{{$t('btn.cancel')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </el-dialog>

        <!-- 编辑 -->
        <el-dialog destroy-on-close  custom-class="table_dialog_class" v-model="editVis" :title="$t('btn.edit')" :width="'40%'">
            <el-form v-if="customParams.column.length>0" ref="dirEditForms" label-position="right" :rules="customParams.rules||null" :model="editForm" :label-width="100" >
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in customParams.column" :key="k" :span="v.span||12"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="customParams.dictData||[]" v-model:formData="editForm[v.value]" />
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

<script setup>
import {reactive,ref,getCurrentInstance} from "vue"
import { Plus ,Upload} from '@element-plus/icons'
import {getToken,getUploadPath} from '@/plugins/config'

const {proxy} = getCurrentInstance()

const editVis = ref(false)
const addVis = ref(false)
const loading = ref(false)
const data = reactive({
    isDir:true, // 是否显示目录
    dirId:0, // 当前目录ID
    dir:[],
    list:[],
    params:{
        per_page:20,// 每页大小
        total:0,
        last_page:1,
        page:1
    },
    uploadOptions:{option:JSON.stringify({thumb:[[400,400],[300,300],[150,150]]}),name:'spaces'},
})
const addForm = reactive({
    name:''
})
const editForm = reactive({
    id:0,
    name:''
})

const customParams = reactive({
    column:[
        {label:'文件夹名称',value:'name',span:24},
    ],
    rules:{
        name:[{required:true,message:proxy.$t('msg.requiredMsg')}]
    },
})
const loadDir = ()=>{
    proxy.R.get('/Seller/file_space_dirs',{isAll:true}).then(res=>{
        if(!res.code) data.dir = res
    })
}

const loadDirPic = ()=>{
    data.params.dir_id = data.dirId
    proxy.R.get('/Seller/file_spaces',data.params).then(res=>{
        if(!res.code){
            data.list = res.data
            data.params.per_page = res.per_page
            data.params.total = res.total
            data.params.last_page = res.last_page
            data.params.page = res.page
        }
    })
}

const openAddDialog = ()=> addVis.value = true
const openEditDialog = (v)=>{
    editVis.value = true
    editForm.name = v.name
    editForm.id = v.id
}
const delDir = (id)=>{
    proxy.R.deletes('/Seller/file_space_dirs/'+id).then(res=>{
        if(!res.code) loadDir()
    })
  
}
const delPic = (id)=>{
    proxy.R.deletes('/Seller/file_spaces/'+id).then(res=>{
        if(!res.code) loadDirPic()
    })
}
const updateData = ()=>{
    proxy.$refs.dirEditForms.validate((valid)=>{
        // 验证失败直接断点
        if (!valid) return false
        loading.value = true
        try {
            proxy.R.put('/Seller/file_space_dirs/'+editForm.id,editForm).then(res=>{
                loading.value = false
                if(!res.code || !res.data || !res.msg){
                    editVis.value = false
                    proxy.$message.success(proxy.$t('msg.success'))
                    loadDir()
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
const addData = ()=>{
    proxy.$refs.dirAddForms.validate((valid)=>{
        // 验证失败直接断点
        if (!valid) return false
        loading.value = true
        try {
            proxy.R.post('/Seller/file_space_dirs',addForm).then(res=>{
                loading.value = false
                if(!res.code || !res.data || !res.msg){
                    addVis.value = false
                    proxy.$message.success(proxy.$t('msg.success'))
                    loadDir()
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
const back = ()=>{
    data.isDir = true
    data.dirId = 0
}
const toPic = (v)=>{
    data.dirId  = v.id
    data.uploadOptions.dir_id = v.id
    data.list = []
    loadDirPic()
    data.isDir  = false
    
}

// 修改页面大小
const handleSizeChange = (e)=>{
    data.params.per_page = e
    loadDirPic()
}
// 修改页面内容
const handleCurrentChange = (e)=>{
    data.params.page = e
    if(data.params.per_page) loadDirPic()
}
// 图片导入
const handleSuccess = (fileRaw)=>{
    loadDirPic()
}
const Token = getToken()
const uploadPath = getUploadPath()

loadDir()
</script>

<style lang="scss" scoped>
.file_dirs{
    display: flex;
    width: 100%;
    margin-top: 20px;
    .items{
        flex: 0 0 23%;
        border: 1px solid #efefef;
        height: 200px;
        margin-right: 2%;
        border-radius: 6px;
        position: relative;
        &:nth-child(4n){
            margin-right: 0;
        }
        .dft_icon{
            margin-top: 50px;
            font-size: 80px;
            justify-content: center;
            align-items: center;
            line-height: 200px;
            display: flex;
            color:#E6A23C;
        }
        .handle_icon{
            position: absolute;
            display: none;
            right: 10px;
            top: 10px;
            font-size: 20px;
            z-index: 800;
            i{
                margin-left:10px ;
                cursor: pointer;
            }
        }
        &:hover{
            .handle_icon{
                display: block;
            }
        }
        .btmtit{
            background: rgba(#000000, 0.6);
            color:#fff;
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            line-height: 35px;
            border-radius: 0 0 6px 6px;
            cursor: pointer;
            overflow: hidden;
            height: 35px;
            padding: 0 10px;
            box-sizing: border-box;
        }
    }
}
.tabel_pagination{
    margin-top: 20px;
}
.import_file{
    display: block;
    float: left;
    margin-right: 10px;
}
</style>