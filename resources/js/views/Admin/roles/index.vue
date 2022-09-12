<template>
    <!-- 自定义权限管理页面- 暂时先不写控制按钮的权限 --> 
    <div class="qwit">
        <table-view ref="tableView" :searchOption="searchOptions" :options="options" :dialogParam="customParams">
            <template #table_add_hook="{dialogParams}">
                <div class="table_add_hook_block">
                    <el-form ref="addForm" label-position="top" :model="roleData.addForm" :rules="roleData.rules">
                        <el-form-item :label="$t('table.roleName')" prop="name"><el-input v-model="roleData.addForm.name" /></el-form-item>
                    </el-form>

                    <!-- 折叠 -->
                    <el-collapse :model-value="'1'" accordion>
                        <el-collapse-item name="1">
                            <template #title><el-icon style="margin-right:10px"><i class="fa fa-list-ul" /></el-icon>{{$t('table.roleMenus')}}</template>
                            <div class="table_dialog_hook">
                                <div class="left_menu">
                                    <el-tree :props="menuProps.props" :data="roleData.menus" node-key="id" show-checkbox @check="handleCheckChange" >
                                        <template #default="{ node, data }">
                                            <span>{{ node.label }}</span>
                                            <span class="tree_content">{{ data.content || ' - '+$t('table.roleEmptyContent') }}</span>
                                        </template>
                                    </el-tree>
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="2">
                            <template #title><el-icon style="margin-right:10px"><i class="fa fa-share-alt" /></el-icon>{{$t('table.rolePermissions')}}</template>
                            <div class="table_dialog_hook">
                                <div class="left_menu">
                                    <div class="group_per_all"><el-checkbox v-model="roleData.selectAll" @change="selectAllCheck" :indeterminate="roleData.isIndeterminate">{{$t('btn.selectAll')}}</el-checkbox></div>
                                    <el-checkbox-group v-model="roleData.checkList" @change="permissionCheck">
                                        <div class="group_perblock" v-for="(v,k) in roleData.permissions || []" :key="k">
                                            <div class="group_per_title"><span>{{v.name}}</span><span @click="groupCheck(v)">{{$t('table.roleSelectAll')}}</span></div>
                                            <el-row :gutter="10">
                                                <el-col  :sm="12" :md="8"  v-for="(vo,key) in v.permissions || []" :key="key">
                                                    <el-checkbox :label="vo.id" >{{vo.name}}</el-checkbox>
                                                </el-col>
                                            </el-row>
                                        </div>
                                    </el-checkbox-group>
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                    <div class="table_btn">
                        <el-button :loading="loading" type="primary" @click="storeData(dialogParams)">{{$t('btn.determine')}}</el-button>
                        <el-button @click="dialogParams.closeDialog()">{{$t('btn.cancel')}}</el-button>
                    </div>
                </div>
            </template>
            <template #table_edit_hook="{dialogParams,formData }">
                {{formDataChange(formData)}}
                <div class="table_add_hook_block">
                    <el-form ref="editForm" label-position="top" :model="roleData.editForm" :rules="roleData.rules">
                        <el-form-item :label="$t('table.roleName')" prop="name"><el-input v-model="roleData.editForm.name" /></el-form-item>
                    </el-form>

                    <!-- 折叠 -->
                    <el-collapse :model-value="'1'" accordion>
                        <el-collapse-item name="1">
                            <template #title><el-icon style="margin-right:10px"><i class="fa fa-list-ul" /></el-icon>{{$t('table.roleMenus')}}</template>
                            <div class="table_dialog_hook">
                                <div class="left_menu">
                                    <el-tree ref="roleTree" :props="menuProps.props" :data="roleData.menus" node-key="id" show-checkbox @check="handleCheckChange" >
                                        <template #default="{ node, data }">
                                            <span>{{ node.label }}</span>
                                            <span class="tree_content">{{ data.content || ' - '+$t('table.roleEmptyContent') }}</span>
                                        </template>
                                    </el-tree>
                                </div>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="2">
                            <template #title><el-icon style="margin-right:10px"><i class="fa fa-share-alt" /></el-icon>{{$t('table.rolePermissions')}}</template>
                            <div class="table_dialog_hook">
                                <div class="left_menu">
                                    <div class="group_per_all"><el-checkbox v-model="roleData.selectAll" @change="selectAllCheck" :indeterminate="roleData.isIndeterminate">{{$t('btn.selectAll')}}</el-checkbox></div>
                                    <el-checkbox-group v-model="roleData.checkList" @change="permissionCheck">
                                        <div class="group_perblock" v-for="(v,k) in roleData.permissions || []" :key="k">
                                            <div class="group_per_title"><span>{{v.name}}</span><span @click="groupCheck(v)">{{$t('table.roleSelectAll')}}</span></div>
                                            <el-row :gutter="10">
                                                <el-col  :sm="12" :md="8"  v-for="(vo,key) in v.permissions || []" :key="key">
                                                    <el-checkbox :label="vo.id" >{{vo.name}}</el-checkbox>
                                                </el-col>
                                            </el-row>
                                        </div>
                                    </el-checkbox-group>
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                    <div class="table_btn">
                        <el-button :loading="loading" type="primary" @click="updateData(dialogParams)">{{$t('btn.determine')}}</el-button>
                        <el-button @click="dialogParams.closeDialog()">{{$t('btn.cancel')}}</el-button>
                    </div>
                </div>
            </template>
        </table-view>
    </div>
