import {message} from 'ant-design-vue';
export function formatDate (date, fmt) {
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    let o = {
        'M+': date.getMonth() + 1,
        'd+': date.getDate(),
        'h+': date.getHours(),
        'm+': date.getMinutes(),
        's+': date.getSeconds()
    };
    for (let k in o) {
        if (new RegExp(`(${k})`).test(fmt)) {
            let str = o[k] + '';
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str));
        }
    }
    return fmt;
}

export function getSession(name){
    let token_type = sessionStorage.getItem(name);
    return localStorage.getItem(token_type);
}

export function returnInfo(res){
    if(res.code == 200){
        return message.success(res.msg);
    }else{
        return message.error(res.msg)
    }
}

export function formatFloat(value,length=2){  
    let tempNum = 0;  
    let s,temp;  
    let s1 = value + "";  
    let start = s1.indexOf(".");  
    if(s1.substr(start+length+1,1)>=5){
        tempNum=1;  
    }
    temp = Math.pow(10,length);  
    s = Math.floor(value * temp) + tempNum;  
    return s/temp;  
}
 
function padLeftZero (str) {
    return ('00' + str).substr(str.length);
}

// 时间格式化
Vue.filter('formatDate', function (time) {
    var date = new Date(time*1000);
    return formatDate(date, 'yyyy-MM-dd hh:mm');
});

Vue.filter('formatDateAuto', function (time,str) {
    var date = new Date(time*1000);
    return formatDate(date, str);
});