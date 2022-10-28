<template>
    <div class="qwbase">
        <el-container>
            <!-- 左菜单 -->
            <el-aside class="aside_class" :width="collapse?'63px':'200px'" >
                
                <div class="sys_logo">
                    <span v-if="collapse"><img :src="require('@/assets/Admin/system_logo.png').default" :alt="$t('config.systemName')"></span>
                    <span v-if="!collapse"><div class="sys_span"><img :src="require('@/assets/Admin/system_logo.png').default" :alt="$t('config.systemName')"><em>{{$t('config.systemName')}}</em></div></span>
                </div>
                <el-menu :class="{sub_menu:!collapse,sub_menu2:collapse}" :collapse="collapse" :default-active="nowRoutePointIds[0] || 0"  v-if="menuData[routeUriIndex] && menuData[routeUriIndex][moduleIndex] && menuData[routeUriIndex][moduleIndex].children">
                    <el-scrollbar>
                    <div  v-for="(v) in (menuData[routeUriIndex] && menuData[routeUriIndex][moduleIndex] && menuData[routeUriIndex][moduleIndex].children) || []" :key="v.id">
                        <el-sub-menu :index="v.id+''" v-if="v.children" >
                            <template #title>
                                <el-icon><i v-if="v.icon != ''" :class="'fa '+v.icon"></i></el-icon>
                                <span class="menu_i" v-if="!collapse">{{v.name}}</span>
                            </template>
                            <el-menu-item @click="openWin(item)" :index="item.id+''" v-for="(item) in v.children || []" :key="item.id">
                                <el-icon v-if="item.icon != ''"><i :class="'fa '+item.icon"></i></el-icon>
                                <template #title>
                                    <span class="menu_i" >{{item.name}}</span>
                                </template>
                            </el-menu-item>
                        </el-sub-menu>
                        <el-menu-item  @click="openWin(v)" v-else :index="v.id+''">
                            <el-icon v-if="v.icon != ''" ><i :class="'fa '+v.icon"></i></el-icon>
                            <template #title>
                                <span class="menu_i">{{v.name}}</span>
                            </template>
                        </el-menu-item>
                    </div>
                    </el-scrollbar>
                </el-menu>
                <div class="sub_menu empty_menu" v-else>
                    <div style="background:#222;">{{$t('menu.emptyMenus')}}</div>
                </div>
                
            </el-aside>
            <!-- 右内容 -->
            <el-container>
                <!-- 顶部模块 -->
                <el-header class="modlue_header">
                    <div class="fold" @click="collapseChange"><el-icon :size="20"><fold /></el-icon></div>
                    <div class="module_content">
                        <el-scrollbar>
                        <ul class="module_ul">
                            <li v-for="(v,k) in (menuData[routeUriIndex]||[])" :key="k" @click="openWin(v);selectModuleHandle(k);" :class="v.checked||(nowRoutePoint.length>0 && nowRoutePoint[nowRoutePoint.length-1].key==k)?'ck':''">
                                <el-icon v-if="v.icon != ''"><i :class="'fa '+v.icon"></i></el-icon>
                                <span>{{v.name}}</span>
                            </li>
                        </ul>
                        </el-scrollbar>
                    </div>
                    <div class="user_right">
                        <div class="avatar"><el-avatar :size="30" :src="'circleUrl'"><img :src="users.avatar||''" ></el-avatar></div>
                        <div class="welcome_lang">{{$t('user.hello')}}</div>
                        <div class="nickname">
                            <el-dropdown popper-class="custom_dropdown">
                                <span class="el-dropdown-link">
                                    <span>{{users.nickname||'-'}}</span>
                                    <el-icon class="icon_class" :size="12"><arrow-down /></el-icon>
                                </span>
                                <template #dropdown>
                                    <el-dropdown-menu class="dropdown-header">
                                        <el-dropdown-item @click="openUserForm"><i class="fa fa-user" />{{$t('user.userInfo')}}</el-dropdown-item>
                                        <el-dropdown-item @click="$store.commit('login/logout')"><i class="fa fa-sign-out" />{{$t('user.logout')}}</el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </div>
                </el-header>
                <!-- 主内容 -->
                <el-main class="el_main_css">
                    <el-scrollbar>
                    <div v-if="!hideMian" class="sys_main_content shadow">
                        <!-- 具体内容 -->
                        <div v-if="!hideTitle" class="mian_title"><slot name="main_title">{{routeMenuName||'Menu Name'}}</slot></div>
                        <slot v-if="!hideTitle" name="main_line"><div class="main_line"></div></slot>
                        
                        <transition name="fade" mode="out-in">
                            <slot name="main_view">
                                <router-view></router-view>
                            </slot>
                        </transition>
                    </div>
                    <div v-else>
                        <slot name="main_view_sub" >
                            <router-view></router-view>
                        </slot>
                    </div>
                    </el-scrollbar>
                </el-main>
            </el-container>
        </el-container>

        <!-- 用户编辑 -->
        <el-dialog destroy-on-close custom-class="base_dialog_class" v-model="editUserVis" :title="$t('user.userInfo')" width="30%">
            <el-form ref="userEdit" label-position="right" :rules="userRules" :model="editUserForm" :label-width="80">
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item :label="$t('user.nickname')" prop="nickname">
                            <el-input v-model="editUserForm.nickname" style="width:85%" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="$t('user.password')" prop="password">
                            <el-input v-model="editUserForm.password" type="password" style="width:85%" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-form-item :label="$t('user.avatar')" prop="avatar">
                            <el-upload
                                class="avatar-uploader"
                                :action="'/api'+uploadPath+'uploads'"
                                :show-file-list="false"
                                :headers="{Authorization:Token}"
                                :data="{option:{width:150,height:150},name:'avatar'}"
                                :on-success="handleAvatarSuccess"
                            >
                                <img v-if="editUserForm.avatar" style="width:100%;height:100%" :src="editUserForm.avatar" class="avatar" />
                                <el-icon v-else class="avatar-uploader-icon"><plus /></el-icon>
                            </el-upload>
                        </el-form-item>
                    </el-col>


                    <!-- <el-col :span="12"><div class="table-form-content"></div></el-col> -->
                </el-row>

                <!-- 按钮处理 -->
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item>
                            <el-button :loading="loading" type="primary" @click="updateUser">{{$t('btn.determine')}}</el-button>
                            <el-button @click="editUserVis = false">{{$t('btn.cancel')}}</el-button>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
        </el-dialog>
    </div>
