<template>
    <div class="qwcfg">
        <el-tabs tab-position="left" style="height:780px" @tab-click="handleClick" v-model="tabsIndex">
            <el-tab-pane :label="$t('config.configStore.storeConfig')" name="cfgForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.cfgForm" ref="cfgForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.storeLogo')" prop="store_logo">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_logo',option:{width:242,height:74}}"
                            :on-success="(e)=>handleAvatarSuccess(e,'cfgForm','store_logo')"
                        >
                            <img v-if="form.cfgForm.store_logo" style="width:100%;height:100%" :src="form.cfgForm.store_logo" class="avatar" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storeName')" prop="store_name">
                        <el-input v-model="form.cfgForm.store_name"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storeDesc')" prop="store_description">
                        <el-input type="textarea" show-word-limit maxlength="150" v-model="form.cfgForm.store_description"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storeMobile')" prop="store_mobile">
                        <el-input v-model="form.cfgForm.store_mobile"  />
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storeService')" prop="after_sale_service">
                        <q-input :params="{value:'after_sale_service',type:'editor'}" v-model:formData="form.cfgForm.after_sale_service" />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('cfgForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['cfgForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configStore.faceConfig')" name="faceForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.faceForm" ref="faceForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.storeFace')" prop="store_face_image">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_face'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'faceForm','store_face_image')"
                        >
                            <img v-if="form.faceForm.store_face_image" style="width:100%;height:100%" :src="form.faceForm.store_face_image" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('faceForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['faceForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane :label="$t('config.configStore.mapConfig')" name="mapForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.mapForm" ref="mapForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.storeArea')" prop="area">
                        <q-input :params="{value:'area',type:'cascader_lazy',placeholder:form.mapForm.area_info,lazyUrl:'/load_areas?isLazy=true'}" :dictData="{area:[]}||[]"  v-model:formData="form.mapForm.area" />
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storePoint')" prop="latlng">
                        <!-- <el-input v-model="form.mapForm.latlng"  /> -->
                        <div id="container" style="height:300px;width:100%"></div>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.storeAddress')" prop="store_address">
                        <el-input v-model="form.mapForm.store_address"  />
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('mapForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['mapForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            <el-tab-pane :label="$t('config.configStore.pcSlideConfig')" name="pcSlideForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.pcSlideForm" ref="pcSlideForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.slide1')" prop="store_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'pcSlideForm','store_slide',0)"
                        >
                            <img v-if="form.pcSlideForm.store_slide && form.pcSlideForm.store_slide[0]" style="width:100%;height:100%" :src="form.pcSlideForm.store_slide[0]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.slide2')" prop="store_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'pcSlideForm','store_slide',1)"
                        >
                            <img v-if="form.pcSlideForm.store_slide && form.pcSlideForm.store_slide[1]" style="width:100%;height:100%" :src="form.pcSlideForm.store_slide[1]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.slide3')" prop="store_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'pcSlideForm','store_slide',2)"
                        >
                            <img v-if="form.pcSlideForm.store_slide && form.pcSlideForm.store_slide[2]" style="width:100%;height:100%" :src="form.pcSlideForm.store_slide[2]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('pcSlideForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['pcSlideForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>


            <el-tab-pane :label="$t('config.configStore.mobileSlideConfig')" name="mobileSlideForm">
                <el-form style="width:60%;margin-top:8px;" :model="form.mobileSlideForm" ref="mobileSlideForm" label-position="right" label-width="140px">
                    <el-form-item :label="$t('config.configStore.slide1')" prop="store_mobile_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_mobile_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'mobileSlideForm','store_mobile_slide',0)"
                        >
                            <img v-if="form.mobileSlideForm.store_mobile_slide && form.mobileSlideForm.store_mobile_slide[0]" style="width:100%;height:100%" :src="form.mobileSlideForm.store_mobile_slide[0]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.slide2')" prop="store_mobile_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_mobile_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'mobileSlideForm','store_mobile_slide',1)"
                        >
                            <img v-if="form.mobileSlideForm.store_mobile_slide && form.mobileSlideForm.store_mobile_slide[1]" style="width:100%;height:100%" :src="form.mobileSlideForm.store_mobile_slide[1]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item :label="$t('config.configStore.slide3')" prop="store_mobile_slide">
                        <el-upload
                            class="avatar-uploader"
                            action="/api/Seller/uploads"
                            :show-file-list="false"
                            :headers="{Authorization:Token}"
                            :data="{name:'store_mobile_slide'}"
                            :on-success="(e)=>handleAvatarSuccess(e,'mobileSlideForm','store_mobile_slide',2)"
                        >
                            <img v-if="form.mobileSlideForm.store_mobile_slide && form.mobileSlideForm.store_mobile_slide[2]" style="width:100%;height:100%" :src="form.mobileSlideForm.store_mobile_slide[2]" class="avatar slides" />
                            <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                        </el-upload>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="onSubmit('mobileSlideForm')">{{$t('btn.determine')}}</el-button>
                        <el-button @click="$refs['mobileSlideForm'].resetFields()">{{$t('btn.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import {reactive,onMounted,ref,getCurrentInstance} from "vue"
import {getToken} from '@/plugins/config'
import { Plus } from '@element-plus/icons'
import AMapLoader from 'amap-loader'
import { useStore } from 'vuex'
export default {
    components:{Plus},
    setup(props) {
        const {ctx,proxy} = getCurrentInstance()
        const store = useStore()
        const loading = ref(false)

        // 获取数据列表
        const form = reactive({
            cfgForm:{},
            faceForm:{},
            mapForm:{},
            pcSlideForm:{},
            mobileSlideForm:{},
        })
        const tabsIndex = ref('cfgForm')
        const loadConfig = async (e) => {
            form[e] = await proxy.R.get('/Seller/stores/0')
            if(e == 'mapForm') await loadAmap()
        }

        // 切换
        const handleClick = (e)=>{
            loadConfig(e.paneName)
        }

        loadConfig('cfgForm')
        
        const onSubmit = (e)=>{
            let formData = {}
            if(e == 'cfgForm'){
                formData = {
                    store_logo:form[e].store_logo,
                    store_name:form[e].store_name,
                    store_face_image:form[e].store_face_image,
                    store_description:form[e].store_description,
                    store_mobile:form[e].store_mobile,
                    after_sale_service:form[e].after_sale_service,
                }
            }
            if(e == 'faceForm'){
                formData = {
                    store_face_image:form[e].store_face_image,
                }
            }
            if(e == 'mapForm'){
                formData = {
                    area:form[e].area,
                    province_id:form[e].area[0]||0,
                    city_id:form[e].area[1]||0,
                    region_id:form[e].area[2]||0,
                    store_lat:form[e].store_lat,
                    store_lng:form[e].store_lng,
                    store_address:form[e].store_address,
                }
            }
            if(e == 'pcSlideForm'){
                formData = {
                    store_slide:form[e].store_slide.join(','),
                }
            }
            if(e == 'mobileSlideForm'){
                formData = {
                    store_mobile_slide:form[e].store_mobile_slide.join(','),
                }
            }
            proxy.$refs[e].validate((valid)=>{
                // 验证失败直接断点
                if (!valid) return false
                loading.value = true
                try {
                    proxy.R.put('/Seller/stores/0',formData).then(res=>{
                        loading.value = false
                        if(!res.code){
                            loadConfig(e)
                            proxy.$message.success(proxy.$t('msg.success'))
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
        const handleAvatarSuccess = (e,formName,columnName,index=0)=>{
            if(e.code != 200) return ElementPlus.ElMessage.error(e.msg)
            if(formName == 'pcSlideForm' || formName == 'mobileSlideForm' ){
                if(form[formName][columnName] && form[formName][columnName].length == 0){
                    form[formName][columnName] = ['','','']
                }
                return form[formName][columnName][index] = e.data
            }
            form[formName][columnName] = e.data
        }

        // 地图处理
        const loadAmap = async ()=>{
            await store.dispatch('init/loadCommon') // 加载网站数据
            let jsapi = store.state.init.common.common.amap.jsapi
            AMapLoader.load({
                "key": jsapi||'',              // 申请好的Web端开发者Key，首次调用 load 时必填
                "version": "2.0",   // 指定要加载的 JSAPI 的版本，缺省时默认为 1.4.15
                "plugins": ['AMap.ToolBar','AMap.Scale','AMap.Marker','AMap.Pixel'],           // 需要使用的的插件列表，如比例尺'AMap.Scale'等
            }).then((AMap)=>{
                const defaultCenter = [form.mapForm.store_lng||116.397428, form.mapForm.store_lat||39.90923]
                const mapIcon = 'https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png'
                const mapOffset = new AMap.Pixel(-10, -30) // 默认偏移点
                const defaultMarker = new AMap.Marker({
                    icon: mapIcon,
                    position: defaultCenter,
                    offset: mapOffset
                })
                const map = new AMap.Map('container',{
                    center: defaultCenter,//中心点坐标
                });
                const toolBar = new AMap.ToolBar()
                const scale = new AMap.Scale()

                map.add(defaultMarker) // 默认点
                map.addControl(toolBar)
                map.addControl(scale)
                map.on('click', (e)=>{
                    map.clearMap() // 清除所有点
                    form.mapForm.store_lng = e.lnglat.getLng()
                    form.mapForm.store_lat = e.lnglat.getLat()
                    const lnglat = [e.lnglat.getLng(),e.lnglat.getLat()]
                    const marker = new AMap.Marker({
                        icon: mapIcon,
                        position: lnglat,
                        offset: mapOffset
                    })
                    map.add(marker)
                });

            }).catch(e => {
                console.log(e);
            })

            
        }
        

        const Token = getToken()
        return {loading,tabsIndex,form,handleClick,handleAvatarSuccess,onSubmit,Token}
    }
}
</script>

<style lang="scss" scope>
.qwcfg{
    .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 78px;
    height: 78px;
    }
    .avatar-uploader .el-upload:hover {
    border-color: #409eff;
    }
    .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 78px;
    height: 78px;
    text-align: center;
    }
    .avatar-uploader-icon svg {
    // margin-top: 26px; /* (178px - 28px) / 2 - 1px */
    }
    .avatar {
    width: 178px;
    height: 178px;
    display: block;
    &.slides{
        width: 80%;
    }
    }
}
</style>