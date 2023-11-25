<template>
    <div class="qw_table">
        <transition name="el-zoom-in-top"><layout-search v-show="searchVis" :options="searchOption" :dictData="dialogParams.dictData" @data="searchData" /></transition>
        <div class="table_top">
            <div class="table_btn_left">
                <el-button v-if="btnConfigs.store && btnConfigs.store.show" :disabled="btnConfigs.store && btnConfigs.store.disabled" type="primary" :icon="Plus" @click="openAddDialog">{{$t('btn.add')}}</el-button>
                <slot name="table_topleft_hook" :dialogParams="dialogParams" :listCount="listCount.data" :multipleSelectionData="multipleSelectionData" :multipleSelection="multipleSelection"></slot>
                <el-button v-if="btnConfigs.search && btnConfigs.search.show"  @click="searchOpen"  type="primary" plain :icon="Search">{{$t('btn.search2')}}</el-button>
            </div>
            <div class="table_btn_right">
                <el-upload class="import_file" ref="import_file" v-if="btnConfigs.import && btnConfigs.import.show" action :auto-upload="false" :show-file-list="false" :on-change="excelImport" :limit="1">
                    <el-button :icon="Upload">{{$t('btn.import')}}</el-button>
                </el-upload>
                <el-button v-if="btnConfigs.export && btnConfigs.export.show" :icon="Download" @click="excelExport">{{$t('btn.export')}}</el-button>
                <el-button v-if="btnConfigs.destroy && btnConfigs.destroy.show" type="danger" @click="deleteData" :icon="Delete">{{$t('btn.del')}}</el-button>
                <slot name="table_topright_hook" ></slot>
            </div>
        </div>
        <slot name="table_top_hook" ></slot>
        <slot name="table_main">
            <el-table ref="multipleTable" 
            :data="listData.listData||[]" border style="width: 100%" 
            :lazy="tableCfg.lazy"
            :row-key="columnId"
            :load="tableCfg.lazy?lazyLoad:null"
            @selection-change="handleSelectionChange" >
                <el-table-column type="selection" width="55" />
                <el-table-column v-for="(v,k) in options" 
                :key="k" 
                :label="v.label" 
                :width="v.value == 'created_at'?(v.width?v.width||null:'165px'):v.width||null" 
                :show-overflow-tooltip="v.overFlow||false"
                :fixed="v.fixed||null" >
                    <template #default="scope">
                        <!-- 默认展示文字 -->
                        <span v-if="v.type == 'text' || v.type == undefined">{{scope.row[v.value] || '-'}}</span>
                        <span v-if="v.type == 'money'"><em v-if="parseFloat(scope.row[v.value])>=0" class="moneycss paymoney">+{{scope.row[v.value] || '-'}}</em><em v-if="parseFloat(scope.row[v.value])<0" class="moneycss">{{scope.row[v.value] || '-'}}</em></span>
                        <q-tags v-if="v.type=='tags'" :value="scope.row[v.value]" />
                        <span v-if="v.type=='tags_array'"><q-tags  :styles="{marginRight:'8px'}" v-for="(arrItem,arrKey) in scope.row[v.value]" :key="arrKey" :value="arrItem" /></span>
                        <span v-if="v.type=='icon_tags'"><i :class="'icon_tags fa '+scope.row[v.value]" /><q-tags :value="scope.row[v.value]" /></span>
                        <span v-if="v.type == 'dict'">{{dictFind(v.value,scope.row[v.value],v.labelName||'label',v.valueName||'value')}}</span>
                        <q-tags v-if="v.type=='dict_tags'"  :tag_type="v.tag_type||false" :value="dictFind(v.value,scope.row[v.value],v.labelName||'label',v.valueName||'value')" />
                        <el-image v-if="v.type=='avatar' || v.type == 'image'" :style="v.style||{width:'50px',height:'50px',borderRadius:'4px',textAlign:'center',lineHeight:'65px',background:R.isEmpty(scope.row[v.value])?'#f2f2f2':'null',display:'block'}" :fit="v.fit||'fill'" :hide-on-click-modal="true" :src="scope.row[v.value]" lazy :preview-src-list="v.perView?[scope.row[v.value]]:null" :preview-teleported="v.perView?true:false">
                            <template #error>
                                <el-icon :color="v.errColor||'#888'" :size="v.errorPicSize||26"><Picture /></el-icon>
                            </template>
                        </el-image>
                        <div class="custom_column" v-if="v.type=='custom'"><slot :name="v.value" :scopeData="scope.row" ></slot></div>
                    </template>
                </el-table-column>
                <el-table-column label="操作" v-if="handleHide" :width="handleWidth" fixed="right">
                    <template #default="scope">
                        <slot name="table_column_button" >
                            <div class="table_col_handle">
                                <el-button :title="$t('btn.view')" v-if="btnConfigs.show && btnConfigs.show.show" :icon="View" @click="showData(scope.row)" />
                                <el-button :title="$t('btn.edit')" v-if="btnConfigs.update && btnConfigs.update.show" type="primary" @click="openEditDialog(scope.row)" :icon="Edit" />
                                <slot name="table_handleright_hook" :rows="scope.row"></slot>
                                <el-button v-if="btnConfigs.deletes && btnConfigs.deletes.show" type="danger" @click="deleteRowData(scope.row[columnId])" :icon="Delete" />
                            </div>
                        </slot>
                    </template>
                </el-table-column>
            </el-table>
        </slot>
        <div class="tabel_pagination" v-if="pagination">
            <!-- <span class="demonstration">Change page size</span> -->
            <el-pagination background 
            layout="total, sizes, prev, pager, next, jumper" 
            :page-size="listParams.per_page" 
            :page-sizes="paginationSize" 
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :page-count="listParams.last_page"
            :current-page="listParams.current_page"
            :total="listParams.total">
            </el-pagination>
        </div>

        <!-- 添加dialog -->
        <el-dialog :destroy-on-close="dialogParams.destroyOnClose" ref="addDialog" custom-class="table_dialog_class" v-model="addVis" :title="$t('btn.add')" :width="dialogParams.width" :draggable="dialogParams.draggable" :fullscreen="dialogParams.fullscreen">
            <template #header>
                <span class="el-dialog__title">{{$t('btn.add')}}</span>
                <span class="fullscreenbtn" @click="dialogParams.fullscreen=!dialogParams.fullscreen"><el-icon><FullScreen /></el-icon></span>
            </template>
            <slot name="table_add_hook" :dialogParams="dialogParams">
                <el-form v-if="dialogParams.add && dialogParams.add.column.length>0" ref="addForm" label-position="right" :rules="dialogParams.rules||null" :model="formData.add" :label-width="dialogParams.labelWidth">
                    <el-row :gutter="20">
                        <el-col v-for="(v,k) in dialogParams.add.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                            <el-form-item v-if="v.type!='custom'" :label="v.label" :prop="v.value">
                                <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData.add[v.value]" />
                            </el-form-item>
                            <el-form-item v-else :label="v.label" :prop="v.value">
                                <div class="custom_column" ><slot :name="'edit_'+v.value" :scopeData="formData.edit" ></slot></div>
                            </el-form-item>
                        </div></el-col>
                        <!-- <el-col :span="12"><div class="table-form-content"></div></el-col> -->
                    </el-row>

                    <!-- 按钮处理 -->
                    <el-row :gutter="20">
                        <el-col :span="24">
                            <el-form-item>
                                <el-button :loading="loading" type="primary" @click="storeData">{{$t('btn.determine')}}</el-button>
                                <el-button @click="addVis = false">{{$t('btn.cancel')}}</el-button>
                            </el-form-item>
                        </el-col>
                    </el-row>

                </el-form>
                <el-empty v-else />
            </slot>
        </el-dialog>

        <!-- 编辑dialog -->
        <el-dialog :destroy-on-close="dialogParams.destroyOnClose||true" ref="editDialog" custom-class="table_dialog_class" v-model="editVis" :title="$t('btn.edit')" :width="dialogParams.width" :draggable="dialogParams.draggable" :fullscreen="dialogParams.fullscreen">
            <template #header>
                <span class="el-dialog__title">{{$t('btn.edit')}}</span>
                <span class="fullscreenbtn" @click="dialogParams.fullscreen=!dialogParams.fullscreen"><el-icon><FullScreen /></el-icon></span>
            </template>
            <slot name="table_edit_hook" :dialogParams="dialogParams" :formData="formData">
                <el-form v-if="dialogParams.edit && dialogParams.edit.column.length>0" ref="editForm" label-position="right" :rules="dialogParams.rules||null" :model="formData.edit" :label-width="dialogParams.labelWidth">
                    <el-row :gutter="20">
                        <el-col v-for="(v,k) in dialogParams.edit.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                            <el-form-item v-if="v.type!='custom'" :label="v.label" :prop="v.value">
                                <q-input :params="v" :dictData="dialogParams.dictData||[]" v-model:formData="formData.edit[v.value]" />
                            </el-form-item>
                            <el-form-item v-else :label="v.label" :prop="v.value">
                                <div class="custom_column" ><slot :name="'edit_'+v.value" :scopeData="formData.edit" ></slot></div>
                            </el-form-item>
                        </div></el-col>
                        <!-- <el-col :span="12"><div class="table-form-content"></div></el-col> -->
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
                <el-empty v-else />
            </slot>
        </el-dialog>

        <!-- 显示dialog -->
        <el-dialog destroy-on-close ref="viewDialog" custom-class="table_dialog_class" v-model="viewVis" :title="$t('btn.view')" :width="dialogParams.width" :draggable="dialogParams.draggable" :fullscreen="dialogParams.fullscreen">
            <template #header>
                <span class="el-dialog__title">{{$t('btn.view')}}</span>
                <span class="fullscreenbtn" @click="dialogParams.fullscreen=!dialogParams.fullscreen"><el-icon><FullScreen /></el-icon></span>
            </template>
            <slot name="table_show_hook" :dialogParams="dialogParams" :formData="formData">
                <el-form v-if="dialogParams.view && dialogParams.view.column.length>0"  label-position="right"  :label-width="dialogParams.labelWidth">
                    <el-row :gutter="20">
                        <el-col v-for="(v,k) in dialogParams.view.column" :key="k" :span="v.span || dialogParams.span"><div class="table-form-content">
                            <el-form-item :label="v.label+' : '" :prop="v.value">
                                <q-tags v-if="v.viewType=='tag' || (v.viewType==undefined && v.type==undefined) || v.type=='number' || v.type=='datetime'  || v.type=='date' || v.type=='cascader_lazy' || v.type=='map'"  :value="formData.view[v.value]||''" :color="v.color||null" :effect="v.effect||null" :size="v.size||null" :isText="v.isText||false" />
                                <span v-if="v.viewType=='text'">{{formData.view[v.value]}}</span>
                                <div class="html_view" v-if="v.viewType=='html'" v-html="editorSplit(formData.view[v.value])"></div>
                                <span v-if="v.viewType=='tags_array'"><q-tags :styles="{marginRight:'8px'}" v-for="(arrItem,arrKey) in formData.view[v.value]" :key="arrKey" :value="arrItem" /></span>
                                <span v-if="v.type == 'dict' || v.viewType=='dict'">{{dictFind(v.value,formData.view[v.value],v.labelName||'label',v.valueName||'value')}}</span>
                                <q-tags v-if="v.type=='dict_tags' || v.viewType=='dict_tags'" :tag_type="v.tag_type||false" :value="dictFind(v.value,formData.view[v.value],v.labelName||'label',v.valueName||'value')" />
                                <el-image v-if="v.type=='avatar' || v.type == 'image'" :style="v.style||{width:'50px',height:'50px',borderRadius:'4px',textAlign:'center',lineHeight:'65px',background:R.isEmpty(formData.view[v.value])?'#f2f2f2':'null',display:'block'}" :size="100" :fit="v.fit||'fill'" :hide-on-click-modal="true" :src="formData.view[v.value]" lazy :preview-src-list="v.perView?[formData.view[v.value]]:null">
                                    <template #error>
                                        <el-icon :color="v.errColor||'#888'" :size="v.errorPicSize||26"><Picture /></el-icon>
                                    </template>
                                </el-image>
                                <div v-if="v.viewType=='images'">
                                    <el-image v-for="(imgItem,imgKey) in formData.view[v.value]" class="images_item" :key="imgKey" :style="v.style||{width:'50px',marginRight:'10px',height:'50px',borderRadius:'4px',textAlign:'center',lineHeight:'65px',background:R.isEmpty(formData.view[v.value])?'#f2f2f2':'null',display:'inline-block'}" :size="100" :fit="v.fit||'fill'" :hide-on-click-modal="true" :src="imgItem" lazy :preview-src-list="v.perView?formData.view[v.value]:null">
                                        <template #error>
                                            <el-icon :color="v.errColor||'#888'" :size="v.errorPicSize||26"><Picture /></el-icon>
                                        </template>
                                    </el-image>
                                </div>
                                <div class="custom_column" v-if="v.type=='custom' || v.viewType=='custom'"><slot :name="'view_'+v.value" :scopeData="formData.view" ></slot></div>
                            </el-form-item>
                        </div></el-col>
                        <!-- <el-col :span="12"><div class="table-form-content"></div></el-col> -->
                    </el-row>
                </el-form>
                <el-empty v-else />
            </slot>
            <slot name="table_show_bottom_hook" :dialogParams="dialogParams" :formData="formData"></slot>
        </el-dialog>
    </div>