</template>
<script setup>
import { ArrowDown,Fold,Plus } from '@element-plus/icons'
import {_open,getToken,getUploadPath} from '@/plugins/config'
import {ref,reactive,computed,onMounted,getCurrentInstance} from "vue"
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

const {proxy} = getCurrentInstance()
const props = defineProps({
    hideTitle:{
        type:Boolean,
        default:false
    }, // 隐藏标题栏
    hideMian:{
        type:Boolean,
        default:false
    }, // 隐藏主体框
})

const router = useRouter()
const route = useRoute()
const store = useStore()
const collapse = ref(false)
const loading = ref(false)
const editUserVis = ref(false)
const editUserForm = reactive({})
const userRules = reactive({
    nickname:[{required:true,message:proxy.$t('msg.requiredMsg')}],
})
const collapseChange = ()=>{
    collapse.value = !collapse.value
}

// 跳转和打开新页面
const openWin = (row)=>{
    if(row.apis != ''){
        // 看是否是跳转外链的存在
        if(row.apis.indexOf('http://') != -1 || row.apis.indexOf('https://') != -1){
            _open(row.apis,true)
        }else{
            // 判断你是否存在路由 和 相关页面组件是否配置 && router.hasRoute(row.apis)
            if(row.view != '' && router.hasRoute(row.apis)){
                router.push(row.apis)
            }else{
                // 判断是否又子栏目
                if(!row.children || (row.children && row.children.length === 0) ){
                    console.error('view:'+row.view,'router has '+router.hasRoute(row.apis),'children has '+(row.children && row.children.length)||'empty') // 异常信息提醒
                }
            }
        }
    }else{
        // 如果存在子栏目
        const findRoute = (row)=>{
            if(row.children){
                let hasApisIndex = -1
                row.children.map((item,key)=>{
                    if(item.apis != '' && hasApisIndex == -1){
                        hasApisIndex = key
                    }
                })

                if(hasApisIndex != -1) return row.children[hasApisIndex]
                let findFirstRoute = null
                row.children.map(item=>{
                    if(findFirstRoute == null){
                        const findRouteObj = findRoute(item)
                        if(findRouteObj)  findFirstRoute = findRouteObj
                    }
                    
                })
                if(findFirstRoute == null) return false
                return findFirstRoute
            }
            return false
        }
        const routeObj =  findRoute(row)
        if(!routeObj) return console.error('No Route')
        return openWin(routeObj)
    }
}

