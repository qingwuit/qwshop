import qs from 'qs'; 
import axios from 'axios'
import {message} from 'ant-design-vue';
import router from './router'

axios.defaults.timeout = 5000;  // 请求超时
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8';
// axios.defaults.withCredentials = true; // 允许跨域携带cookie

const adminPatt = /api\/Admin/i;
const sellerPatt = /api\/Seller/i;

// 添加请求拦截器
axios.interceptors.request.use(function (config) {
    // 在发送请求之前做些什么

    // 获取localStorage 内token
    
    let adminIndex = config.url.search(adminPatt);
    let sellerIndex = config.url.search(sellerPatt);

    let token = '';
    if(adminIndex>-1){
        token = localStorage.getItem('admin_token');
        sessionStorage.setItem('token_type','admin_token');
    }else if(sellerIndex>-1){
        token = localStorage.getItem('seller_token');
        sessionStorage.setItem('token_type','seller_token');
    }else{
        token = localStorage.getItem('token');
        sessionStorage.setItem('token_type','token');
    }

    
    if(!isEmpty(token)){
		config.headers['Authorization'] = 'Bearer '+token; // 如果token 存在则携带token访问
    }
    

    return config;
  }, function (error) {
    // 对请求错误做些什么
    return Promise.reject(error);
  });


// 添加响应拦截器
axios.interceptors.response.use(function (res) {
    // 对响应数据做点什么
    // eslint-disable-next-line no-console
    // console.log(res.data.code);

    let adminIndex = res.config.url.search(adminPatt);
    let sellerIndex = res.config.url.search(sellerPatt);

    
    
    if(res.status != 200){
        return message.error(res.statusText);
	}
	
	// 如果出现401 代表token 失效
	if(res.data.code == 401){
        message.error(res.data.msg);
        if(adminIndex>-1){
            localStorage.removeItem('admin_token');
            router.push('/Admin/login');
        }else if(sellerIndex>-1){
            localStorage.removeItem('seller_token');
            router.push('/Seller/login');
        }else{
            localStorage.removeItem('token');
            localStorage.removeItem('user_info');
            router.push('/user/login');
        }
        
        
    }

    // 如果出现402 代表接口无权限 失效
	if(res.data.code == 402){
        return message.error(res.data.msg);
    }
    
    // 429 代表请求太频繁
	if(res.data.code == 429){
        return message.error("您请求太频繁了，请休息一会");
	}

	// 刷新了token 则重新存放
	if(!isEmpty(res.headers.authorization)){
        var token = res.headers.authorization.split(" ")[1];
        
        if(adminIndex>-1){
            localStorage.setItem('admin_token',token);
            sessionStorage.setItem('token_type','admin_token');
        }else if(sellerIndex>-1){
            localStorage.setItem('seller_token',token);
            sessionStorage.setItem('token_type','seller_token');
        }else{
            localStorage.setItem('token',token);
            sessionStorage.setItem('token_type','token');
        }
		
	}
    
	// 防止多次出现
    message.destroy();

    return res;
  }, function (err) {
    // eslint-disable-next-line no-console
    // console.log(err);

    // 如果地址无法请求
    if(isEmpty(err.response)){
        message.error("网络异常，请检查！");
    }

    // 存在状态码
    if (err.response.status) {
        switch(err.response.status){
            case 404:
                message.error('error_code:404');
                break;
            case 500:
                message.error('error_code:500');
            break;
            // case 401:
            // // token 失效
            //     if(err.response.statusText == 'Unauthorized'){
            //         if(err.response.data.message == undefined){
            //         Message.error(err.response.data);
            //         }else{
            //         Message.error(err.response.data.message);
            //         window.location.href='/#/admin/login';
            //         }
            //     }else{
            //         Message.error(err.response.statusText+",Code："+err.response.status+"！");
            //     }
            //     break;

            default:
                message.error(err.response.statusText+",error_code："+err.response.status);
            break;
        }
      
    }else{
        message.error("未知错误,错误信息："+err.response.statusText+"！");
    }
    
    // 对响应错误做点什么
    return Promise.reject(err);
  });


/*对象转json*/
export function toJson(data){
  var json = qs.stringify(data);
  return json;
}

/* apihandle */
export function apiHandle(url,id=0){
    let status = false;
    if(id>0){
        status = true;
        url += '/'+id
    }
    return {url:url,status:status}; // 编辑则为false
}

/*判断是否为空*/
export function isEmpty(str){
  if(str === '' || str === null || str === undefined){
    return true;
  }
  return false;
}


/** 
 * get方法，对应get请求 
 * @param {String} url [请求的url地址] 
 * @param {Object} params [请求时携带的参数] 
 */
export function get(url, params){ 
 return new Promise((resolve, reject) =>{  
  axios.get(url, {   
   params: params  
  })  
  .then(res => {   
   resolve(res.data);  
  })  
  .catch(err => {   
   reject(err.data)  
  }) 
 });
}

export function put(url, params){ 
 return new Promise((resolve, reject) =>{  
  axios.put(url, qs.stringify(params))  
  .then(res => {   
   resolve(res.data);  
  })  
  .catch(err => {   
   reject(err.data)  
  }) 
 });
}

export function deletes(url, params){ 
 return new Promise((resolve, reject) =>{  
  axios.delete(url, {   
   params: params  
  })  
  .then(res => {   
   resolve(res.data);  
  })  
  .catch(err => {   
   reject(err.data)  
  }) 
 });
}
/** 
 * post方法，对应post请求 
 * @param {String} url [请求的url地址] 
 * @param {Object} params [请求时携带的参数] 
 */
export function post(url, params) { 
 return new Promise((resolve, reject) => {   
  axios.post(url, qs.stringify(params))  
  .then(res => {   
   resolve(res.data);  
  })  
  .catch(err => {   
   reject(err.data)  
  }) 
 });
}

/** 
 * post方法，对应post请求,file 文件上传 
 * @param {String} url [请求的url地址] 
 * @param {Object} params [请求时携带的参数] 
 */
export function postfile(url, params) { 
    return new Promise((resolve, reject) => {   
     axios.post(url, params,{headers:{'Content-Type': 'multipart/form-data'}})  
     .then(res => {   
      resolve(res.data);  
     })  
     .catch(err => {   
      reject(err.data)  
     }) 
    });
   }
 //Vue.use(axios)