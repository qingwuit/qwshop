<template>
    <div :class="{chat_main:true,position_css:position}" v-show="hide">
        <!-- 缩略框 -->
        <div v-show="small" :class="{small_socket_status:socketStatus,small_main:true,shadow:true}">
            <div class="socket_status">
                <el-icon :size="24"><ChatDotSquare /></el-icon>
            </div>
        </div>

        <!-- 主要聊天框 -->
        <div v-show="!small" :class="{main_block:true}">
            <!-- 联系人列表 -->
            <div class="left_block">
                <ul>
                    <li v-for="(v,k) in data.friends" :key="k" @click="openChatItem(v.id)" :class="{ck:v.id==data.selectId}">
                        <div class="content_item" >
                            <img class="avatar" :src="(data.selfData.sid == v.sid && data.selfData.stype == v.stype) ? ((v.rinfo && v.rinfo.avatar)||require('@/assets/Home/kefu.png').default) : ((v.sinfo && v.sinfo.avatar)||require('@/assets/Home/kefu.png').default)" />
                            <div class="avatar_right">
                                <div class="nickname text_over">{{(data.selfData.sid == v.sid && data.selfData.stype == v.stype) ? ((v.rinfo && v.rinfo.nickname)||'anonymous') : ((v.sinfo && v.sinfo.nickname)||'anonymous')}}</div>
                                <div class="text text_over">{{v.lastMsg ? v.lastMsg.content_type && v.lastMsg.content_type == 'text' ? v.lastMsg.content : '[ '+ v.lastMsg.content_type +' ]' : ''}}</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- 聊天内容 -->
            <div class="right_block">
                <div class="top_title"><span v-show="closeVis" @click="closeChat"><el-icon><Close /></el-icon></span>{{loadInfoById(data.selectId)['nickname'] || 'Loading...'}}</div>
                <div class="chat_content">
                    <el-scrollbar class="scrollbar" ref="scrollbarRef" @scroll="scrollbarChange" v-if="data.selectId > 0">
                        <div class="scrollbar_inner" ref="scrollbarDivRef">
                            <div class="item" v-for="(v,k) in data.chatList" :key="k">
                                <img class="avatar" :src="loadInfoBySid(v.sid,v.stype).avatar||require('@/assets/Home/kefu.png').default" />
                                <div class="avatar_right">
                                    <div class="nickname text_over">{{loadInfoBySid(v.sid,v.stype).nickname||'anonymous'}} <span class="text_time">[ {{v.created_at}} ]</span></div>
                                    <div class="text ">
                                        <div v-if="v.content_type == 'text'">{{v.content||'-'}}</div>
                                        <div v-if="v.content_type == 'image'"><el-image :src="v.content" fit="cover" :preview-src-list="[v.content]" /></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </el-scrollbar>
                    <el-empty v-else />
                </div>
                <div class="chat_tool" v-if="data.selectId > 0" >
                    <div class="tools">
                        <el-upload 
                        style="display: inline-block;"
                        :show-file-list="false"
                        :action="'/api'+uploadPath+'uploads'"
                        :headers="{Authorization:Token}"
                        :data="data.uploadOptions"
                        :on-success="handleSuccess"
                        >
                            <el-icon :size="22" color="#666" title="Upload Image"><Picture /></el-icon>
                        </el-upload>
                        <el-icon @click="$message.info('暂未开发')" :size="22" color="#666" title="Upload File"><FolderOpened /></el-icon>
                    </div>
                    <div class="send_textarea">
                        <textarea v-model="data.params.content" :placeholder="'Please enter information'"  />
                    </div>
                    <div class="send_btn">
                        <el-button type="success" :loading="loading" :icon="Promotion" @click="send('text')">{{$t('btn.sendMsg')}}</el-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref,reactive,watch,onMounted,onBeforeUnmount,nextTick,getCurrentInstance} from "vue"
