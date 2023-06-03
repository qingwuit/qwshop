<template>
    <div class="goods_form">
        <div class="step_bar">
            <div class="step">
                <div :class="{item:true,check:data.step == 0,success:data.step>0} "><el-icon :size="16"><Reading /></el-icon>选择类目</div>
                <div :class="{item:true,check:data.step == 1,success:data.step>1}"><el-icon :size="16"><List /></el-icon>编辑商品</div>
                <div :class="{item:true,check:data.step == 3,success:data.step>3}"><el-icon :size="16"><SetUp /></el-icon>规格编辑</div>
                <div class="item"><el-icon :size="16"><CircleCheckFilled /></el-icon>发布商品</div>
            </div>
        </div>

        <div class="goods_chose" v-if="data.step == 0">
            <div class="goods_add_chose_class_bg">
                <div class="chose_class_bg_item">
                    <el-scrollbar height="378px">
                        <ul><li @click="chose(v,0,k)" v-for="(v,k) in data.classList" :key="k" :class="data.choseId[0] == v.id?'checked':''">{{v.name}}<el-icon><ArrowRight /></el-icon></li></ul>
                    </el-scrollbar>
                </div>
                <div :class="data.choseId[0]==0?'chose_class_bg_item disabled':'chose_class_bg_item'">
                    <el-scrollbar height="378px">
                        <ul><li @click="chose(v,1,k)" v-for="(v,k) in data.choseId[0]==0?[]:data.classList[data.index[0]].children" :key="k" :class="data.choseId[1] == v.id?'checked':''">{{v.name}}<el-icon><ArrowRight /></el-icon></li></ul>
                    </el-scrollbar>
                </div>
                <div :class="data.choseId[1]==0?'chose_class_bg_item disabled':'chose_class_bg_item'">
                    <el-scrollbar height="378px">
                        <ul><li @click="chose(v,2,k)" v-for="(v,k) in data.choseId[1]==0?[]:data.classList[data.index[0]].children[data.index[1]].children" :key="k" :class="data.choseId[2] == v.id?'checked':''">{{v.name}}<el-icon><ArrowRight /></el-icon></li></ul>
                    </el-scrollbar>
                </div>
            </div>

            <div class="chose_class_btn">
                <el-button type="primary" :disabled="data.choseId[0]==0 || data.choseId[1]==0 || data.choseId[2]==0" @click="nextStep(1)">{{$t('btn.goodsNext')}}</el-button>
                <el-button @click="goodsBack">{{$t('btn.back')}}</el-button>
            </div>
        </div>

        <div class="goods_form_item" v-if="data.step == 1">
            <el-form ref="addForm" label-position="right" :model="data.form" :rules="data.rules" label-width="80px">
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item :label="'商品分类'">
                            <el-breadcrumb style="line-height:28px;background:#f4f4f4;padding-left:10px;">
                                <el-breadcrumb-item v-for="(v,k) in data.choseItem" :key="k">{{v.name}}</el-breadcrumb-item>
                                <el-breadcrumb-item >
                                    <el-button size="small" @click="data.step=0" >{{$t('btn.edit')}}</el-button>
                                </el-breadcrumb-item>
                            </el-breadcrumb>
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'商品标题'" prop="goods_name"><el-input v-model="data.form.goods_name" /></el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'副标题'" prop="goods_subname"><el-input type="textarea" v-model="data.form.goods_subname" show-word-limit maxlength="120" /></el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品编号'" prop="goods_no"><el-input v-model="data.form.goods_no" placeholder="A123456789" /></el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品品牌'" prop="brand_id">
                            <q-input :params="{value:'brand_id',type:'select',labelName:'name',valueName:'id'}" v-model:formData="data.form.brand_id" :dictData="{brand_id:data.goodsBrands}" />
                        </el-form-item>
                    </el-col>
                    
                    <el-col :span="24">
                        <el-form-item :label="'商品图片'" prop="goods_images">
                            <div class="goods_image">
                                <div class="item" v-if="data.form.goods_images">
                                    <div class="item_img" v-for="(v,k) in data.form.goods_images" :key="k"  @click="setMaster(k)">
                                        <div class="item_bg"><el-icon @click="deleteImg(k)" ><Delete /></el-icon></div>
                                        <div class="item_master" v-if="data.form.goods_master_image==v"><el-icon><CircleCheck /></el-icon>&nbsp;主图展示</div>
                                        <img :src="v" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="item noimg" v-else><el-icon ><CameraFilled /></el-icon></div>
                            </div>
                            <div class="goods_upload_btn">
                                <el-upload
                                    class="goods_upload_btns"
                                    :action="'/api'+uploadPath+'uploads'"
                                    :headers="{Authorization:Token}"
                                    :data="data.uploadOptions"
                                    :multiple="true"
                                    :on-success="handleSuccess"
                                >
                                    <el-button :icon="Upload" type="primary">上传</el-button>
                                </el-upload>
                                <el-button :icon="Picture" @click="openSpace">空间</el-button>
                            </div>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item :label="'平台价格'" prop="goods_price">
                            <el-input type="number" v-model="data.form.goods_price" >
                                <template #suffix>{{$t('btn.money')}}</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'市场价格'" prop="goods_market_price">
                            <el-input type="number" v-model="data.form.goods_market_price" >
                                <template #suffix>{{$t('btn.money')}}</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品重量'" prop="goods_weight">
                            <el-input type="number" v-model="data.form.goods_weight" >
                                <template #suffix>Kg</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品库存'" prop="goods_stock">
                            <el-input type="number" v-model="data.form.goods_stock" :suffix-icon="PieChart" />
                        </el-form-item>
                    </el-col>
                    <!-- <el-form-item :label="'规格属性(SKU)'" prop="name"><el-input v-model="data.form.name" /></el-form-item> -->
                    <el-col :span="12">
                        <el-form-item :label="'运费模版'" prop="freight_id">
                            <!-- ,addSelect:{name:proxy.$t('btn.default'),id:0} -->
                            <q-input :params="{value:'freight_id',type:'select',labelName:'name',valueName:'id'}" v-model:formData="data.form.freight_id" :dictData="{freight_id:data.freight}" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'商品详情'" prop="goods_content">
                            <q-input ref="goods_editor" :params="{value:'goods_content',type:'editor'}" v-model:formData="data.form.goods_content" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'是否上架'" prop="goods_status">
                            <q-input :params="{value:'goods_status',type:'radio'}" v-model:formData="data.form.goods_status" :dictData="{goods_status:[{label:$t('btn.yes'),value:1},{label:$t('btn.no'),value:0}]}" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item >
                            <el-button type="primary" @click="nextStep(3)">{{$t('btn.attrNext')}}</el-button>
                            <el-button :icon="CircleCheck" type="success" :loading="loading" @click="nextStep(2)">{{$t('btn.release')}}</el-button>
                            <el-button @click="goodsBack">{{$t('btn.back')}}</el-button>
                        </el-form-item>
                    </el-col>
                    
                    
                    
                </el-row>
            </el-form>
        </div>

        <div class="goods_form_item" v-if="data.step == 2">
            <el-result icon="success" :title="$t('msg.success')" :sub-title="$t('msg.waitPageJump')" />
        </div>

        <div class="goods_form_attr" v-if="data.step == 3">
            <el-form ref="addForm" label-position="right" :model="data.form" :rules="data.rules" label-width="80px">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item :label="'规格(SKU)'" >
                            <el-button type="primary" @click="openAttrWin">选择属性</el-button>
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item >
                            <div class="attr_item" v-for="(v,k) in data.goodsAttr" :key="k">
                                <span style="margin-right:10px;">{{v.name}}：</span>
                                <el-checkbox v-for="(vo,key) in v.specs" :key="key" @change="specChange(v,vo)" :value="vo.check||false" :checked="vo.check||false">{{vo.name}}</el-checkbox>
                            </div>
                            <!-- 规格SKU start -->
                            <div class="goods_specs" v-if="data.form.skuList && data.form.skuList.length>0">
                                <div class="row_th">
                                    <el-row :gutter="16">
                                        <el-col class="col_th thc" :span="4">SKU</el-col>
                                        <el-col class="col_th thc" :span="4">市场价</el-col>
                                        <el-col class="col_th thc" :span="4">平台价</el-col>
                                        <el-col class="col_th thc" :span="4">重量</el-col>
                                        <el-col class="col_th thc" :span="4">库存</el-col>
                                        <el-col class="col_th thc" :span="4">图片</el-col>
                                    </el-row>
                                </div>
                                <div class="row_td">
                                    <el-row :gutter="16" v-for="(v,k) in data.form.skuList" :key="k">
                                        <el-col class="col_th" :span="4">{{v.sku_name.join(' ')}}</el-col>
                                        <el-col class="col_th" :span="4">
                                            <el-input v-model="v.goods_market_price" type="number" >
                                                <template #suffix>{{$t('btn.money')}}</template>
                                            </el-input>
                                        </el-col>
                                        <el-col class="col_th" :span="4">
                                            <el-input v-model="v.goods_price" type="number" >
                                                <template #suffix>{{$t('btn.money')}}</template>
                                            </el-input>
                                        </el-col>
                                        <el-col class="col_th" :span="4">
                                            <el-input v-model="v.goods_weight" type="number" >
                                                <template #suffix>Kg</template>
                                            </el-input>
                                        </el-col>
                                        <el-col class="col_th" :span="4">
                                            <el-input type="number" v-model="v.goods_stock" :suffix-icon="PieChart" />
                                        </el-col>
                                        <el-col class="col_th" :span="4">-</el-col>
                                    </el-row>
                                </div>
                            </div>
                                <!-- 规格sku end -->
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item >
                            <el-button :icon="CircleCheck" type="success" :loading="loading" @click="nextStep(2)">{{$t('btn.release')}}</el-button>
                            <el-button @click="data.step=1">{{$t('btn.back')}}</el-button>
                        </el-form-item>
                    </el-col>
                    
                </el-row>
            </el-form>
        </div>

        <!-- 选择属性 -->
        <el-dialog v-model="data.centerDialogVisible" title="选择属性" width="40%" center>
            <el-checkbox-group v-model="data.attrListCheck">
                <el-row :gutter="10">
                    <el-col  v-for="(v,k) in data.attrList" :key="k" :span="6" style="margin-bottom:10px;"><el-checkbox :label="v.id" border >{{v.name}}</el-checkbox></el-col>
                </el-row>
            </el-checkbox-group>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="data.centerDialogVisible = false">{{$t('btn.cancel')}}</el-button>
                    <el-button type="primary" @click="attrChose">{{$t('btn.determine')}}</el-button>
                </span>
            </template>
        </el-dialog>

        <!-- 图片空间 -->
        <el-dialog v-model="data.fileSpaceVis" title="选择图片" width="60%" center>
            <div class="file_dirs" v-if="data.isFileSpaceDir">
                <div class="items">
                    <div class="dft_icon"><i class="fa fa-folder-open" /></div>
                    <div class="btmtit" @click="toPic({id:0,name:'临时目录'})">临时目录</div>
                </div>
                <div class="items" v-for="(v,k) in data.dir" :key="k">
                    <div class="dft_icon"><i class="fa fa-folder-open" /></div>
                    <div class="btmtit" @click="toPic(v)">{{v.name}}</div>
                </div>
            </div>
            <div class="file_dirs" v-if="!data.isFileSpaceDir">
                <template  v-if="data.fileSpaces.length > 0">
                    <div class="items" v-for="(v,k) in data.fileSpaces" :key="k" >
                        <div class="handle_icon">
                            <i class="fa fa-check-square-o" v-show="v.checked" />
                        </div>
                        <el-image style="width:100%;height:100%" :src="v.url" fit="contain" hide-on-click-modal :preview-src-list="[v.url]"></el-image>
                        <div class="btmtit" :title="v.name" @click="checkFileSpace(k)" >{{v.name}}</div>
                    </div>
                </template>
                <el-empty style="text-align:center;width: 100%;" v-else />
            </div>
            <div class="tabel_pagination" v-if="!data.isFileSpaceDir">
                <el-pagination background 
                layout="total, sizes, prev, pager, next, jumper" 
                :page-size="data.fileSpaceParams.per_page" 
                :page-sizes="[ 30, 100, 200, 300, 400 ]" 
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :page-count="data.fileSpaceParams.last_page"
                :current-page="data.fileSpaceParams.page"
                :total="data.fileSpaceParams.total">
                </el-pagination>
            </div>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="backFileSpaceDir" v-if="!data.isFileSpaceDir">返回目录</el-button>
                    <el-button @click="data.fileSpaceVis = false">{{$t('btn.cancel')}}</el-button>
                    <el-button type="primary" @click="insertFile(0)">插入主图</el-button>
                    <el-button type="primary" @click="insertFile(1)">插入详情</el-button>
                </span>
            </template>
        </el-dialog>

    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance,nextTick} from "vue"
