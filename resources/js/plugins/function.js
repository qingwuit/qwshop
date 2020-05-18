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
 
function padLeftZero (str) {
    return ('00' + str).substr(str.length);
}


// 产品数据格式化
export function formatGoods (data) {
    data.forEach(res=>{
        if(res.spec != undefined && res.spec.length != 0 && res.spec != null && res.spec !=''){
            res.goods_price = res.spec[0]['goods_price'];
            res.goods_market_price = res.spec[0]['goods_market_price'];
            res.goods_num = res.spec[0]['goods_num'];
            res.all_goods_num = 0;
            res.spec.forEach(specRes=>{
                res.all_goods_num += parseInt(specRes.goods_num);
            });
            
        }
    });
    
    return data;
}