export const routeUriType = ['/Admin/','/Seller/']
// 无需登录接口
export const exclude = [
    '/Admin/login',
    '/Seller/login',
]

import router from './router'

export function loginBaseData(){
    let loginData = []
    routeUriType.map((item,key)=>{
        let tokenName = ''
        tokenName = (routeUriType[key].replaceAll('/','')).toLowerCase()
        loginData.push({isLogin:false,tokenName:tokenName,loginName:routeUriType[key]})
    })
    // 最后一个是用户的
    loginData.push({isLogin:false,tokenName:''})
    return loginData
}

export function _import(file) {
    return () => import('@/views/' + file + '.vue');
}

// 打开一个新窗口
export function _open(url,newWin = false){
    if(newWin) window.open(url)
    if(!newWin) window.location.href=url
    
}

// 跳转路由
export function _push(path){
    router.push(path)
}

// 定义地址链接
export function formatUrl(value){
    // console.log(value)
    if(value.indexOf('://') != -1){
        // 判断是http 还是https
        const url = indow.location.href
        let suf = 'http://'
        if(url.indexOf('https://') > -1){
            suf = 'https://'
        }
        value = suf+window.location.host+value
    }
    return value
}

// 获取当前的路由地址
export function getToken(){
    const url = window.location.href
    let index = 2;
    let tokenName = 'token'
    routeUriType.map((item,key)=>{
        if(url.indexOf(item)>-1){
            index = key
            tokenName = (item.replaceAll('/','').toLowerCase())+'_token'
        }
    })
    return 'Bearer '+localStorage.getItem(tokenName)
}

export function getUploadPath(){
    const url = window.location.href
    let index = 2;
    let path = '/'
    routeUriType.map((item,key)=>{
        if(url.indexOf(item)>-1){
            index = key
            path = item
        }
    })
    return path
}
//wangditor 中断字符串
export const editSplitStr = '##qingwuit##'
// wangeditor数据处理
export function editorHandle(data,index=0){
    if(!data && index==0) return '';
    if(!data && index==1) return {};
    const htmls = data.split(editSplitStr)
    if(htmls.length == 2){
        if(index == 1) return JSON.parse(htmls[index])
        return htmls[index]
    }
    return ''
}

// 格式化时间戳
export function formatTime(timeVal,type=true){
    var d = Math.floor(timeVal / (24 * 3600));
    var h = Math.floor((timeVal - 24 * 3600 * d) / 3600);
    var m = Math.floor((timeVal - 24 * 3600 * d - h * 3600) / 60);
    var s = Math.floor((timeVal - 24 * 3600 * d - h * 3600 - m * 60));
    // console.log(d + '天' + hh + '时' + mm + '分' + ss + '秒'); // 打印出转换后的时间
    //  当时分秒小于10的时候补0
    var hh = h < 10 ? '0' + h : h;
    var mm = m < 10 ? '0' + m : m;
    var ss = s < 10 ? '0' + s : s;
    // this.seckills.format_time =  d + '天' + hh + '时' + mm + '分' + ss + '秒';
    if(!type) return  hh + ' : ' + mm + ' : ' + ss 
    return d + ' 天 ' + hh + ' 时 ' + mm + ' 分 ' + ss + ' 秒'
}