import {getToken,getUploadPath} from '@/plugins/config'
import {ArrowRight,Delete,Upload,CameraFilled,PieChart,Picture,CircleCheck, Reading,CircleCheckFilled,List,SetUp } from '@element-plus/icons'
export default {
    components: {ArrowRight,Upload,CameraFilled,PieChart,Picture,Delete,CircleCheck, Reading,CircleCheckFilled,List,SetUp},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)
        const data = reactive({
            centerDialogVisible:false,
            fileSpaceVis:false,
            isFileSpaceDir:true,
            fileSpaceDirId:0,
            isEdit:false,
            classList:[],
            attrList:[],
            attrListCheck:[],
            goodsAttr:[],
            goodsBrands:[],
            choseId:[0,0,0],
            choseItem:[{},{},{}],
            index:[0,0,0],
            step:0,
            form:{
                freight_id:-1
            },
            freight:[],
            rules:{
                goods_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_images:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_price:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_market_price:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_weight:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            uploadOptions:{option:JSON.stringify({width:800,height:800,thumb:[[400,400],[300,300],[150,150]]}),name:'goods'},
            fileSpaceDir:[],
            fileSpaces:[],
            fileSpaceParams:{
                per_page:20,// 每页大小
                total:0,
                last_page:1,
                page:1
            }
        })
        const nextStep = (e)=>{
            let oldStep = data.step
            data.step = e

            // 直接发布商品
            if(e == 2){
                data.step = oldStep
                proxy.$refs.addForm.validate((valid)=>{
                    // 验证失败直接断点
                    if (!valid) return false
                    loading.value = true
                    try {
                        // 插入栏目Id
                        data.form.class_id = data.choseId[2]
                        let url = '/Seller/goods'
                        let method = 'post'

                        if(data.isEdit){
                            url += '/'+data.form.id
                            method = 'put'
                        }
                        proxy.R[method](url,data.form).then(res=>{
                            if(!res.code){
                                proxy.$message.success(proxy.$t('msg.success'))
                            }
                        }).catch((err)=>{
                            console.log(err)
                        }).finally(()=>{
                            data.step = e
                            loading.value = false
                            goodsBack();
                        })
                    } catch (error) {
                        loading.value = false
                    }
                })
                return
            }
            // 选择到商品菜单跳转
            if(data.step == 1){
                
            }

            // 下一步选择规格属性
            if(e == 3){
                data.step = oldStep
                nextTick(()=>{
                    return proxy.$refs.addForm.validate((valid)=>{
                        if (!valid) return false
                        return data.step = e
                    })
                })
            }
        }

        // 获取店铺分类
        const storeClass = async ()=>{
            data.classList = await proxy.R.get('/Seller/store_classes')
        }
        // 获取品牌信息
        const goodsBrand = async ()=>{
            data.goodsBrands = await proxy.R.get('/Seller/goods_brands?isAll=true')
        }

        // 获取运费配置
        const loadFreght = ()=>{
            proxy.R.get('/Seller/freights',{isAll:true}).then(res=>{
                if(!res.code) data.freight = res
                data.freight.unshift({name:proxy.$t('freight.free'),id:-1})
            })
        }

        const chose = (v,deep,index)=>{
            data.choseId[deep] = v.id
            data.choseItem[deep] = v
            data.index[deep] = index
        }

        // 图片处理
        const setMaster = (e)=>{
            data.form.goods_master_image = data.form.goods_images[e]
        }
        const deleteImg = (e)=>{
            let imgUrl = data.form.goods_images[e]
            data.form.goods_images.splice(e,1)
            if(data.form.goods_images &&
                data.form.goods_master_image == imgUrl &&
                data.form.goods_images.length>0
            ){
                data.form.goods_master_image = data.form.goods_images[0]
            }
            
        }
        // 上传图片
        const handleSuccess = (e)=>{
            if(e.code != 200) return proxy.$message.error(e.msg)
            if(!data.form.goods_master_image) data.form.goods_master_image = e.data
            if(!data.form.goods_images) data.form.goods_images = []
            data.form.goods_images.push(e.data) 
        }

        const editGoods = (e)=>{
            data.step = 1
            data.isEdit = true
            data.choseItem = e.classList
            data.form = e
            data.goodsAttr = e.attrList
            data.attrListCheck = e.attrList?.map(item=>item.id)
        }

        // 公共返回列表页面
        const goodsBack = ()=>{
            location.reload();
        }

        // 打开属性选择
        const openAttrWin = async ()=>{
            data.attrList = await proxy.R.get('/Seller/goods_attrs?isAll=true&isWith=specs')
            data.centerDialogVisible = true
        }

        // 确定属性选择
        const attrChose = ()=>{
            // data.goodsAttr = []
            data.centerDialogVisible = false
            if(data.attrListCheck.length<=0) return
            if(!data.goodsAttr) data.goodsAttr = []
            data.attrListCheck.map(items=>{
                let attrId = items
                let status = false
                data.goodsAttr.map((attrItems)=>{
                    // console.log(attrItems.id,items.id)
                    if(attrItems.id == items){
                        status = true
                        attrId = items.id
                    }
                })
                if(!status){
                    data.attrList.map((item,index)=>{
                        if(attrId == item.id) data.goodsAttr.push(data.attrList[index])
                    })
                    
                }
     
            })
        }

        const specChange = (attrs,specs)=>{
            let index = -1;
            data.goodsAttr.map((items,key)=>{
                if(items.id == attrs.id){
                    index = key;
                }
            })
            data.goodsAttr[index].specs.map((items,itemsKey)=>{
                if(items.id == specs.id){
                    // console.log(items.check)
                    if(items.check == undefined ){
                        data.goodsAttr[index].specs[itemsKey].check = true
                    }else{
                        data.goodsAttr[index].specs[itemsKey].check = !data.goodsAttr[index].specs[itemsKey].check
                    }
                }
            })
            structureSku();
        }

        const structureSku = ()=>{
            let skuList = [];
            let attrList = [];
            let attrListName = [];
            let i=0;
            data.goodsAttr.map((items,key)=>{
                let canPlus = false;
                items.specs.map(specItem=>{
                    if(specItem.check){
                        if(proxy.R.isEmpty(attrList[i])){
                            attrList[i] = [];
                            attrListName[i] = [];
                        }
                        attrList[i].push(specItem.id);
                        attrListName[i].push(specItem.name);
                        canPlus = true;
                    }
                })
                if(canPlus){
                    i++;
                }
            })
            // console.log(skuList,attrList,attrListName)
            if(attrList.length<=0){
                return data.form.skuList = [];
            }
            
            // 判断是否单选一个属性
            let attrName = []
            let attrId = [];
            if(attrList.length!=1){
                attrName = cartesianProduct(attrListName);
                attrId = cartesianProduct(attrList);
                attrId.map((items,key)=>{
                    skuList.push({spec_id:items,sku_name:attrName[key],goods_market_price:0,goods_price:0,goods_stock:0,goods_weight:0});
                })
            }else{
                attrName = attrListName[0];
                attrId = attrList[0];
                attrId.map((items,key)=>{
                    skuList.push({spec_id:[items],sku_name:[attrName[key]],goods_market_price:0,goods_price:0,goods_stock:0,goods_weight:0});
                })
            }
            console.log(skuList)
          
            // 判断是否有已经设置过金额的则不改变内容
            // console.log(skuList.length,data.form.skuList.length)
            if(data.form.skuList && !proxy.R.isEmpty(data.form.skuList[0]) && skuList[0].spec_id.length==data.form.skuList[0].spec_id.length){ // 如果规格数量不一致了则不变了直接替换
                // 判断是否是规格减少了
                if(skuList.length<data.form.skuList.length){
                    
                    let skuListLength = data.form.skuList.length;
                    let strList = [];
                    for(let i=0;i<skuListLength;i++){
                        let ngt = false
                        skuList.map(skuItem=>{
                            if(skuItem.spec_id.sort().toString() == data.form.skuList[i].spec_id.sort().toString()){
                                ngt = true
                            }
                        })
                        if(!ngt){
                            strList.push(data.form.skuList[i].spec_id.sort().toString());
                        }
                    }
                    // console.log(strList,data.form.skuList)
                    for(let i=0;i<strList.length;i++){
                        let ngt = false;
                        let index = -1
                        data.form.skuList.map((skuItem,key)=>{
                            console.log(strList[i],skuItem.spec_id.sort().toString(),strList[i] == skuItem.spec_id.sort().toString())
                            if(strList[i] == skuItem.spec_id.sort().toString()){
                                ngt = true
                                index = key
                            }
                            
                        })
                        if(ngt) data.form.skuList.splice(index,1)
                    }
                }else{
                    skuList.forEach(item=>{
                        let gt = false;
                        data.form.skuList.map(skuItem=>{
                            if(skuItem.spec_id.sort().toString() == item.spec_id.sort().toString()){
                                gt = true;
                            }
                        })
                        if(!gt){
                            data.form.skuList.push(item)
                        }
                    })
                }
                
            }else{
                data.form.skuList = skuList
            }
        }

        const cartesianProduct = (array)=>{
            if(array.length==1){
                return array
            }
            return array.reduce(function(a,b){
                return a.map(function(x){
                    return b.map(function(y){
                        return x.concat(y)
                    })
                }).reduce(function(a,b){ return a.concat(b) },[])
            }, [[]])
        }
        

        // 打开图片空间
        const loadDir = ()=>{
            proxy.R.get('/Seller/file_space_dirs',{isAll:true}).then(res=>{
                data.fileSpaces = []
                if(!res.code) data.fileSpaceDir = res
            })
        }
        const loadDirPic = ()=>{
            data.fileSpaceParams.dir_id = data.fileSpaceDirId
            proxy.R.get('/Seller/file_spaces',data.params).then(res=>{
                if(!res.code){
                    data.fileSpaces = res.data
                    data.fileSpaceParams.per_page = res.per_page
                    data.fileSpaceParams.total = res.total
                    data.fileSpaceParams.last_page = res.last_page
                    data.fileSpaceParams.page = res.page
                }
            })
        }
        const openSpace = ()=>{
            data.fileSpaceVis = true
            data.isFileSpaceDir  = true
            loadDir()
        }
        const toPic = (v)=>{
            data.fileSpaceDirId  = v.id
            data.fileSpaces = []
            loadDirPic()
            data.isFileSpaceDir  = false
        }
        const backFileSpaceDir = ()=>{
            data.isFileSpaceDir = true
            data.fileSpaceDirId = 0
        }
        const insertFile = (e)=>{
            // 插入主图
            if(e == 0){
                data.fileSpaces.map(item=>{
                    if(item.checked && item.checked === true){
                        if(!data.form.goods_master_image) data.form.goods_master_image = item.url
                        if(!data.form.goods_images) data.form.goods_images = []
                        data.form.goods_images.push(item.url)
                    }
                })
                data.fileSpaceVis = false
            }
            if(e == 1){
                let E = proxy.$refs['goods_editor'].getEditObjec()
                E.editorObj.focus()
                setTimeout(()=>{
                    data.fileSpaces.map(item=>{
                        if(item.checked && item.checked === true){
                            E.editorObj.insertNode({type: 'image',src:item.url,children: [{ text: '' }]})
                        }
                    })
                    data.fileSpaceVis = false
                },200)
            }
        }
        const checkFileSpace = (k)=>{
            if(data.fileSpaces[k].checked){
                data.fileSpaces[k].checked = !data.fileSpaces[k].checked
            }else{
                data.fileSpaces[k].checked = true
            }
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

        storeClass()
        goodsBrand()
        loadFreght()

        const Token = getToken()
        const uploadPath = getUploadPath()

        return {
            nextStep,chose,handleSuccess,setMaster,deleteImg,specChange,
            openSpace,toPic,backFileSpaceDir,insertFile,checkFileSpace,handleSizeChange,handleCurrentChange,
            attrChose,openAttrWin,goodsBack,editGoods,data,
            Picture,PieChart,Upload,CircleCheck,Token,uploadPath,loading
        }
    }
}
</script>