import { ChatDotSquare,Picture,FolderOpened,Promotion,Close } from '@element-plus/icons'
import {getToken,getUploadPath} from '@/plugins/config'
import qFont from './font'
export default {
    components:{ChatDotSquare,Picture,Close,FolderOpened},
    props:{
        // 缩略
        small:{
            type:Boolean,
            default:false
        },
        // 是否漂浮
        position:{
            type:Boolean,
            default:false,
        },
        // 关闭按钮显示
        closeVis:{
            type:Boolean,
            default:true,
        },
        // 发送人用户的信息 和 当前人类型
        params:{
            type:Object,
            default:()=>{
                return {}
            }
        }
    },
    setup(props) {
        const {proxy} = getCurrentInstance()
        const socketStatus = ref(false) // socket 连接状态
        const hide = ref(false) // 显示聊天
        const loading = ref(false)
        const data = reactive({
            socket:null,
            timer:null,
            friends:[],
            chatList:[],
            pageParams:{
                per_page:50,
                page:1,
                total:0,
                last_page:1
            },
            selfData:{},
            selectId:0,
            params:{
                content:'',
                type:'text',
                file:'',
            },
            uploadOptions:{
                name:'chat',
            },
        })
        
        const wsInit = async ()=>{
            let ws = (document.location.protocol == 'http:' ? 'ws:' : 'wss:') + '//' + window.location.host + '/ws'
            if(typeof(WebSocket) === "undefined"){
                return console.error("您的浏览器不支持socket")
            }else{
                // 实例化socket
                if(data.socket != null) return
                data.socket = new WebSocket(ws)
                // 监听socket连接
                data.socket.onopen = open
                // 监听socket错误信息
                data.socket.onerror = error
                // 监听socket消息
                data.socket.onmessage = getMessage
            }
        }
        

        // 关闭socket
        onBeforeUnmount(()=>{
            closeChat()
        })

        const open = ()=>{
            if(data.socket.readyState == 1 ) return socketStatus.value = true
        }
        const error = ()=>{
            console.error("socket连接错误")
        }

        // 获取socket信息 post发送
        const getMessage = (msg)=>{
            try {
                let obj = JSON.parse(msg.data)
                if(obj.type == 'heart') console.log(obj)
                loadFriends()
                data.pageParams.page = 1
                data.chatList = []
                loadChatList(data.pageParams.page>1?false:true)
            } catch (error) {
                return console.error(error)
            }
        }

        // 发送心跳
        const setTime = async ()=>{
            if(data.socket == null) return
            data.socket.send(JSON.stringify({
                type:'heart',
                data:{
                    sid:data.selfData.sid
                }
            }))
        }
        const sendHeart = async () =>{
            if(data.socket == null) return
            if(data.timer != null) clearInterval(data.timer)
            await setTime();
            data.timer = setInterval(async ()=>{
                await setTime()
            },40000)
            
        }

        // 发送socket信息
        const send = (sendType)=>{
            sendType = sendType || 'text'
            let rData = loadAllById(data.selectId)
            let params = {
                provider:props.params.provider,
                rid:rData.rid,
                rtype:rData.rtype,
                content_type:sendType,
                content:sendType == 'text' ? data.params.content : data.params.file,
                token:props.params.token||'',
            }
            loading.value = true
            proxy.R.post('/Chat/send',params).then(res=>{
                if(!res.code){
                    if(sendType == 'text'){
                        data.params.content = ''
                    }else{
                        data.params.file = ''
                    }
                }
            }).finally(()=>{
                loading.value = false
                loadChatList(true)
            })
        }

        // 打开聊天框
        const openChat = async ()=>{
            await wsInit()
            proxy.R.post('/Chat/add',props.params).then(res=>{
                if(!res.code){
                    hide.value = true
                    data.selfData = res // 得到自己的信息
                    loadFriends()
                    sendHeart()
                }
            })
        }

        // 获取好友列表
        const loadFriends = async ()=>{
            data.friends = await proxy.R.post('/Chat/friends',props.params)
        }

        // 关闭聊天框
        const closeChat = ()=>{
            hide.value = false
            if(data.socket != null) data.socket.close()
            if(data.timer != null) clearInterval(data.timer)
        }

        // 根据Id 获取firends 接受者用户信息
        // data.selectIndex > -1 ? ( (data.selfData.sid == data.friends[data.selectIndex].sid && data.selfData.stype == data.friends[data.selectIndex].stype) ? (data.friends[data.selectIndex].rinfo.nickname||'anonymous') : (data.friends[data.selectIndex].sinfo.nickname||'anonymous') ) : 'Loading...'
        const loadInfoById = (id)=>{
            if(data.selectId == 0) return {}
            let selectData = null
            data.friends.map(item=>{
                if(item.id == id) selectData = item
            })
            if(data.selfData.sid == selectData.sid && data.selfData.stype == selectData.stype){
                return selectData.rinfo;
            }
            return selectData.sinfo || {};
        }

        // 根据Id 获取firends 所有内容
        const loadAllById = (id)=>{
            if(data.selectId == 0) return {}
            let selectData = null
            data.friends.map(item=>{
                if(item.id == id) selectData = item
            })
            if(data.selfData.sid == selectData.sid && data.selfData.stype == selectData.stype){
                return {rid:selectData.rid,rtype:selectData.rtype}
            }
            return {rid:selectData.sid,rtype:selectData.stype}
        }

        // 根据Sid Rid 比较获取用户信息内容
        const loadInfoBySid = (id,stype)=>{
            if(id == 0) return {}
            let items = {}
            data.friends.map(item=>{
                if(item.sid == id && item.stype == stype){
                    items = item.sinfo
                }
                if(item.rid == id && item.rtype == stype){
                    items = item.rinfo
                }
            })
            return items || {}
        }

        // 点击好友item
        const openChatItem = (id) =>{
            data.selectId = id
            data.pageParams.page = 1
            data.chatList = []
            // 获取聊天信息
            loadChatList(true)
            
        }

        // 获取聊天信息
        const loadChatList = (isOpen=false)=>{
            let rData = loadAllById(data.selectId)
            let params = {
                provider:props.params.provider,
                rid:rData.rid,
                rtype:rData.rtype,
                token:props.params.token||'',
            }
            params = Object.assign(data.pageParams,params)
            proxy.R.post('/Chat/friend_content',params).then(res=>{
                if(!res.code){
                    data.pageParams.page = res.current_page??1
                    data.pageParams.last_page = res.last_page??1
                    data.pageParams.per_page = res.page??50
                    data.pageParams.total = res.total??1
                    _.reverse(res.data).map(item=>{
                        data.chatList.push(item)
                    })
                    // data.chatList = data.chatList.unshift(_.reverse(res.data))
                    if(isOpen) getScrollbarHeight() // 拉到最底部
            
                }
            })
            
        }

        // 获取聊天信息列表的高度
        const getScrollbarHeight = ()=>{
            nextTick(()=>{
                setTimeout(()=>{
                    if(proxy.$refs.scrollbarRef) proxy.$refs.scrollbarRef.setScrollTop(parseFloat(proxy.$refs.scrollbarDivRef.clientHeight))
                },300)
            })
        }

        // 滚动条到顶部
        const scrollbarChange = ({ scrollLeft, scrollTop })=>{
            if(scrollTop == 0){
                if(data.pageParams.last_page <= data.pageParams.page) return 
                data.pageParams.page += 1
                loadChatList()
            }
        }

        // 上传图片
        const handleSuccess = (e)=>{
            if(e.code != 200) return proxy.$message.error(e.msg)
            data.params.file = e.data
            send('image')
        }

        const Token = getToken()
        const uploadPath = getUploadPath()


        return {
            Promotion,
            socketStatus,
            data,hide,loading,Token,uploadPath,
            loadInfoById,loadInfoBySid,scrollbarChange,openChatItem,send,handleSuccess,
            openChat,closeChat,
        }
    }
}
</script>

