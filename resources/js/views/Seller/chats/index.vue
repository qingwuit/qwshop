<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            在线聊天
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <!-- 聊天界面 -->
            <div class="chat_interface" v-if="chat_friend.length>=0">
                <!-- 左侧显示聊天好友 -->
                <div class="chat_interface_left">
                    <dl :class="k==onChatIndex?'active':'active2'" v-for="(v,k) in chat_friend" :key="k" @click="onChatIndex=k;get_chat_msg({id:v.user_id})">
                        <dt><img :src="v.avatar||require('@/asset/user/user_default.png')" alt=""></dt>
                        <dd>{{v.nickname||'-'}}</dd>
                        <!-- <dd class="close" @click="closeWin(onChat[onChatIndex])"><a-icon type="close-circle" /></dd> -->
                    </dl>
                </div>
                <div class="chat_interface_right">
                    <div class="chat_interface_right_top" v-if="chat_friend[onChatIndex]">{{chat_friend[onChatIndex].nickname||'加载中...'}}</div>
                    <div class="chat_interface_right_top" v-if="!chat_friend[onChatIndex]">暂无聊天</div>

                    <!-- 聊天内容 -->
                    <div class="chat_interface_right_content"  ref="myScrollbar">
                        <div v-if="chat_friend[onChatIndex] && chat_friend[onChatIndex].chat_msg != undefined">
                            <div>
                                <div :class="v.send_type==1?'chat_interface_msg_item me':'chat_interface_msg_item'" v-for="(v,k) in chat_friend[onChatIndex].chat_msg.data" :key="k">
                                    <div class="chat_interface_msg_avatar">
                                        <img :src="v.send_type==1?storeInfo.store_logo:v.avatar||require('@/asset/user/user_default.png')" alt="">
                                    </div>
                                    <div class="chat_interface_msg_time"><span>{{v.send_type==1?storeInfo.store_name:(v.nickname||'-')}}</span> {{v.created_at}}</div>
                                    <div class="chat_interface_msg">
                                        <div class="chat_image" v-if="v.type=='image'" ><img :key="v.content" :src="v.content"  /></div>
                                        <div v-else-if="v.type=='text'">{{v.content}}</div>
                                        <div v-else>无法识别！</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 聊天输入框 -->
                    <div class="chat_interface_right_send" v-if="chat_friend[onChatIndex]">
                        <div class="chat_interface_right_send_tool">
                            <div class="tool_item chat_handle" @click="$message.error('暂时未开放')"><a-icon type="smile" /></div>
                            <div class="tool_item chat_handle">
                                <a-upload
                                    :action="$api.chatImage"
                                    :data="{token:$getSession('token_type')}"
                                    :multiple="true"
                                    :show-upload-list="false"
                                    @change="chatUpload"
                                >
                                    <a-icon type="picture" />
                                </a-upload>
                                <!-- <i class="icon iconfont">&#xebac;</i> -->
                            </div>
                            <div class="tool_item other_tool_item chat_handle" @click="$message.error('暂时未开放')"><a-icon type="bar-chart" />聊天记录</div>
                        </div>
                        <div class="chat_interface_right_send_text">
                            <textarea v-model="sendMsg.content"></textarea>
                        </div>
                        <div class="chat_interface_right_send_text_btn">
                            <button @click="send('text')">发送消息(E)</button>
                            <button @click="closeWin(chat_friend[onChatIndex])">关闭窗口(C)</button>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <a-empty v-else />
        </div>
    </div>

        

            


</template>

