<template>
    <div class="qingwu">
        <!-- 容器本身 -->
        <el-container>
            <!-- 左侧 -->
            <el-aside :width="leftBarWidth">
                <el-scrollbar>
                    <!-- LOGO -->
                    <div class="head_logo">
                        <span>Q</span><font v-show="isQingwu">ingwu</font>
                    </div>
                    <!-- 左侧导航 -->
                    <el-menu background-color="#20222a" text-color="#e1e1e1" active-text-color="#fff" @open="handleOpen" @close="handleClose" :collapse="isCollapse" :router="true">
                        <el-menu-item index="0" route="/Seller/index">
                            <i class="icon iconfont title_i">&#xe625;</i>
                            <span slot="title">系统首页</span>
                        </el-menu-item>
                        
                        <el-submenu :index="v.id+''" v-for="(v,k) in permisssion_menus" :key="k">
                            <template slot="title">
                                <i class="icon iconfont title_i" v-html="v.icon"></i>
                                <span slot="title">{{v.name}}</span>
                            </template>
                            <el-menu-item-group v-if="!$isEmpty(v.children) && v.children.length>0">
                                <el-menu-item :route="vo.url" v-for="(vo,key) in v.children" :key="key" :index="vo.id+''">{{vo.name}}</el-menu-item>
                            </el-menu-item-group>
                        </el-submenu>
                    </el-menu>
                </el-scrollbar>
            </el-aside>
            <el-main>
                <el-header height="102px">
                    <div class="index_top_bg">
                        <div class="index_header">
                            <i class="icon iconfont right_head_i" @click="left_bar();">&#xe62c;</i>
                            <i class="icon iconfont right_head_i" title="刷新页面" @click="$router.go(0)">&#xe638;</i>
                        </div>

                        <div class="head_user">
                            <el-dropdown  @command="handleCommand">
                                <span class="el-dropdown-link">
                                {{user_info.nickname}}<i class="el-icon-arrow-down el-icon--right"></i>
                                </span>
                                <el-dropdown-menu class="head_menu" slot="dropdown">
                                    <el-dropdown-item>暂无其他功能</el-dropdown-item>
                                    <el-dropdown-item command="logout" divided>退出</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>

                        <div class="avatar"><img :src="user_info.avatar||'https://www.layui.com/admin/pro/dist/style/res/template/portrait.png'"></div>

                        <div class="right_head_other">
                            <el-badge :value="count_msg" class="item" id="dot">
                                <i @click="openChat" class="icon iconfont right_head_i">&#xe793;</i>
                            </el-badge>
                        </div>
                    </div>
                    <breadcrumb-nav></breadcrumb-nav>
                </el-header>
                <el-main class="main_in">
                    
                    <el-scrollbar>
                        <div class="main_in2">
                            <transition name="el-fade-in-linear" mode="out-in">
                            <router-view></router-view>
                            </transition>
                        </div>
                    </el-scrollbar>
                    
                </el-main>
            </el-main>
            <el-backtop target=".main_in .el-scrollbar .el-scrollbar__wrap"></el-backtop>
        </el-container>
        <chat ref="chat" @count_msg="changeCountMsg($event)"></chat>
    </div>
</template>

<script>
import BreadcrumbNav from "@/components/admin/BreadcrumbNav.vue";
import chat from "@/components/chat/seller_chat.vue";
export default {
    components: {
        BreadcrumbNav,
        chat,
    },
    props: {},
    data() {
        return {
            isCollapse:false, // 侧边栏缩进打开
            leftBarWidth:"185px", // 左侧宽度
            isQingwu:true, // 显示Qingwu 全部
            minimize:true, // 聊天框是否显示
            count_msg:0,
            permisssion_menus:[],
            user_info:{},
        };
    },
    watch: {},
    computed: {},
    methods: {
        handleOpen:function(){},
        handleClose:function(){},
        handleCommand:function(e){
            // 点击退出
            if(e == 'logout'){
                this.logout();
            }
        },
        left_bar:function(){
            this.isCollapse = !this.isCollapse;
            if(this.isCollapse){
                this.leftBarWidth = "65px";
                this.isQingwu = false;
            }else{
                this.leftBarWidth = "185px";
                this.isQingwu = true;
            }
        },
        get_user_info:function(){
            let _this = this;
            this.$get(this.$api.getUserInfo).then(function(res){
                _this.user_info = res.data;
            });
        },
        get_permission_menus:function(){
            let _this = this;
            this.$post(this.$api.getPermissionMenus,{is_type:1}).then(function(res){
                _this.permisssion_menus = res.data;
            });
        },
        logout:function(){
            let _this = this;
            this.$get(this.$api.logout).then(function(){
                localStorage.removeItem('token');
                _this.$router.push('/Seller/login');
            });
        },
        openChat:function(){
            this.$refs.chat.chatShow();
        },
        changeCountMsg:function(e){
            this.count_msg = e;
        }
    },
    created() {
        this.get_permission_menus();
        this.get_user_info();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.el-main {
    padding: 0;
}
.main_in{
    height: calc(100% - 101px);
    background: #f3f3f4;
    width: 100%;
    box-sizing: border-box;
    // padding: 25px;
    .el-scrollbar__wrap{
        overflow-x: hidden!important;
    }
    .main_in2{
        padding: 25px;
    }
}
.el-menu {
    border-right: none;
    height: 100%;
    .el-submenu__title i{
        color:#e1e1e1;
    }
    .title_i{
        color:#cfcfcf;
        padding-right: 15px;
    }
}
.el-scrollbar{
    height: 100%;
}
.el-aside {
    height: 100%;
    overflow: hidden!important;
    
}
.el-container {
    height: 100%;
}
.el-header{
    padding: 0;
}

.head_logo{
    height: 50px;
    line-height: 50px;
    text-align: center;
    font-size: 20px;
    background:#000;
    color:#fff;
    span{color:#409EFF;}
}
.index_top_bg{
    border-bottom: 1px solid #e7eaec!important;
    height: 50px;
    .index_header{
        float: left;
        .right_head_i{line-height: 50px;font-size: 18px;color:#333;margin-left: 30px;}
    }
    .avatar img{width: 30px; height: 30px;border-radius: 50%;float: right;margin-top: 10px;}
    .right_head_other{
        float: right;margin-right: 40px;position: relative;
        .item{margin-top: 17px;}
        i{font-size: 20px}
    }
    .head_user{line-height: 50px;float: right;position: relative;margin-left: 10px;margin-right: 30px;}
}

</style>