<style lang="scss" scope>
.shadow{
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.text_over{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.chat_main{
    height: 100%;
    &.position_css{
        position: fixed;
        z-index: 901;
        width: 50%;
        margin:0 auto;
        height: 50%;
        left:25%;
        top:20%;
        border-radius: 4px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .left_block{
            border-radius: 4px 0 0 4px;
        }
        .right_block{
            border-radius: 0 4px 4px 0;
        }
        .top_title{
            border-radius: 0 4px 0 0;
        }

    }
}
.small_main{
    cursor: pointer;
    background: #333;
    color:#fff;
    display: flex;
    height: 40px;
    width: 50px;
    position: absolute;
    bottom: 0;
    right: 0;
    align-items: center;
    justify-content: center;
    border-radius: 6px 0 0 0;
    padding-top: 5px;
    &.small_socket_status{
        background: var(--el-color-primary);
    }
}
.main_block{
    height: 100%;
    display: flex;
    &.position_css{
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        position: absolute;
    }
    .left_block{
        flex: 0 0 200px;
        box-sizing: border-box;
        background: #333;
        ul{
            height: 100%;
            display: flex;
            flex-flow: column;
            li{
                padding:20px 20px;
                display: flex;
                color:#fff;
                height: 40px;
                cursor: pointer;
                &:hover{background: #666;}
                &.ck{
                    background: #666;
                }
                .content_item{
                    display: flex;
                }
                .avatar{border-radius: 6px;background: #ccc;width: 40px;height: 40px;flex: 0 0 40px;margin-right: 10px;}
                .avatar_right{
                    width: 110px;
                    .nickname{
                        padding-top: 2px;
                    }
                    .text{
                        color:#ccc;
                        font-size: 12px;
                    }
                }
            }
        }
    }
    .right_block{
        display: flex;
        flex-flow: column;
        flex: 1;
        background: #fff;
        .top_title{line-height: 40px;background: #efefef;text-align: center;}
        .top_title span{
            float: right;
            margin-right: 15px;
            cursor: pointer;
            &:hover{
                color:var(--el-color-primary);
            }
        }
        .scrollbar_inner{
            display: flex;
            flex: 1;
            flex-direction: column;
            // width: calc(100% - 40px);
        }
        .chat_content{
            flex: 1;
            height: calc(100% - 180px);
            .item{
                margin:15px;
                display: flex;
                .avatar{border-radius: 6px;background: #ccc;width: 40px;height: 40px;flex: 0 0 40px;margin-right: 10px;}
                .avatar_right{
                    .nickname{
                        padding-top: 2px;
                        .text_time{
                            font-size: 12px;
                            color:#999;
                            margin-left: 10px;
                        }
                    }
                    .text{
                        color:#666;
                        font-size: 12px;
                        margin-top: 5px;
                        padding: 5px 6px;
                        border:1px solid #efefef;
                        width: 100%;
                        border-radius: 6px;
                        word-break:break-all;
                        box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.1);
                        box-sizing: border-box;
                        // max-width: 300px;
                    }
                }
            }
        }
        .chat_tool{
            flex: 0 0 140px;
            box-sizing: border-box;
            border-top:1px solid #efefef;
            .tools{
                padding:10px;
                i{cursor: pointer;margin-right: 10px;}
                i:hover{
                    color:var(--el-color-primary);
                }
            }
            .send_textarea{
                textarea{
                    width: 100%;
                    padding:0 15px;
                    border:none;
                    outline: none;
                    box-sizing: border-box;
                }
                textarea::-webkit-input-placeholder{
                    color:#ccc;
                }
            }
            .send_btn{
                text-align: right;
                margin-top: 10px;
                margin-right: 15px;
                
            }
        }
    }
}
</style>