</template>

<script>
import {reactive,ref ,watch,nextTick ,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        // 配置字段
        const options = reactive([
            {label:'角色名称',value:'name'}
        ]);

        // 搜索字段
        const searchOptions = reactive([
            {label:'角色名称',value:'name',where:'likeRight'},
        ])

        // 视图配置 
        const addColumn = [
            {label:'角色名称',value:'name'},
        ]

        // 弹框配置
        const customParams = reactive({
            view:{column:addColumn},
            addOpenBefore:()=>{
                loadPermission()
                loadNode()
                roleData.checkList = []
                roleData.permissions = []
                roleData.addForm = {}
            },
            editOpenBefore:()=>{
                loadPermission()
                loadNode()
            },
        })

        // 树节点配置信息
        const menuProps = reactive({
            props:{
                label:'name',
                value:'id',
                isLeaf: 'hasChildren',
            },
            btnData:[
                {name:proxy.$t('btn.add'),value:'1'},
                {name:proxy.$t('btn.view'),value:'2'},
                {name:proxy.$t('btn.edit'),value:'3'},
                {name:proxy.$t('btn.del'),value:'4'},
                {name:proxy.$t('btn.import'),value:'5'},
                {name:proxy.$t('btn.export'),value:'6'},
            ],
        })
        const loading = ref(false)

        // 数据
        const roleData = reactive({
            addForm:{},
            editForm:{},
            rules:{
                name:[{required:true,message:proxy.$t('msg.requiredMsg')}]
            },
            selectAll:false,
            isIndeterminate:false,
            checkList:[],
            permissions:[],
            permissionLength:0,
            permissionAllId:[],
            menus:[],
        })

        // 节点被改变
        const handleCheckChange = (data,all)=>{
            roleData.addForm.menu_id = all.checkedKeys || []
            roleData.editForm.menu_id = all.checkedKeys || []
            roleData.editForm.menu_id = _.concat(roleData.editForm.menu_id,proxy.$refs.roleTree.getHalfCheckedKeys())
        }

        // 全选按钮被点击
        const selectAllCheck = (e)=>{
            roleData.isIndeterminate = false
            if(!e) roleData.checkList = []
            if(e) roleData.checkList = roleData.permissionAllId
        }

        // 加载节点信息
        const loadNode =  async ()=>{
            roleData.menus = await proxy.R.get('/Admin/load_menu?type=getChildren')
        }

        // 加载接口信息
        const loadPermission = async ()=>{
            roleData.permissions = await proxy.R.get('/Admin/permission_groups?isAll=true&isWith=permissions')
            let permissionLength = 0
            let permissionAllId = []
            roleData.permissions.map(item=>{
                if(item.permissions && item.permissions.length && item.permissions.length>0){
                    permissionLength += item.permissions.length
                    item.permissions.map((items)=>{
                        permissionAllId.push(items.id)
                    })
                }
            })
            roleData.permissionLength = permissionLength
            roleData.permissionAllId = permissionAllId
        }

        // 权限接口被改变
        const permissionCheck = async (e)=>{
            // console.log(e)
        }

        // 添加
        const storeData = (dialogParams)=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.post('/Admin/roles',roleData.addForm).then(res=>{
                        if(!res.code){
                            dialogParams.reloadData()
                            dialogParams.closeDialog()
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

        // 编辑
        const updateData = (dialogParams)=>{
            proxy.$refs.editForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    roleData.editForm.permissions = null
                    roleData.editForm.menus = null
                    // console.log(roleData.editForm)
                    proxy.R.put('/Admin/roles/'+roleData.editForm.id||0,roleData.editForm).then(res=>{
                        if(!res.code){
                            dialogParams.reloadData()
                            dialogParams.closeDialog()
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

        // solt父组件传过来的数据做接收
        const formDataChange = (e)=>{
            let permissions = []
            let menus = []
            roleData.editForm = e.edit || {}
            if(e.edit && e.edit.permissions){
                e.edit.permissions.map(item=>permissions.push(item.id))
            }
            if(e.edit && e.edit.menus){
                e.edit.menus.map(item=>menus.push(item.id))
            }
            roleData.checkList = permissions
            roleData.editForm.permission_id = permissions
            nextTick(()=>{
                roleData.editForm.menu_id = menus
                setTimeout(()=>{
                    menus.map(item=>{
                        let node = proxy.$refs.roleTree.getNode(item);
                        if(node && node.isLeaf){
                            proxy.$refs.roleTree.setChecked(node, true)
                        }
                    })
                },200);
            })
            
        }

        // 分组全选
        const groupCheck = (row)=>{
            let groupIds = []
            if(!row.permissions) return
            row.permissions.map(item=>groupIds.push(item.id))
            if(groupIds.length==0) return
            let isAllSelect = true
            groupIds.map(item=>{
                if(_.indexOf(roleData.checkList,item) == -1) isAllSelect = false
            })
            if(!isAllSelect){
                roleData.checkList = _.concat(roleData.checkList,groupIds)
                roleData.checkList = _.uniq(roleData.checkList)
            }else{
                _.remove(roleData.checkList,(item)=>{
                    return _.indexOf(groupIds,item)>-1
                })
            }
        }


        watch(() => roleData.checkList,(e)=>{
            if(e.length>0 && e.length < roleData.permissionLength){
                roleData.selectAll = true
                roleData.isIndeterminate = true
            }
            if(e.length>0 && e.length == roleData.permissionLength){
                roleData.selectAll = true
                roleData.isIndeterminate = false
            }
            if(e.length==0){
                roleData.selectAll = false
                roleData.isIndeterminate = false
            }

            roleData.addForm.permission_id = e
            roleData.editForm.permission_id = e
        },{deep:true})

        return {
            options,searchOptions,menuProps,roleData,loading,customParams,
            storeData,updateData,handleCheckChange,loadNode,permissionCheck,selectAllCheck,formDataChange,groupCheck
        }
    }
}
</script>

<style lang="scss" scoped>
.table_add_hook_block{width: 100%;}
.table_dialog_hook{display: flex;width: 100%;justify-content: space-between;}
.left_menu_title,.right_permission_title{font-size: 14px;margin-bottom: 10px;flex: 0 1 49%;}
.left_menu{
    min-height: 250px;
    border:1px solid #efefef;
    // flex: 0 1 49%;
    flex: 1;
    box-sizing: border-box;
    padding:10px 8px;
}

.tree_content{
    color:#ccc;
    font-size: 12px;
    margin-left: 10px;
}
.table_btn{
    text-align: center;
    margin-top: 20px;
}
.group_per_title{
    background: #efefef;
    padding: 5px 24px;
    box-sizing: border-box;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    margin-bottom: 5px;
    height: 36px;
    span{
        flex: 1;
        font-size: 14px;
        &:last-child{
            text-align: right;
            cursor: pointer;
            color: #409eff;
            // text-decoration: underline;
        }
    }
}
</style>
<style lang="scss">

</style>