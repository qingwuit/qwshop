<template>
    <div class="chat">
        <!-- 缩小化 -->
        <div class="small_chat" @click="websocketInit();" v-show="minimizes && miniItems">
            <i class="font iconfont chat_handle">&#xebaf;</i>
        </div>

        <!-- 好友界面 -->
        <div class="chat_friend_interface chat_shodow" v-show="!minimizes">

            <!-- 好友界面头部 -->
            <div class="chat_friend_interface_head">
                <div class="head_top_info">
                    <span>{{user_info.nickname||'加载中...'}}</span>
                    <font :class="isOnline?'green':'red'"></font>
                    <i class="font iconfont chat_handle" @click="minimizes = true">&#xeb9b;</i>
                </div>
                <div class="chat_friend_interface_head_desc">
                    该用户很懒，没留下任何描述~
                </div>
            </div>

            <!-- 分组好友和临时聊天列表Tab切换 -->
            <div class="chat_friend_interface_tab">
                <ul>
                    <li class="border_b_show">消息<div class="border_b"></div></li>
                    <li @click="$message.error('暂无此功能')">联系人<div class="border_b"></div></li>
                </ul>
            </div>

            <!-- 好友的内容块 -->
            <div class="chat_friend_interface_content">
                <dl class="chat_handle" v-for="(v,k) in chat_friend.data" :key="k" @click="onChatHandle(v.friend_info)">
                    <dt><img :src="v.friend_info.avatar||'/pc/default_avatar.png'" alt=""></dt>
                    <dd>{{v.friend_info.nickname||'加载中...'}}<span v-show="v.not_read_count>0"></span></dd>
                    <dd class="friend_new">聊天内容：{{v.chat_msg==null?'-':(v.chat_msg.type=='text'?v.chat_msg.content:(v.chat_msg.type=='image'?'[图片]':'-'))}}</dd>
                </dl>
                <!-- <dl class="chat_handle">
                    <dt><img src="/pc/default_avatar.png" alt=""></dt>
                    <dd>青梧商城<span></span></dd>
                    <dd class="friend_new">青梧商城：[图片][图片][图片][图片][图片]</dd>
                </dl>
                <dl class="chat_handle">
                    <dt><img src="/pc/default_avatar.png" alt=""></dt>
                    <dd>青梧商城</dd>
                    <dd class="friend_new">青梧商城：[图片][图片][图片][图片][图片]</dd>
                </dl> -->
            </div>

            <!-- 聊天界面 -->
            <div class="chat_interface" v-if="onChat.length>0">
                <!-- 左侧显示聊天好友 -->
                <div class="chat_interface_left">
                    <dl :class="k==onChatIndex?'active':'active2'" v-for="(v,k) in onChat" :key="k">
                        <dt><img :src="v.avatar||'/pc/default_avatar.png'" alt=""></dt>
                        <dd>{{v.nickname||'-'}}</dd>
                        <dd class="close" @click="closeWin(onChat[onChatIndex])"><i class="icon iconfont">&#xebb2;</i></dd>
                    </dl>
                </div>
                <div class="chat_interface_right">
                    <div class="chat_interface_right_top">{{onChat[onChatIndex].nickname||'加载中...'}}<i class="icon iconfont chat_handle" @click="onChat = [];onChatIndex = 0;">&#xeb9b;</i></div>

                    <!-- 聊天内容 -->
                    <div class="chat_interface_right_content">
                        <div v-if="onChat[onChatIndex].chat_msg != undefined">
                            <el-scrollbar ref="myScrollbar">
                                <div :class="user_info.id==v.user_id?'chat_interface_msg_item me':'chat_interface_msg_item'" v-for="(v,k) in onChat[onChatIndex].chat_msg.data" :key="k">
                                    <div class="chat_interface_msg_avatar">
                                        <img :src="v.user_info.avatar||'/pc/default_avatar.png'" alt="">
                                    </div>
                                    <div class="chat_interface_msg_time"><span>{{v.user_info.nickname||'-'}}</span> {{v.add_time|formatDateAuto('yyyy-MM-dd hh:mm:ss')}}</div>
                                    <div class="chat_interface_msg">
                                        <div class="chat_image" v-if="v.type=='image'" ><el-image :key="v.content" :src="v.content" ></el-image></div>
                                        <div v-else-if="v.type=='text'">{{v.content}}</div>
                                        <div v-else>无法识别！</div>
                                    </div>
                                </div>
                            </el-scrollbar>
                        </div>
                    </div>

                    <!-- 聊天输入框 -->
                    <div class="chat_interface_right_send">
                        <div class="chat_interface_right_send_tool">
                            <div class="tool_item chat_handle">
                                <emoji-picker @emoji="insert">
                                    <div slot="emoji-invoker" slot-scope="{ events: { click: clickEvent } }" @click.stop="clickEvent">
                                        <i class="icon iconfont">&#xeb96;</i>
                                    </div>
                                    <div slot="emoji-picker" slot-scope="{ emojis, insert }">
                                    <div>
                                    <div>
                                        <!-- <div v-for="(emojiGroup, category) in emojis" :key="category"> -->
                                        <!-- <h5>{{ category }}</h5> -->
                                        <div class="emoji_block">
                                            <el-scrollbar>
                                            <span class="emoji_class"
                                            v-for="(emoji, emojiName) in emojis['People']"
                                            :key="emojiName"
                                            @click="insert(emoji)"
                                            :title="emojiName"
                                            >{{ emoji }}</span>
                                            </el-scrollbar>
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                    </div>
                                </div>
                                </emoji-picker>
                            </div>
                            <!-- <div class="tool_item chat_handle"><i class="icon iconfont">&#xeb96;</i></div> -->
                            <div class="tool_item chat_handle">
                                <el-upload :action="$api.chatUpload" :headers="upload_headers" :show-file-list="false" :on-success="chatUpload" >
                                    <i class="icon iconfont">&#xebac;</i>
                                </el-upload>
                                <!-- <i class="icon iconfont">&#xebac;</i> -->
                            </div>
                            <div class="tool_item other_tool_item chat_handle" @click="$message.error('暂时未开放')"><i class="icon iconfont">&#xeb9d;</i>聊天记录</div>
                        </div>
                        <div class="chat_interface_right_send_text">
                            <textarea v-model="sendMsg['content']['content']"></textarea>
                        </div>
                        <div class="chat_interface_right_send_text_btn">
                            <button @click="send('text')">发送消息(E)</button>
                            <button @click="closeWin(onChat[onChatIndex])">关闭窗口(C)</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import EmojiPicker from 'vue-emoji-picker'