<script>
export default {
    components: {
    },
    
    data() {
      return {
          webSocketLink:"ws://127.0.0.1:2346",
          music:"/default.mp3", // 播放音频地址
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
          onChatIndex:-1,
          sendMsg:{
              type:'text', // 类型
              content:'', // 内容
              uid:0,
              send_type:1,
              store_id:0,
          },
          chat_friend:[], // 好友列表
          storeInfo:{},

      };
    },
    watch: {},
    computed: {},
    methods: {
        websocketInit:function(){

            // 判断是否登录
            // console.log(this.storeInfo)
            // if(!this.isLogin){
            //     this.$router.push('/user/login');
            // }

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
                return true;
            }

            // eslint-disable-next-line no-console
            console.log("socket连接成功")

            // 如果登录了，且用户ID存在
            this.sendMsg.type = 'bind';
            this.sendMsg.uid = this.storeInfo.id;
            this.sendMsg.store_id = this.storeInfo.id;
            let infoStr = JSON.stringify(this.sendMsg);
            if(this.storeInfo.id>0){
                this.socketObj.send(infoStr); // 发送用户ID到服务器
                this.isBind = true; // 发送完了绑定信息
                 this.get_chat_friend();
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
            
            let chatType = 1;
            switch(obj.type){
                case 'bind': // 绑定用户成功
                    this.isOnline = true;
                    break;
                case 'error':
                    this.$message.error('服务端消息接收失败!');
                    break;
                case 'text': // 文字接收
                    break;
                case 'image': // 图片接收
                    break;
                default :  // 都不是
                    chatType = -1;
                    break;
            }

            if(chatType == -1){
                return;
            }
            console.log(obj)
            // 只要不是心跳检测，就查询一次好友列表
            // if(obj.type != '@heart@'){
            //     this.get_chat_msg({uid:obj.user_id});
            // }

            // 只要不是就处理到指定位置
            if(obj.type != 'bind' && obj.type != 'error' && obj.type !='other'){

                // 判断如果发送人不是自己则提示音
                if(obj.send_type==0){
                    console.log(this.music)
                    let audio = new Audio(this.music);
                    audio.play();
                }   
                this.scrollDown();
                // 如果没有正在聊天的窗口 直接返回不管
                if(this.chat_friend.length<=0){
                    return false;
                }
                console.log(this.chat_friend)
                this.chat_friend.forEach((item,key)=>{
                    
                    // 正在聊天则将聊天记录放入到onChat内
                    if(item.user_id == obj.user_id && key == this.onChatIndex){
                        obj.avatar = this.chat_friend[key].avatar;
                        obj.nickname = this.chat_friend[key].nickname;
                        obj.store_logo = this.chat_friend[key].store_logo||'';
                        obj.store_name = this.chat_friend[key].store_name||'';
                        this.chat_friend[key].chat_msg.data.push(obj) // 放入
                        this.scrollDown();
                        // 如果消息存在onChat 则要清空未读记录
                        // if(obj.uid != this.user_info.id){
                        //     this.clearNoRead(obj.content.user_id,obj.content.to_user_id);
                        // }
                    }
                });
                this.$forceUpdate();
            }
            
        },
        send: function (type) {
            if(this.onChatIndex ==-1){
                return this.$message.error('请选择聊天对象');
            }
            this.sendMsg.type = type;
            this.sendMsg.uid = this.chat_friend[this.onChatIndex].user_id;
            
            // 发送信息
            this.$post(this.$api.sellerChatChatEvent,{data:this.sendMsg}).then(res=>{
                if(res.code == 200){
                    this.$set(this.sendMsg,'content','');
                    // console.log(this.onChat)
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
   
  
        // 滚动条到底部
        scrollDown() {
            this.$nextTick(() => {
                setTimeout(()=>{
                    this.$refs['myScrollbar'].scrollTop = this.$refs['myScrollbar'].scrollHeight;
                },600)
            })
            
        },
        // 清空未读信息
        clearNoRead:function(user_id,to_user_id){
            this.$post(this.$api.sellerChatReadMsg,{user_id:user_id,to_user_id:to_user_id}).then(()=>{});
        },
        // 图片上传成功
        chatUpload:function(e){
            if(e.file.status == 'done'){
                let rs = e.file.response;
                let info = {
                    type:'image',
                    uid:0,
                    send_type:1,
                    store_id:this.storeInfo.id,
                    content:'',
                
                };
                if(rs.code == 200){
                    info.uid = this.chat_friend[this.onChatIndex].user_id;
                    info.content = rs.data;
                    // 发送信息
                    this.$post(this.$api.sellerChatChatEvent,{data:info}).then(res=>{
                        if(res.code == 200){
                            this.scrollDown();
                        }else{
                            this.$message.error(res.msg);
                        }
                    });
                    this.$forceUpdate();
                }else{
                    return this.$message.error(rs.msg);
                }
            }
            
            
        },
        
    
        // 获取聊天好友列表
        get_chat_friend:function(){
            this.$get(this.$api.sellerChatFriends).then(res=>{
                if(res.code == 200){
                    this.chat_friend = res.data.data;
                    this.$forceUpdate();
                }else{
                    this.$message.error(res.msg);
                }
            });
        },
       
        // 获取聊天信息列表
        get_chat_msg:function(info){
            this.$post(this.$api.sellerChatChatMsg,{uid:info.id,page:this.page}).then(res=>{
                if(res.code == 200){
                    this.chat_friend.forEach((item,key) => {
                        if(item.user_id == info.id){
                            res.data.data = res.data.data.reverse();
                            this.chat_friend[key].chat_msg = res.data;
                            this.clearNoRead(this.storeInfo.id,item.id); // 将该正在聊天未读标记已读
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
        // this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        // this.get_user_info();
        // 判断token是否失效
        this.$get(this.$api.sellerCheckLogin).then(res=> {
            if(res.code == 200){
                this.storeInfo = res.data;
                this.websocketInit();
            }
        });
        
        
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
    img{
      width: 100%;  
    }
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
                line-height: 40px;
                margin-right: 15px;
            }
        }
        .chat_interface_right_content{
            height: 280px;
            padding-bottom: 15px;
            box-sizing: border-box;
            overflow-y: scroll;
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
                    max-width: 250px;
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
    position: relative;
    left: 50%;
    top: 50%;
    margin-top:330px;
    background: #fff;
    transform: translate(-50%, -50%);    /* 50%为自身尺寸的一半 */
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

.chat_friend_interface{
    z-index: 1000;
    width: 260px;
    height: 520px;
    position: fixed;
    right: 0;
    bottom: 0;
    background: #fff;
    
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


</style>