// 模型选中状态处理
const selectModule = (menuDataTmp,indexs=0)=>{
    menuDataTmp.map((item,key)=>{
        menuDataTmp[key].checked = false
        if(indexs == key) menuDataTmp[indexs].checked = true
    })
    return menuDataTmp
}

const selectModuleHandle = async (indexs=0)=>{
    let menuData = await store.dispatch('load/getMenus',-1)
    const menuDataTmp = selectModule(menuData,indexs)
    await store.commit('load/setMenus',menuDataTmp,-1)
    await store.commit('load/setRoutePointIndex', indexs)
    // moduleIndex.value = indexs
    // ctx.$forceUpdate()
}

const users = reactive({})
onMounted( async ()=>{
    let user = await store.dispatch('load/getUser')
    Object.assign(users,user)
})


// 用户相关
const openUserForm = async ()=>{
    editUserVis.value = true
    const servUser = await store.dispatch('login/getUserSer')
    Object.assign(editUserForm,servUser)
    Object.assign(users,editUserForm)
}
const updateUser = async ()=>{
    proxy.$refs.userEdit.validate( async (valid)=>{
        if (!valid) return false
        loading.value = true
        let userEditForm = {
            nickname:editUserForm.nickname,
            password:editUserForm.password||'',
        }
        if(editUserForm.avatar) userEditForm.avatar = editUserForm.avatar
        try{
            const servUser = await store.dispatch('login/editUserSer',userEditForm)
            if(!servUser.code){
                let servUser2 = await store.dispatch('login/getUserSer')
                Object.assign(users,servUser2)
                Object.assign(editUserForm,servUser2)
                ElementPlus.ElMessage.success(proxy.$t('msg.success'))
            }else{
                ElementPlus.ElMessage.error(servUser.msg)
            }
            editUserVis.value = false
            loading.value = false
        }catch(e){
            console.log(e)
            editUserVis.value = false
            loading.value = false
        }
    })
    
    
}

// 头像上传
const handleAvatarSuccess = (e)=>{
    if(e.code != 200) return ElementPlus.ElMessage.error(e.msg)
    editUserForm.avatar = e.data
}

const Token = getToken()
const uploadPath = getUploadPath()

const menuData = computed(()=>store.state.load.menuData)
const routeUriIndex = computed(()=>store.state.load.routeUriIndex)
const nowRoutePoint = computed(()=>store.state.load.nowRoutePoint)
const nowRoutePointIds = computed(()=>store.state.load.nowRoutePointIds)
const routeMenuName = computed(()=>store.state.load.routeMenuName)
const moduleIndex = computed(()=>store.state.load.moduleIndex)