export default {
    components: {
        EmojiPicker,
    },
    props: {
        minimize:{ // 最小化
            type:Boolean,
            default:true,
        },
        miniItem:{
            type:Boolean,
            default:false,
        },
        sellerId:{
            type:Number,
            default:0,
        }
        
    },
    data() {
      return {
          minimizes:this.minimize,
          miniItems:this.miniItem,
          webSocketLink:"ws://127.0.0.1:2346",
          music:"/music/chat.wav", // 播放音频地址
          upload_headers:{},
          page:1, // 聊天页码
          socketObj:null,
          isOnline:false, // 是否链接上socket
          isBind:false, // 是否已经绑定
          connect_info:{ // 用户信息，包括聊天好友信息
              user_id:0,
              to_user_id:0,
          }, 
          onChat:[],
          onChatIndex:0,
          sendMsg:{
              type:'text', // 类型
              content:{
                  content:'',// 聊天内容
              }, // 内容
          },
          user_info:{}, // 链接成功后获取到的用户信息
          chat_friend:{}, // 好友列表

      };
    },
    watch: {},
    computed: {},
    methods: {
        websocketInit:function(){
            if(typeof(WebSocket) === "undefined"){
                alert("您的浏览器不支持socket")
            }else{
                // 实例化socket
                this.socketObj = new WebSocket(this.webSocketLink)
                // 监听socket连接
                this.socketObj.onopen = this.open;
                // 监听socket错误信息
                this.socketObj.onerror = this.error;
                // 监听socket消息
                this.socketObj.onmessage = this.getMessage;
            }
        },
        open: function () {
            // 如果已经链接则不再重复链接
            if(this.socketObj.readyState == 1 && this.isBind ){
                this.minimizes = false;
                return true;
            }

            // eslint-disable-next-line no-console
            console.log("socket连接成功")

            // 如果登录了，且用户ID存在
            this.sendMsg.type = 'bind';
            this.sendMsg.content = this.connect_info;
            let infoStr = JSON.stringify(this.sendMsg);
            if(this.user_info.id>0){
                this.socketObj.send(infoStr); // 发送用户ID到服务器
                this.isBind = true; // 发送完了绑定信息
                this.add_friend(); // 添加商家为好友
                
            }
        },
        error: function () {
            // eslint-disable-next-line no-console
            console.log("连接错误")
        },
        getMessage: function (msg) {

            let obj = JSON.parse(msg.data);

            if(obj == undefined){
                return;
            }
            // eslint-disable-next-line no-console
            console.log(obj)

            switch(obj.type){
                case 'bind': // 绑定用户成功
                    this.isOnline = true;
                    this.minimizes = false;
                    break;
                case 'error':
                    this.$message.error('服务端消息接收失败!');
                    break;
                case 'text': // 文字接收
                    break;
                case 'image': // 图片接收
                    break;
            }

            // 只要不是心跳检测，就查询一次好友列表
            if(obj.type != '@heart@'){
                this.get_chat_friend();
            }

            // 只要不是就处理到指定位置
            if(obj.type != '@heart@' && obj.type != 'bind' && obj.type != 'error' && obj.type !='other'){

                // 判断如果发送人不是自己则提示音
                if(obj.user_id != this.user_info.id){
                    let audio = new Audio(this.music);
                    audio.play();
                }   
                
                // 如果没有正在聊天的窗口 直接返回不管
                if(this.onChat.length<=0){
                    return false;
                }

                this.onChat.forEach((item,key)=>{

                    // 正在聊天则将聊天记录放入到onChat内
                    if(item.id == obj.user_id || item.id == obj.to_user_id){
                        this.onChat[key].chat_msg.data.push(obj) // 放入

                        // 如果消息存在onChat 则要清空未读记录
                        if(obj.user_id != this.user_info.id){
                            this.clearNoRead(obj.content.user_id,obj.content.to_user_id);
                        }
                    }
                });
            }
            
        },
        send: function (type) {
            this.sendMsg.type = type;
            this.sendMsg.content.to_user_id = this.onChat[this.onChatIndex].id;
            this.sendMsg.content.user_id = this.user_info.id;
            // 发送信息
            this.$post(this.$api.chatChatEvent,{data:this.sendMsg}).then(res=>{
                if(res.code == 200){
                    this.sendMsg.content.content = '';
                    this.scrollDown();
                }else{
                    this.$message.error(res.msg);
                }
            });
            
        },
        close: function () {
            // eslint-disable-next-line no-console
            console.log("socket已经关闭")
        },
        // 获取用户链接信息，如果未登陆则跳转登录
        get_connect_info:function(){ 

        },
        insert(emoji) {
            if(this.$isEmpty(this.sendMsg.content.content)){
                this.sendMsg.content.content = '';
            }
            this.sendMsg.content.content += emoji;
            this.$forceUpdate();
        },
        // 滚动条到底部
        scrollDown() {
            this.$nextTick(() => {
                this.$refs['myScrollbar'].wrap.scrollTop = this.$refs['myScrollbar'].wrap.scrollHeight;
            })
            
        },
        // 清空未读信息
        clearNoRead:function(user_id,to_user_id){
            this.$post(this.$api.chatReadMsg,{user_id:user_id,to_user_id:to_user_id}).then(()=>{});
        },
        // 图片上传成功
        chatUpload:function(res){
            let info = {
                type:'image',
                content:{
                    to_user_id:0,
                    user_id:0,
                    content:'',
                }
            };
            info.content.to_user_id = this.onChat[this.onChatIndex].id;
            info.content.user_id = this.user_info.id;
            info.content.content = res.data;
            // 发送信息
            this.$post(this.$api.chatChatEvent,{data:info}).then(res=>{
                if(res.code == 200){
                    this.scrollDown();
                }else{
                    this.$message.error(res.msg);
                }
            });
            this.$forceUpdate();
        },
        
        // 获取用户信息
        get_user_info:function(){
            let user_info = localStorage.getItem('user_info');
            if(this.$isEmpty(user_info)){
                this.$message.error('请先登陆');
                this.$router.push('/user/login');
            }
            this.user_info = JSON.parse(user_info);
            this.connect_info.user_id = this.user_info.id;
            // this.connect_info.to_user_id = 
        },
        // 把商家添加成好友
        add_friend:function(){
            this.$post(this.$api.chatAddFriend,{firend_id:this.sellerId}).then(res=>{
                if(res.code==200){
                    // eslint-disable-next-line no-console
                    console.log(res);
                }else{
                    return this.$message.error(res.msg);
                }
            })
        },
        // 获取聊天好友列表
        get_chat_friend:function(){
            this.$get(this.$api.chatGetChatFriend).then(res=>{
                if(res.code == 200){
                    this.user_info = res.data.user_info;
                    this.chat_friend = res.data.chat_friend;
                    this.$forceUpdate();
                }else{
                    this.$message.error(res.msg);
                }
            });
        },
        // 打开聊天框
        onChatHandle:function(info){
            // 是否正在聊天
            let isOnChat = false;
            this.onChat.forEach(item => {
                if(item.id == info.id){
                    isOnChat = true;
                }
            });

            if(!isOnChat){
                this.onChat.push(info);
            }

            // 获取聊天信息列表
            this.get_chat_msg(info);
            
        },
        // 关闭窗口
        closeWin:function(info){
            this.onChatIndex = 0;
            if(this.onChat.length<=1){
                return this.onChat = [];
            }
            this.onChat.forEach((item,key)=>{
                if(item.id == info.id){
                    this.onChat.splice(key,1);
                }
            });
        },
        // 获取聊天信息列表
        get_chat_msg:function(info){
            this.$post(this.$api.chatGetChatMsg,{to_user_id:info.id,page:this.page}).then(res=>{
                if(res.code == 200){
                    this.onChat.forEach((item,key) => {
                        if(item.id == info.id){
                            res.data.data = res.data.data.reverse();
                            this.onChat[key].chat_msg = res.data;
                            this.clearNoRead(this.user_info.id,item.id); // 将该正在聊天未读标记已读
                            this.$forceUpdate();
                        }
                    });
                    this.scrollDown();
                }else{
                    this.$message.error('聊天信息加载失败');
                }
            });
        }

    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        this.get_user_info();
        // this.websocketInit();
    },
    mounted() {},
    destroyed () {
        // 销毁监听
        // this.socket.onclose = this.close
    }
};
</script>
<style lang="scss" scoped>
.chat_image{
    max-width: 200px;
    max-height: 200px;
}
.emoji_block{
    height: 170px;
    position: absolute;
    background: #fff;
    top: 150px;
    .el-scrollbar{
        height: 170px;
    }
}
.emoji_class{
    margin-right: 10px;
    padding-top: 10px;
    float: left;
}
.chat_interface{
    border-radius: 3px;
    .chat_interface_left{
        float: left;
        width: 200px;
        background: #333;
        height: 100%;
        border-radius: 3px 0 0 3px;
        dl{
            padding: 10px 0;
            clear: both;
            dt{
                width: 40px;
                height: 40px;
                img{
                    width: 100%;
                    height: 100%;
                }
                float: left;
                margin-right: 15px;
                margin-left: 15px;
            }
            dd{
                float: left;
                overflow: hidden;
                color:#999;
                line-height: 40px;
                height: 30px;
                width: 100px;
            }
            dd.close{
                float: right;
                width: 20px;
                height: 30px;
                margin-right: 5px;
                cursor:pointer;
                display: none;
            }
        }
        dl.active{
            background: #222;
            dd{
                color:#fff;
            }
        }
        dl.active2{
            background: #333;
            dd{
                color:#fff;
            }
        }
        dl:nth-child(1).active{
            border-radius: 3px 0 0 0;
        }
        dl:hover dd.close{
            display: block;
            color:#fff;
        }
        dl:after{
            content:'';
            clear:both;
            display: block;
        }
    }
    .chat_interface_right{
        float: right;
        width: 600px;;
        .chat_interface_right_top{
            background: #efefef;
            line-height: 40px;
            text-align: center;
            font-size: 14px;
            border-radius: 0 3px 0 0;
            i{
                float:right;
                margin-right: 15px;
            }
        }
        .chat_interface_right_content{
            height: 280px;
            padding-bottom: 15px;
            box-sizing: border-box;
            .el-scrollbar{
                height: 265px;
            }
            .chat_interface_msg_item{
                padding: 15px 0 0 15px;
                .chat_interface_msg_avatar{
                    background-color: #efefef;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    float: left;
                    margin-right: 15px;
                    img{
                        border-radius: 50%;
                        width: 100%;
                        height: 100%;
                    }
                }
                .chat_interface_msg_time{
                    font-size: 12px;
                    margin-top: 10px;
                    color:#666;
                    line-height: 20px;
                    span{
                        float: left;
                        font-size: 14px;
                        color:#333;
                        margin-right: 15px;
                        font-weight: bold;
                    }
                }
                .chat_interface_msg_time:after{
                    content:'';
                    display: block;
                    clear: both;
                }
                .chat_interface_msg{
                    font-size: 12px;
                    line-height: 20px;
                    padding: 5px 8px;
                    background: #efefef;
                    border-radius: 5px;
                    margin-top: 10px;
                    word-wrap:break-word;
                    // display:inline-block;
                    min-width: 140px;
                    max-width: 400px;
                    height: 100%;
                    margin-left: 52px;
                }
            }
            .me{
                .chat_interface_msg_avatar{
                    float: right;
                }
                .chat_interface_msg_time{
                    float: right;
                    span{
                        float: right;
                        margin-left: 20px;
                    }
                }
                .chat_interface_msg{
                    background: #67C23A;
                    color:#fff;
                    float: right;
                    clear:both;
                    margin-right: 65px;
                    text-align: right;
                }
            }
            .me:after{
                content:'';
                display: block;
                clear: both;
            }
        }
        .chat_interface_right_send{
            .chat_interface_right_send_tool{
                .tool_item{
                    line-height: 18px;
                    margin-left: 20px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    padding:4px 6px;
                    float: left;
                    border-radius: 3px;
                    i{
                        font-size: 18px;
                    }
                }
                .tool_item:hover{
                    background: #efefef;
                }
                .other_tool_item{
                    float:right;
                    margin-right: 15px;
                    font-size: 14px;
                    i{
                        margin-right: 10px;
                    }
                }
            }
            .chat_interface_right_send_tool:after{
                content:'';
                clear: both;
                display: block;
            }
            .chat_interface_right_send_text{
                height: 60px;
                textarea{
                    font-family:'微软雅黑',  Arial, sans-serif;
                    width: 100%;
                    height: 60px;
                    padding: 5px;
                    color:#666;
                    outline: none;
                    border:none;
                    box-sizing: border-box;
                }
            }
            .chat_interface_right_send_text_btn{
                button{
                    background: #fff;
                    border:1px solid #efefef;
                    border-radius: 3px;
                    float:right;
                    margin-right: 15px;
                    line-height: 25px;
                    margin-top: 5px;
                    outline: none;
                    cursor:pointer;
                }
                button:nth-child(1){
                    border: none;
                    background: #333;
                    color:#fff;
                }
                
            }
            border-top: 1px solid #efefef;
        }
    }
    width: 800px;
    height: 460px;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    z-index: 1005;
    position: fixed;
    left: 50%;
    top: 50%;
    background: #fff;
    transform: translate(-50%, -50%);    /* 50%为自身尺寸的一半 */
}
.chat_friend_interface_content{
    dl{
        padding: 15px 0 10px 0; 
        dt{
            width: 40px;
            height: 40px;
            display: blcok;
            float: left;
            margin-left: 15px;
            margin-right: 15px;
            img{
                border-radius: 50%;
                width: 100%;
                height: 100%;
            }
        }
        dd{
            float: left;
            width: 165px;
            line-height: 20px;
            height: 20px;
            font-size: 14px;
            display: block;
            position: relative;
            span{
                border-radius: 50%;
                background: #ca151e;
                color:#fff;
                width: 8px;
                height: 8px;
                position: absolute;
                right: 0px;
                z-index: 1001;
                top: -5px;
            }
        }
        dd.friend_new{
            color:#999;
            font-size: 12px;
            line-height: 16px;
            overflow: hidden;
            height: 16px;
            display: block;
        }
    }
    dl:hover{
        // background: #fcfcfc;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1)
    }
    dl:after{
        content:'';
        clear: both;
        display: block;
    }
}
.chat_friend_interface_tab{
    ul{
        border-bottom: 1px solid #efefef;
        li{
            float: left;
            width: 50%;
            text-align: center;
            line-height: 40px;
            color:#999;
            position: relative;
            .border_b{
                border-bottom: 1px solid #333;
                display: none;
                width: 80%;
                margin:0 auto;
                margin-bottom: -1px;
            }
        }
        li:hover{
            color: #333;
        }
        li.border_b_show{
            color:#333;
        }
        li.border_b_show .border_b{
            display: block;
        }
        
    }
    ul:after{
        content:'';
        clear: both;
        display: block;
    }
}
.small_chat{
    z-index: 1000;
    color:#ca151e;
    position: fixed;
    right: 40px;
    bottom: 90px;
    i{
        font-size: 50px;
    }
}
.chat_friend_interface{
    z-index: 1000;
    width: 260px;
    height: 520px;
    position: fixed;
    right: 0;
    bottom: 0;
    background: #fff;
    .chat_friend_interface_head{
        height: 80px;
        background: #333;
        color:#fff;
        .head_top_info{
            span{
                line-height: 50px;
                margin-left: 15px;
                float: left;
                height: 50;
                overflow: hidden;
                
            }
            font{
                float: left;    
                margin-top: 18px;  
                margin-left: 8px;  
            }
            i{
                float: right;
                line-height: 40px;
                margin-right: 10px;
            }
        }
        .head_top_info:after{
            content:'';
            clear: both;
            display: block;
        }
    }
    .chat_friend_interface_head_desc{
        font-size: 12px;
        color:#999;
        margin-left: 15px;
    }
}
.red{
    width: 12px;
    height: 12px;
    display: block;
    border-radius: 50%;
    background: #ca151e;
}
.green{
    width: 12px;
    height: 12px;
    display: block;
    border-radius: 50%;
    background: #67C23A;
}
.chat_shodow{
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.chat_handle{
    cursor:pointer;
}

@font-face {
  font-family: 'iconfont';  /* project id 1617825 */
  src: url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.eot');
  src: url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.eot?#iefix') format('embedded-opentype'),
  url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.woff2') format('woff2'),
  url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.woff') format('woff'),
  url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.ttf') format('truetype'),
  url('//at.alicdn.com/t/font_1617825_5wjqc8ogtbk.svg#iconfont') format('svg');
}
</style>