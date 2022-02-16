<template>
    <div class="goods_form">
        <div class="goods_form_item">
            <el-form ref="addForm" label-position="right" :model="data.form" :rules="data.rules" label-width="80px">
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item :label="'商品分类'" prop="cid"><q-input :params="{value:'cid',type:'select',labelName:'name',valueName:'id'}" v-model:formData="data.form.cid" :dictData="{cid:data.classList}" /></el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'商品标题'" prop="goods_name"><el-input v-model="data.form.goods_name" /></el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'副标题'" prop="goods_subname"><el-input type="textarea" v-model="data.form.goods_subname" show-word-limit maxlength="120" /></el-form-item>
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
                                <el-button :icon="Picture" @click="$message.info('暂未开发')">空间</el-button>
                            </div>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item :label="'平台积分'" prop="goods_price">
                            <el-input type="number" v-model="data.form.goods_price" >
                                <template #append>{{$t('btn.money')}}</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'市场价格'" prop="goods_market_price">
                            <el-input type="number" v-model="data.form.goods_market_price" >
                                <template #append>{{$t('btn.money')}}</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品库存'" prop="goods_stock">
                            <el-input type="number" v-model="data.form.goods_stock" >
                                <template #append> <el-icon><PieChart /></el-icon> </template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="'商品详情'" prop="goods_content">
                            <q-input :params="{value:'goods_content',type:'editor'}" v-model:formData="data.form.goods_content" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'商品上架'" prop="goods_status">
                            <q-input :params="{value:'goods_status',type:'switch'}" v-model:formData="data.form.goods_status" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="'推荐位'" prop="is_recommend">
                            <q-input :params="{value:'is_recommend',type:'switch'}" v-model:formData="data.form.is_recommend" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item >
                            
                            <el-button :icon="CircleCheck" type="success" :loading="loading" @click="nextStep">{{$t('btn.release')}}</el-button>
                            <el-button @click="goodsBack">{{$t('btn.back')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>

    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import {getToken,getUploadPath} from '@/plugins/config'
import {Delete,Upload,CameraFilled,PieChart,Picture,CircleCheck, Reading,CircleCheckFilled,List,SetUp } from '@element-plus/icons'
export default {
    components: {Upload,CameraFilled,PieChart,Picture,Delete,CircleCheck, Reading,CircleCheckFilled,List,SetUp},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const loading = ref(false)
        const data = reactive({
            centerDialogVisible:false,
            isEdit:false,
            classList:[],
            form:{},
            rules:{
                cid:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_name:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_images:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_price:[{required:true,message:proxy.$t('msg.requiredMsg')}],
                goods_market_price:[{required:true,message:proxy.$t('msg.requiredMsg')}],
            },
            uploadOptions:{option:JSON.stringify({width:800,height:800,thumb:[[400,400],[300,300],[150,150]]}),name:'goods'},
        })
        const nextStep = ()=>{
            proxy.$refs.addForm.validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    // 插入栏目Id
                    let url = '/Admin/integral_goods'
                    let method = 'post'

                    if(data.isEdit){
                        url += '/'+data.form.id
                        method = 'put'
                    }
                    proxy.R[method](url,data.form).then(res=>{
                        if(!res.code){
                            goodsBack();
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
            return
        }

        // 获取店铺分类
        const storeClass = async ()=>{
            data.classList = await proxy.R.get('/Admin/integral_goods_classes',{isAll:true})
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
            data.isEdit = true
            data.form = e
        }

        // 公共返回列表页面
        const goodsBack = ()=>{
            location.reload();
        }


        storeClass();

        const Token = getToken()
        const uploadPath = getUploadPath()

        return {
            nextStep,handleSuccess,setMaster,deleteImg,goodsBack,editGoods,data,
            Picture,Upload,CircleCheck,Token,uploadPath,loading
        }
    }
}
</script>

<style lang="scss" scoped>


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
}
</style>
<style lang="scss">
.goods_form .goods_form_item .el-upload-list{
    display: none;
}
</style>