<style lang="scss" scoped>
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
.goods_chose{
    .chose_class_btn{
        margin:40px 0;
        display: block;
        text-align: center;
    }
    .chose_class_bg_item{
        width: 30%;
        background: #fff;
        box-sizing: border-box;
        padding:10px;
        border: 1px solid #efefef;
        border-radius: 4px;
        float: left;
        margin-right: 5%;
        height: 398px;
        &:last-child{
            margin-right: 0;
        }
        &.disabled{
            background: #fafafa;
        }
        ul li{
            cursor: pointer;
            padding:4px 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #fff;
            i{
                float:right;
                line-height: 24px;
                margin-top: 3px;
            }
            &.checked{
                border: 1px solid #d9ecff;
                background: #ecf5ff;
                color: #409eff;
                border-radius: 3px;
            }
            &:hover{
                border: 1px solid #d9ecff;
                background: #ecf5ff;
                color: #409eff;
                border-radius: 3px;
            }
        }
    }

    .goods_add_chose_class_bg{
        box-sizing: border-box;
        background: #fafafa;
        padding: 40px;
        width: 100%;
        margin: 0 auto;
        border: 1px solid #eee;
        border-radius: 5px;
        &:after{
            content:'';
            clear:both;
            display: block;
        }
    }
}
.goods_image{
    width: 100%;
    .item{
        &.noimg{
            width:160px;
            height: 160px;
            background: #efefef;
            text-align: center;
            border-radius: 4px;
            i{
                font-size: 40px;
                line-height: 160px;
                margin-top: 60px;
                color:#999;
            }
        }
        .item_img{
            width: 160px;
            height: 160px;
            display: block;
            float: left;
            box-sizing: border-box;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            border:1px solid #efefef;
            .item_bg{
                border-radius: 4px;
                text-align: center;
                line-height: 160px;
                display: none;
                width: 100%;
                height: 100%;
                position: absolute;
                color:#fff;
                top:0;
                left:0;
                background: rgba(0,0,0,0.5);/* IE9、标准浏览器、IE6和部分IE7内核的浏览器(如QQ浏览器)会读懂 */
                i{
                    padding: 0 14px;
                    font-size: 16px;
                    cursor: pointer;
                }
            }
            @media \0screen\,screen\9 {/* 只支持IE6、7、8 */
                .item_bg{
                    background-color:#000;
                    filter:Alpha(opacity=50);
                    position:static; /* IE6、7、8只能设置position:static(默认属性) ，否则会导致子元素继承Alpha值 */
                    *zoom:1; /* 激活IE6、7的haslayout属性，让它读懂Alpha */
                }
                .item_bg span{
                    position: relative;/* 设置子元素为相对定位，可让子元素不继承Alpha值 */
                }  
            }
            img{
                width: 100%;
                height: 100%;
                border-radius: 4px;
            }
            .item_master{
                position: absolute;
                left:0;
                bottom: 0;
                border-radius: 0 0 4px 4px;
                background: #000;
                line-height: 26px;
                height: 26px;
                width: 100%;
                z-index: 3;
                background: rgba(0,0,0,0.5);
                color:#fff;
                text-align: center;
                font-size: 12px;
            }
            &:hover .item_bg{
                display: block;
            }
        }
    }
    
    
}
.goods_image_class{
}
.goods_upload_btn{
    padding-top: 15px;
    display: flex;
}
.goods_upload_btns{
    margin-right: 10px;
}
.goods_specs{
    border:1px solid #efefef;
    margin-top: 10px;
    padding-bottom: 10px;
}
.row_th{
    background: #efefef;
}
.col_th{
    text-align: center;
    padding-top: 10px;
    &.thc{
        padding-top: 0;
    }
}
.goods_form_attr{
    .attr_item{
        width: 100%;
    }
}
.file_dirs{
    display: flex;
    width: 100%;
    margin-bottom: 20px;
    flex-flow:row wrap;
    .items{
        flex: 0 0 23%;
        border: 1px solid #efefef;
        height: 200px;
        margin-right: 2%;
        margin-bottom: 20px;
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
            right: 10px;
            top: 10px;
            font-size: 20px;
            z-index: 800;
            color: #67C23A;
            i{
                margin-left:10px ;
                cursor: pointer;
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

</style>
<style lang="scss">
.goods_form .goods_form_item .el-upload-list{
    display: none;
}
</style>