</template>

<script>
import {reactive,ref,watch,provide,onMounted,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import layoutSearch from "@/components/common/search"
import { Plus,Edit,View,Delete,Download,Upload,Picture,Search,FullScreen  } from '@element-plus/icons'
import qTags from "@/components/common/tags"
export default {
    components: {layoutSearch,qTags,Picture,FullScreen},
    props:{
        // label , elabel , prop  显示字段
        searchOption:{
            type:Array,
            default:()=>{
                return []
            }
        },
        options:{
            type:Array,
            default:()=>{
                return []
            }
        },
        // tabl表数据
        tableData:{
            type:Array,
            default:()=>{
                return {}
            }
        },
        // table配置
        tableCfg:{
            type:Object,
            default:()=>{
                return {
                    lazy:false,
                    pid:'id',
                    lazyPid:'pid',
                }
            }
        },
        // 操作行宽度
        handleWidth:{
            type:Number,
            default:140
        }, // 操作栏宽度
        // 操作列是否显示
        handleHide:{
            type:Boolean,
            default:true
        },
        btnConfig:{
            type:Object,
            default:()=>{
                return {}
            }
        },
        // 默认获取的行唯一值
        columnId:{
            type:String,
            default:'id'
        },
        // Pagination 分页显示
        pagination:{
            type:Boolean,
            default:true
        },
        // 分页大小
        paginationSize:{
            type:Array,
            default:()=>{
                return [ 30, 100, 200, 300, 400 ]
            }
        },  
        // 列表数据URI
        pageUrl:{
            type:String,
            default:''
        },

        // 弹框Dialog参数控制
        dialogParam:{
            type:Object,
            default:()=>{}
        },

        // 自定义formData
        cutomFormData:{
            type:Object,
            default:()=>{}
        },

        // 搜索关键词和翻页数据
        params:{
            type:Object,
            default:()=>{
                return {}
            }
        }

    },
    setup(props,{emit}) {
        const {ctx,proxy} = getCurrentInstance()
        const route = useRoute()
        const store = useStore()
        const multipleSelection = reactive([])
        const multipleSelectionData = reactive([])
        const searchVis = ref(false)
        const listCount = reactive({data:{}})
        const listData = reactive({listData:[]})
        const viewData = reactive({data:{}})
        const addVis = ref(false) // 添加窗口状态
        const editVis = ref(false) // 添加窗口状态
        const viewVis = ref(false) // 查看窗口状态
        const loading = ref(false) // 按钮状态

        // 懒加载临时数据存储
        const lazyDataTmp = reactive({
            data:[],
            loadRow:[],
        })

        // 列表参数处理
        let queryParams = {
            per_page:30,// 每页大小
            total:0,
            last_page:1,
            page:1
        }
        queryParams = Object.assign(queryParams,props.params)
        const listParams = reactive(queryParams) // 分页还要搜索参数

        // 默认按钮状态
        let btnConfig = {
            show:{show:true,url:'',disabled:false}, // 显示
            store:{show:true,url:'',disabled:false}, // 添加
            update:{show:true,url:'',disabled:false}, // 编辑
            destroy:{show:true,url:'',disabled:false}, // 删除
            deletes:{show:false,url:'',disabled:false}, // 删除单行
            search:{show:true,url:'',disabled:false}, // 删除单行
            export:{show:true,url:'',disabled:false},
            import:{show:false,url:'',disabled:false},
        }
        btnConfig = Object.assign(btnConfig,props.btnConfig)
        const btnConfigs = reactive(btnConfig)

        // 弹框默认参数
        let dialogParam = {
            width:'50%',
            labelWidth:'90px', // 字体宽度
            span:12, // 默认宽度
            column:[], // 默认字段
            fullscreen:false, // 是否全屏
            draggable:false, // 是否可拖拽
            rules:null,
            destroyOnClose:true, // dialog 关闭时销毁Dom
            dict:[], // 字典链接 {name:"menus",url:'xxx.com',isPageDict:false,selectDictByColumId:false} isPageDict // 带分页的字典 selectDictByColumId // 字典根据当前页面数据ID查询
            dictData:{}, // 字典数据 {menus:[]}
            exportData:[],
            exportDataAdd:[],
            multipleSelection:()=>{
                return multipleSelection.value
            },
            addOpenBefore:()=>{}, // 打开添加之前执行
            editOpenBefore:()=>{}, // 打开编辑之前执行
            editOpenAfter:()=>{},
            closeDialog:()=>{
                closeDialog()
            }, // 关闭所有弹框
            reloadData:()=>{
                Object.assign(listParams,props.params)
                loadData() // 重加载列表数据
            },
            loadView:async (url = null)=>{ // 获取详情信息
                return await proxy.R.get(url!=null?url:pageUrl+'/'+row[props.columnId])
            },
            add:{
                column:[], // {label:'我的游戏',value:'name'},{label:'定位密钥',value:'name'},{label:'头像',value:'name'}
            },
            edit:{
                column:[],
            },
            view:{
                column:[],
            }
        }
        dialogParam = Object.assign(dialogParam,props.dialogParam)
        const dialogParams = reactive(dialogParam)
        // 字典处理
        const dictHandle = async ()=>{
            if(dialogParam.dict.length<=0) return
            
            dialogParam.dict.map(async item=>{
                let dictUrlParams = {}
                let columnIds = []
                if(item.selectDictByColumId && listData.listData.length>0){
                    listData.listData.map(listItem=>{
                        if(!proxy.R.isEmpty(listItem[item.name]) && listItem[item.name]!=0) columnIds.push(listItem[item.name])
                    })
                }
                if(item.selectDictByColumId){
                    if(!proxy.R.isEmpty(columnIds) && columnIds.length>0) dictUrlParams[props.columnId] = _.uniq(columnIds).join(',')+'|in'
                }
                let dictResp = await proxy.R.get(item.url,dictUrlParams)
                dialogParams.dictData[item.name] = item.isPageDict?dictResp['data']:dictResp
                if(item.addSelect) dialogParams.dictData[item.name].unshift(item.addSelect)
            })
        }
        // 根据键值字典名称获取对应label 最多三层
        const dictFind = (dictName,value,labelName='label',valueName='value')=>{
            let dictVal = '-'
            if(dialogParams.dictData[dictName] && dialogParams.dictData[dictName].length>0){
                dialogParams.dictData[dictName].map(item=>{
                    if(item[valueName] == value) dictVal = item[labelName]
                    if(item.children && item.children.length>0){
                        item.children.map(itemS=>{
                            if(itemS[valueName] == value) dictVal = itemS[labelName]
                            if(itemS.children && itemS.children.length>0){
                                itemS.children.map(itemT=>{
                                    if(itemT[valueName] == value) dictVal = itemT[labelName]
                                })
                            }
                        })
                    }
                })
            }
            return dictVal
        }
        // dictHandle()

        const editorSplit = (val)=>{
            if(!proxy.R.isEmpty(val)){
                const splitStr = '##qingwuit##'
                return val.split(splitStr)[0]
            }
        }

        let cutomFormData = {add:{},edit:{},view:{}}
        cutomFormData = Object.assign(cutomFormData,props.cutomFormData)
        const formData = reactive(cutomFormData)
        
        // const editForm = reactive({})

        // 如果没有传请求接口则默认取该路由链接为接口
        // let propRefs = toRefs(props) // 解构props
        let pageUrl = props.pageUrl // 组成链接
        if(proxy.R.isEmpty(props.pageUrl)) pageUrl = route.path

        

        // 选择数据
        const handleSelectionChange = (e)=>{
            let idArr = []
            let rows = []
            e.map(item=>{idArr.push(item[props.columnId]);rows.push(item)})
            multipleSelection.value = idArr
            multipleSelectionData.value = rows
        }
        // 修改页面大小
        const handleSizeChange = (e)=>{
            listParams.per_page = e
            emit('tableHandleSizeChange',listParams)
            loadData()
        }
        // 修改页面内容
        const handleCurrentChange = (e)=>{
            listParams.page = e
            emit('tableHandleCurrentChange',listParams)
            if(listParams.per_page) loadData()
        }

        // 获取列表信息
        const loadData = async ()=>{
            if(props.tableData.data){
                if(props.tableData.data.length == 0) return
                listCount.data = props.tableData.count
                listData.listData = props.tableData.data
                listParams.total = parseInt(props.tableData.total)
                listParams.per_page = parseInt(props.tableData.per_page)
                listParams.last_page = parseInt(props.tableData.last_page)
                listParams.page = parseInt(props.tableData.current_page)
                return
            }
            if(!props.tableCfg.lazy){
                const allData = await proxy.R.get(pageUrl,listParams)
                if(allData.headers && allData.request && typeof(allData.data) == 'string' && allData.data.indexOf('<!doctype html>')>-1) return
                listCount.data = allData.count
                listData.listData = allData.data
                listParams.total = parseInt(allData.total)
                listParams.per_page = parseInt(allData.per_page)
                listParams.last_page = parseInt(allData.last_page)
                listParams.page = parseInt(allData.current_page)
            }else{
                let childrenData = await getChildrenData(0)
                listData.listData = childrenData
                listParams.total = childrenData.length
            }

            // 重新加载字典
            dictHandle()
            
        }

        // 值发生改变
        watch(()=>props.tableData,(e)=>{
            if(props.tableData.data){
                listCount.data = props.tableData.count||0
                listData.listData = props.tableData.data||[]
                listParams.total = parseInt(props.tableData.total||0)
                listParams.per_page = parseInt(props.tableData.per_page||1)
                listParams.last_page = parseInt(props.tableData.last_page||1)
                listParams.page = parseInt(props.tableData.current_page||1)
                return
            }
            loadData()
        })

        const getChildrenData = async (pid=0)=>{
            listParams.isChildren = true
            listParams.pid = pid
            let childrenData = await proxy.R.get(pageUrl,listParams)
            childrenData.forEach(item=>{
                if(item.has_children != null){
                    item.hasChildren = true
                }
            })
            return childrenData
        }

        // 懒加载
        const lazyLoad = async (tree, treeNode, resolve)=>{
            let childRaw = await getChildrenData(tree[props.tableCfg.pid||'id'])
            // 先循环判断是否存在过
            if(lazyDataTmp.data.length == 0) lazyDataTmp.data.push({tree, treeNode, resolve, id:tree[props.columnId]})
            lazyDataTmp.data.map((lazyItem,lazyKey)=>{
                if(lazyItem.tree && lazyItem.tree[props.columnId] == tree[props.columnId]){
                    lazyDataTmp.data[lazyKey] = {tree, treeNode, resolve, id:tree[props.columnId]}
                }else{
                    lazyDataTmp.data.push({tree, treeNode, resolve, id:tree[props.columnId]})
                }
            })
            lazyDataTmp.data = _.uniqBy(lazyDataTmp.data,'id')
            // 查出来的数据都存放如临时变量中
            if(childRaw.length>0){
                childRaw.map(cItem=>{
                    let rowStr = ''+cItem[props.columnId]+'|'+cItem[props.tableCfg.lazyPid||'pid']
                    if(lazyDataTmp.loadRow.indexOf(rowStr) == -1) lazyDataTmp.loadRow.push(rowStr)
                })
            }
            
            resolve(childRaw)
        }

        // 懒加载自动更新
        const lazyAutoUpdate = (handleType)=>{
            try {
                if(!props.tableCfg.lazy) return
                // 循环查看是否有需要更新节点 
                let nodeState = false
                lazyDataTmp.data.map(item=>{
                    if(formData[handleType][props.tableCfg.lazyPid||'pid'] == item.tree[props.columnId]){
                        lazyLoad(item.tree,item.treeNode,item.resolve)
                        nodeState = true
                    }
                })
                // 节点未找到无法更新 则更新父节点
                if(!nodeState){
                    // 先查看之前查出来的数据是否存在这个父ID 如果存在再调
                    let loadRowLen = lazyDataTmp.loadRow.length
                    let parentIdVal = -1
                    for(let i=0;i<loadRowLen;i++){
                        let splitItems = lazyDataTmp.loadRow[i].split('|')
                        if(splitItems[0] == formData[handleType][props.tableCfg.lazyPid||'pid']){
                            parentIdVal = splitItems[1]
                            break
                        }
                    }
                    if(parentIdVal != -1){
                        lazyDataTmp.data.map(item=>{
                            if(parentIdVal == item.tree[props.columnId]){
                                lazyLoad(item.tree,item.treeNode,item.resolve)
                            }
                        })
                    }
                }
            } catch (error) {
                console.log('nodes update error.')
            }
        }

        const deleteLazyAutoUpdate = (row)=>{
            // 如果是删除的情况直接更新父节点
            try {
                if(!props.tableCfg.lazy) return
                if(row == undefined || row.length <= 0) return
                let pidArr = []
                if(typeof(row) != 'array') pidArr = [row]
                if(typeof(row) == 'array') pidArr = row
                pidArr = _.uniq(pidArr) // 去重复
                let loadRowLen = lazyDataTmp.loadRow.length
                if(pidArr.length <= 0) return
                pidArr.map(pidArrItem=>{
                    let parentIdVal = -1
                    for(let i=0;i<loadRowLen;i++){
                        let splitItems = lazyDataTmp.loadRow[i].split('|')
                        if(splitItems[0] == pidArrItem){
                            parentIdVal = splitItems[1]
                            break
                        }
                    }
                    if(parentIdVal != -1){
                        lazyDataTmp.data.map(async (item)=>{
                            if(parentIdVal == item.tree[props.columnId]){
                                proxy.$refs.multipleTable.store.states.lazyTreeNodeMap.value[parentIdVal] = []
                                await lazyLoad(item.tree,item.treeNode,item.resolve)
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error)
            }
            

        }

        // 打开添加编辑弹框
        const openAddDialog = ()=>{
            dialogParams.addOpenBefore()
            addVis.value = true
        }
        // 添加数据
        const storeData = ()=>{
            let keys = []
            for(let key in formData.add){
                keys.push(key)
            }
            dialogParam.add.column.map(item=>{
                let newAddData = {};
                if(_.indexOf(keys,item.value) == -1){
                    newAddData[item.value] = ''
                    Object.assign(formData.add,newAddData)
                }
            })
            
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.post(pageUrl,formData.add).then(res=>{
                        if(!res.code || !res.data || !res.msg){
                            loadData()
                            lazyAutoUpdate('add') // 懒加载自动更新
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

        // 查看数据
        const showData = async (row)=>{
            viewVis.value = true
            formData.view.id = row[props.columnId]
            const newViewData = await proxy.R.get(pageUrl+'/'+row[props.columnId])
            formData.view = newViewData
        }

        // 打开编辑弹框
        const openEditDialog = async (row)=>{
            dialogParams.editOpenBefore()
            formData.edit = {}
            editVis.value = true
            formData.edit = await proxy.R.get(pageUrl+'/'+row[props.columnId])
            await dialogParams.edit.column.map((item,key)=>{
                // placeholder关联字段
                if(item.relation) dialogParams.edit.column[key]['placeholder'] = formData.edit[item.relation]
            })
            dialogParams.editOpenAfter()
        }
        // 编辑数据
        const updateData = ()=>{
            proxy.$refs.editForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true

                // 找出相对应的数据存入，如不存在的不处理
                let newViewData = {}
                dialogParams.edit.column.map(item=>{
                    newViewData[item.value] = formData.edit[item.value]||''
                })
                formData.edit[props.columnId] = formData.edit[props.columnId]

                try {
                    proxy.R.put(pageUrl+'/'+formData.edit[props.columnId],formData.edit).then(res=>{
                        loading.value = false
                        if(!res.code || !res.data || !res.msg){
                            loadData()
                            lazyAutoUpdate('edit') // 懒加载自动更新
                            editVis.value = false
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

        // 关闭dialog
        const closeDialog = ()=>{
            addVis.value = false
            editVis.value = false
            viewVis.value = false
        }
        // 删除数据
        const deleteData = async ()=>{
            // console.log(ctx.$refs,proxy.$refs)
            if(!multipleSelection.value || multipleSelection.value.length<=0) return proxy.$message.error(proxy.$t('msg.selectErr'))
            let ids = multipleSelection.value.join(',')
            // let delParams = {}
            // delParams[props.columnId] = ids
            ElementPlus.ElMessageBox.confirm(
                proxy.$t('table.delmsg'),
                proxy.$t('table.deltit'),
                {
                    confirmButtonText: proxy.$t('btn.determine'),
                    cancelButtonText: proxy.$t('btn.cancel'),
                    type: 'warning',
                    // center: true,
                }
            ).then(async ()=>{
                await proxy.R.deletes(pageUrl+'/'+ids,{})
                loadData()
                deleteLazyAutoUpdate(multipleSelection.value) // 懒加载自动更新
                return proxy.$message.success(proxy.$t('msg.success')) // ElementPlus.ElMessage.error
            }).catch(()=>{})
        }
        // 删除一条数据
        const deleteRowData = async (id)=>{
            ElementPlus.ElMessageBox.confirm(
                proxy.$t('table.delmsg'),
                proxy.$t('table.deltit'),
                {
                    confirmButtonText: proxy.$t('btn.determine'),
                    cancelButtonText: proxy.$t('btn.cancel'),
                    type: 'warning',
                    // center: true,
                }
            ).then(async ()=>{
                await proxy.R.deletes(pageUrl+'/'+id,{})
                loadData()
                deleteLazyAutoUpdate(id) // 懒加载自动更新
                return proxy.$message.success(proxy.$t('msg.success')) // ElementPlus.ElMessage.error
            }).catch(()=>{})
        }

        // 搜索
        const searchData = (e)=>{
            Object.assign(listParams,e)
            // 清掉为空的字段
            Object.keys(listParams).map(delVal=>{
                if(proxy.R.isEmpty(listParams[delVal])) delete listParams[delVal]
            })  
            emit('searchChange',listParams)
            loadData()
        }
        const searchOpen = ()=>{
            if(props.searchOption && props.searchOption.length <= 0) return proxy.$message.error('wait setting.')
            searchVis.value = !searchVis.value
        }

        // excel导出
        const excelExport = ()=>{
            // 如果自定义导出数据则走自定义导出数据
            let exportOptions = _.cloneDeep(props.options)
            if(dialogParam.exportData.length > 0) exportOptions = dialogParam.exportData
            if(dialogParam.exportDataAdd.length > 0) exportOptions = _.concat(exportOptions,dialogParam.exportDataAdd)
            // 显示字段
            if(exportOptions.length<0) return proxy.$message.error(proxy.$t('msg.selectErr'))
            let excelExportData = []
            let headerData = []
            listData.listData.map(item=>{
                let colItem = []
                // 判断是否有选择数据 没有则取全部数据
                if(!multipleSelection.value || multipleSelection.value.length<=0){
                    headerData = []
                    exportOptions.map(itemOption=>{
                        let colItemVal = item[itemOption.value]||''
                        if(itemOption.type == 'dict_tags' || itemOption.type == 'dict'){
                            colItemVal = dictFind(itemOption.value,colItemVal,itemOption.labelName||'label',itemOption.valueName||'value')
                        }
                        colItem.push(colItemVal)
                        headerData.push(itemOption.label)
                    })
                    excelExportData.push(colItem)
                }else{
                    let ids = multipleSelection.value.join(',').split(',')
                    if(_.indexOf(ids,item[props.columnId]+'') > -1){
                        headerData = []
                        exportOptions.map(itemOption=>{
                            let colItemVal = item[itemOption.value]||''
                            if(itemOption.type == 'dict_tags' || itemOption.type == 'dict'){
                                colItemVal = dictFind(itemOption.value,colItemVal,itemOption.labelName||'label',itemOption.valueName||'value')
                            }
                            colItem.push(colItemVal)
                            headerData.push(itemOption.label)
                        })
                        excelExportData.push(colItem)
                    }
                }
                
            })
            excelExportData.unshift(headerData)
            const ws = XLSX.utils.aoa_to_sheet(excelExportData);
            const wb = XLSX.utils.book_new();
            // XLSX.utils.sheet_add_aoa(ws, [[1,2], [2,3], [3,4]], {origin: "A2"});
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            XLSX.writeFile(wb, (store.state.load.routeMenuName||'table')+".xlsx");
        }

        const ReadFile = async (file)=>{
            return new Promise(resolve => {
                let reader = new FileReader()
                reader.readAsBinaryString(file)
                reader.onload = ev => {  resolve(ev.target.result) }
            })
        }

        // excel导入
        const excelImport = async (fileRaw)=>{
            let dataBinary = await ReadFile(fileRaw.raw)    // 读取文件
            let workBook = XLSX.read(dataBinary, { type: "binary", cellDates: true })
            let workSheet = workBook.Sheets[workBook.SheetNames[0]];
            let xlsdata = XLSX.utils.sheet_to_json(workSheet);
            if (xlsdata.length == 0)  proxy.$refs["import_file"].clearFiles();
            if (xlsdata.length > 0){
                proxy.R.post(pageUrl+'/import/excel',xlsdata)
                .catch((errXls)=>{
                    console.error(errXls)
                }).finally(()=>{
                    proxy.$message.success(proxy.$t('msg.success'))
                })
            }
            emit('import',xlsdata)
        }

        

        // 初始化获取数据
        loadData()

        // 把这个对象返回回去
        emit('tableView',dialogParams)

        return {
            handleSelectionChange,
            handleSizeChange,
            handleCurrentChange,
            showData,viewData,
            storeData,updateData,formData,searchOpen,
            deleteData,deleteRowData,
            multipleSelection,multipleSelectionData,
            searchVis,addVis,editVis,viewVis,openEditDialog,openAddDialog,closeDialog,searchData ,loading,loadData,
            listCount,listData,listParams,lazyLoad,dictFind,editorSplit,
            btnConfigs,dialogParams,
            excelExport,excelImport,
            Plus,Edit,View,Delete,Download,Upload,Picture,Search,
            // routeMenuName:computed(()=>store.state.load.routeMenuName),
        }
    }

};
</script>
<style lang="scss" scoped>
.qw_table{
    height: 100%;
    .moneycss{
        color:rgb(66, 185, 131);
        &.paymoney{
            color:red;
        }
    }
    .table_top{
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between; /* 横向中间自动空间 */
        .table_btn_left{
        }
        .table_btn_right{
        }
    }
    .tabel_pagination{
        margin-top: 20px;
    }
    .icon_tags{
        margin-right: 10px;
    }
    .table_col_handle{
        text-align: center;
    }
    .html_view{
        // background: #f8f8f8;
        width: 100%;
        border-radius: 4px;
        border:1px solid #efefef;
        padding:5px;
    }
    .fullscreenbtn{
        position: absolute;
        right: 52px;
        top: 23px;
        color: var(--el-color-info);
        font-size: inherit;
        cursor: pointer;
        &:hover{
            color:var(--el-color-primary);
        }
    }
    .import_file{
        vertical-align: middle;
        display: inline-flex;
        margin-right: 12px;
    }
}
</style>
<style  lang="scss">
.table_dialog_class{
    .el-dialog__header{border-bottom: 1px solid #efefef;}
}
</style>