</script>
<style lang="scss" scoped>
.qwbase{
    height: 100%;
    background: #f4f4f4;
}
.modlue_header{
    line-height: 60px;
    background: #fff;
    display: flex;
    box-shadow: 0 2px 12px 0 rgba(170, 170, 170, 0.1);
    .fold{
        flex: 0 1 50px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    .module_content{
        flex: 1;
    }
    .user_right{
        flex: 0 0 20%;
        display: flex;
        justify-content: right;
        min-width: 160px;
        .avatar{
            flex: 1;
            text-align: right;
            justify-items: center;
            margin-right: 15px;
            padding-top: 10px;
        }
        .nickname{
            padding-top: 23px;
        }
    }
    .module_ul{
        display: flex;
        margin-top: 15px;
        li{
            flex: 0 0 auto;
            line-height: 30px;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding:0 20px;
            transition-duration:0.3s;
            i{
                margin-right: 5px;
                // padding-right: 10px;
            }
            .el-icon{
                width: 26px;
                height: 26px;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                box-sizing: border-box;
                padding-left: 4px;
            }
            &:hover{
                color:#409EFF;
                .el-icon{
                    background: #409eff;
                    border-radius: 50%;
                    color: #fff;
                }
            }
            &.ck{
                color:#409EFF;
                .el-icon{
                    background: #409eff;
                    border-radius: 50%;
                    color: #fff;
                }
            }
        }
    }
    
}

.sys_logo{
    height: 60px;
    background: #333;
    display: flex;
    align-items: center;
    justify-items: center;
    width: 100%;
    text-align: center;
    flex-direction: row;
    span{
        align-items: center;
        justify-items: center;
        // display: flex;
        text-align: center;
        width: 100%;
        img{
            // flex: 0 1 30px;
            width: 30px;
            margin-right: 8px;
        }
        .sys_span{
            align-items: center;
            justify-items: center;
            display: inline-flex;
            em{
                font-size: 16px;
                font-style:normal
            }
        }
    }
}
.el_main_css{
    margin-bottom: 0;
    padding-bottom: 0;
}
.sys_main_content{
    background: #fff;
    height: 100%;
    width: 100%;
    border-radius: 8px;
    padding:15px 20px;
    box-sizing: border-box;
    box-shadow: 0 2px 12px 0 rgba(170, 170, 170, 0.1);
}
.mian_title{
    font-weight: bold;
    font-size: 16px;
}
.main_line{
    height: 1px;
    background: #EBEEF5;
    margin:20px 0;
    width: 100%;
    

}
.el-container{
    height: 100%;
}
.icon_class{
    margin-left: 6px;
}
.aside_class{
    transition: width 0.18s;
    overflow-x: hidden;
    background: #111;
}

</style>
<style lang="scss">
$admin_subcolor:#111;
$admin_subcolorhover:#222;
$admin_subfontcolor:#fff;
$admin_subactive:#409eff;;
.qwbase .sys_logo{
    background:$admin_subcolor;
    color:$admin_subfontcolor;
}
.el-sub-menu__title:hover{
    background: #333;
}
.menu_i{
    margin-left: 8px;
}
.sub_menu{
    text-align: center;
    background: $admin_subcolor;
    height: calc(100% - 60px);
    overflow-x: hidden;
    width: 200px;
    transition: width 0.18s;
    border-right:none;
    color:#fff;
    .el-menu.el-menu--inline{
        background: #151515;
    }
    .el-sub-menu__title{
        color:$admin_subfontcolor;
        &:hover{
            background: $admin_subcolorhover;
        }
    }
    .el-menu-item.is-active{
        color:var(--el-menu-active-color);
    }
    .el-menu-item{
        color:$admin_subfontcolor;
        // background: $admin_subcolor;
        &:hover{
            background: $admin_subcolorhover;
        }
    }
    .el-sub-menu .el-icon{
        font-size: 14px;
    }
    .el-menu-item [class^=el-icon]{
        font-size: 14px;
    }
    &.empty_menu{
        line-height: 40px;
    }
}
.sub_menu2{
    text-align: center;
    background: $admin_subcolor;
    height: calc(100% - 60px);
    overflow-x: hidden;
    width: 63px;
    transition: width 0.18s;
    border-right:none;
    color:$admin_subfontcolor;
    .el-sub-menu__title{
        color:$admin_subfontcolor;
        &:hover{
            background: $admin_subcolorhover;
        }
    }
    .el-menu-item.is-active{
        color:var(--el-menu-active-color);
    }
    .el-menu-item{
        color:$admin_subfontcolor;
        // background: $admin_subcolor;
        &:hover{
            background: $admin_subcolorhover;
        }
    }
    .el-sub-menu .el-icon{
        font-size: 14px;
    }
    .el-menu-item [class^=el-icon]{
        font-size: 14px;
    }
    .el-sub-menu .el-icon{
        margin-right: 0;
    }
    .el-sub-menu .el-sub-menu__icon-arrow{
        display: none;
    }
}

.base_dialog_class{
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
    }
}


.fade-enter-active,
.fade-leave-active {
  transition: 0.5s ease;
  opacity: 1;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateX(80px);
}


</style>