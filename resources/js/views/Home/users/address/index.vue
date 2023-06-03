<template>
    <div class="user_address">
        <div class="user_main table_lists">
            <div class="block_title">
                <span><div class="btn" @click="addVis = true;isEdit=false">新增地址</div></span>
                收货地址
            </div>
            <div class="x20"></div>
            <div class="address_list" v-if="data.addresses.length>0">
                <ul>
                    <li v-for="(v,k) in data.addresses" :key="k">
                        <div class="pos_img"><img :src="v.is_default==1?require('@/assets/Home/address_pos2.png').default:require('@/assets/Home/address_pos.png').default" alt=""></div>
                        <div class="name">{{v.receive_name}}</div>
                        <div class="area_info">{{v.area_info+' '+v.address}}  <em style="font-weight:bold;margin-left:10px">({{v.receive_tel}})</em></div>
                        <div class="handle"><span v-if="!v.is_default" @click="set_default(v.id)">设置默认</span><em v-if="!v.is_default">|</em><span @click="openEdit(v.id)">编辑</span>|<span @click="del(v.id)">删除</span></div>
                    </li>
                </ul>
            </div>

            <el-empty v-else />

            <div class="fy" v-if="data.params.total>0">
                <el-pagination background 
                layout="total, prev, pager, next" 
                :page-size="data.params.per_page" 
                @current-change="handleCurrentChange"
                :page-count="data.params.last_page"
                :current-page="data.params.current_page"
                :total="data.params.total">
                </el-pagination>
            </div>
        </div>

        <!-- 编辑 -->
        <el-dialog destroy-on-close ref="addDialog" custom-class="table_dialog_class" v-model="addVis" :title="isEdit?$t('btn.edit'):$t('btn.add')" :width="dialogParams.width">
            <el-form v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="formData.add" :label-width="dialogParams.labelWidth" :fullscreen="dialogParams.fullscreen">
                <el-row :gutter="20">
                    <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                        <el-form-item :label="v.label" :prop="v.value">
                            <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData.add[v.value]" />
                        </el-form-item>
                    </div></el-col>
                </el-row>

                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button color="#e50e19" :loading="loading" type="primary" @click="isEdit?editData():storeData()">{{$t('btn.determine')}}</el-button>
                            <el-button @click="addVis = false">{{$t('btn.cancel')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
            <el-empty v-else />
        </el-dialog>

    </div>
</template>

<script>
import {reactive,ref,onMounted,getCurrentInstance} from "vue"
export default {
    components:{},
    setup(props) {
        const {proxy} = getCurrentInstance()
        const loading = ref(false)
        const addVis = ref(false)
        const isEdit = ref(false)
        // 表单配置 
        const addColumn = [
             {label:'收件人',value:'receive_name'},
             {label:'手机',value:'receive_tel'},
             {label:'地址信息',value:'area',type:'cascader',props:{emitPath:true,label:'name',value:'id'}},
             {label:'详细地址',value:'address'},
             {label:'设置默认',value:'is_default',type:'switch'},
        ]
        const data = reactive({
            addresses:[],
            params:{
                per_page:30,// 每页大小
                total:0,
                last_page:1,
                page:1,
            }
        })  

        const dialogParams = reactive({
            span:12,
            width:'50%',
            labelWidth:'120px',
            dictData:{
                area:[],
            },
            add:{column:addColumn},
            edit:{column:addColumn},
        })

        const formData = reactive({
            add:{},
        })

        const storeData = ()=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.post('/user/addresses',formData.add).then(res=>{
                        loading.value = false
                        if(!res.code || !res.data || !res.msg){
                            loadData()
                            addVis.value = false
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

        const editData = ()=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/user/addresses/'+formData.add.id,formData.add).then(res=>{
                        loading.value = false
                        if(!res.code || !res.data || !res.msg){
                            loadData()
                            addVis.value = false
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

        const set_default = (id)=>{
            try {
                proxy.R.get('/user/addresses/default/'+id).then(res=>{
                    if(!res.code || !res.data || !res.msg){
                        loadData()
                        proxy.$message.success(proxy.$t('msg.success'))
                    }
                }).catch((err)=>{
                    console.log(err)
                }).finally(()=>{
                })
            } catch (error) {
            }
        }

        const handleCurrentChange = (e)=>{
            data.params.page = e
            if(data.params.per_page) loadData()
        }

        const loadData = async ()=>{
            let resp = await proxy.R.get('/user/addresses?isResource=Home',data.params)
            if(!resp.code){
                data.addresses = resp.data
                data.params.total = parseInt(resp.total)
                data.params.per_page = parseInt(resp.per_page)
                data.params.last_page = parseInt(resp.last_page)
                data.params.current_page = parseInt(resp.current_page)
            }
        }

        onMounted( async () => {
            const areaData = await proxy.R.get('/load_areas')
            dialogParams.dictData.area = areaData
        })

        const openEdit = async (id)=>{
            let resp = await proxy.R.get('/user/addresses/'+id+'?isResource=Home')
            formData.add = resp
            addVis.value = true
            isEdit.value = true
        }

        const del = async (id)=>{
            let resp = await proxy.R.deletes('/user/addresses/'+id)
            if(!resp.code){
                proxy.$message.success(proxy.$t('msg.success'))
                loadData()
            }
        }

        loadData()
        return {
            data,dialogParams,formData,loading,addVis,isEdit,
            set_default,del,storeData,editData,openEdit,handleCurrentChange,
        }
    },
};
</script>
<style lang="scss" scoped>
.address_list{
    margin-bottom: 30px;
    ul li{
        border-bottom: 1px solid #efefef;
        padding:20px 0;
        position: relative;
        padding-left: 42px;
        &:first-child{
            padding-top: 0;
            .pos_img{
                left: 0;
                top:6px;
            }
            .handle{
                top:10px;
            }
        }
        .name{
            font-size: 16px;
            font-weight: bold;
        }
        .pos_img{
            position: absolute;
            left: 0;
            top:26px;
        }
        .handle{
            position: absolute;
            right: 0;
            top:28px;
            cursor: pointer;
            span{
                margin:5px;
                &:hover{
                    color:#ca151e;
                }
            }
        }
    }
}
</style>