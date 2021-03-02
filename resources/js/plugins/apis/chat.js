import common from './common';
const baseUrl = common.baseUrl;
export default 
{
     /**
     * 青梧商城聊天接口
     * <www.qingwuit.com>
     */

    "chatFriends" : baseUrl + "Chat/friends", // 好友列表
    "chatAddFriend" : baseUrl + "Chat/add_friend", // 添加好友
    "chatChatMsg" : baseUrl + "Chat/chat_msg", // 聊天信息
    "chatReadMsg" : baseUrl + "Chat/read_msg", // 阅读
    "chatChatEvent" : baseUrl + "Chat/chat_event", // 事件
    "chatImage" : baseUrl + "Chat/image", // 图片上传

    // 商家
    "sellerChatFriends" : baseUrl + "Seller/chat_friends", // 好友列表
    "sellerChatChatMsg" : baseUrl + "Seller/chat_msg", // 聊天信息
    "sellerChatReadMsg" : baseUrl + "Seller/chat_read_msg", // 阅读
    "sellerChatChatEvent" : baseUrl + "Seller/chat_event", // 事件
   
    

    
};
