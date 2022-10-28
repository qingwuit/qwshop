import qs from "qs";
import axios from "axios";
import _ from "lodash";
import { ElMessage } from "element-plus";
import { isEmpty } from "lodash";
import { loginBaseData } from "@/plugins/config";
import router from "./router";

axios.defaults.timeout = 30000; // 请求超时
axios.defaults.headers.post["Content-Type"] =
    "application/x-www-form-urlencoded;charset=UTF-8";
axios.defaults.headers.put["Content-Type"] =
    "application/x-www-form-urlencoded;charset=UTF-8";
// axios.defaults.withCredentials = true; // 允许跨域携带cookie

const prefixUri = "/api";
let token = "";
let tokenName = "token";
let loginName = "";
let loginData = loginBaseData();

// 添加请求拦截器
axios.interceptors.request.use(
    function (config) {
        // 在发送请求之前做些什么

        // 获取localStorage 内token
        tokenName = "token";
        loginData.map((item) => {
            if (
                config.url.indexOf(item.loginName) != -1 &&
                item.tokenName != ""
            )
                tokenName = item.tokenName + "_token";
        });
        token = localStorage.getItem(tokenName);
        if (!R.isEmpty(token)) {
            config.headers["Authorization"] = "Bearer " + token; // 如果token 存在则携带token访问
        }

        // 聊天相关
        if (config.url.indexOf("api/Chat") != -1) {
            token = config.data.split("token")[1].split("&")[0];
            if (!R.isEmpty(token)) {
                config.headers["Authorization"] = "Bearer " + token.slice(1); // 如果token 存在则携带token访问
            }
        }

        // 验证登录
        if (config.url.indexOf("api/is_login") != -1) {
            token = localStorage.getItem("setToken");
            if (!R.isEmpty(token)) {
                config.headers["Authorization"] = "Bearer " + token; // 如果token 存在则携带token访问
            }
        }

        return config;
    },
    function (error) {
        // 对请求错误做些什么
        return Promise.reject(error);
    }
);

// 添加响应拦截器
axios.interceptors.response.use(
    function (res) {
        // 对响应数据做点什么
        // eslint-disable-next-line no-console
        // console.log(res.data.code);
        tokenName = "token";
        loginData.map((item, key) => {
            if (
                res.config.url.indexOf(item.loginName) != -1 &&
                item.tokenName != ""
            ) {
                tokenName = item.tokenName + "_token";
            }
        });

        if (res.status != 200) {
            return ElMessage.error(res.statusText);
        }

        // 如果出现401 代表token 失效
        if (res.data.code == 401) {
            ElMessage.error(res.data.msg);
            if (tokenName != "token") {
                localStorage.removeItem(tokenName);
                return router.push(loginName + "login");
            } else {
                localStorage.removeItem(tokenName);
                return router.push("/login");
            }
        }

        // 如果出现402 代表接口无权限 失效
        if (res.data.code == 402) {
            return ElMessage.error(res.data.msg);
        }

        // 如果出现405 代表未登录，但是返回数据
        if (res.data.code == 405) {
            return res.data.data;
        }

        // 429 代表请求太频繁
        if (res.data.code == 429) {
            return ElMessage.error("您请求太频繁了，请休息一会");
        }

        // 刷新了token 则重新存放
        if (!R.isEmpty(res.headers.authorization)) {
            var token = res.headers.authorization.split(" ")[1];
            localStorage.setItem(tokenName, token);
        }

        if (
            res.data &&
            (res.data.data || res.data.data === false || res.data.data === 0) &&
            res.data.code == 200
        )
            return res.data.data;
        if (
            res.data &&
            (res.data.data || res.data.data === false || res.data.data === 0) &&
            res.data.code != 200
        ) {
            ElMessage.error(res.data.msg);
            return res.data;
        }
        // 防止多次出现
        // ElMessage.destroy();
        return res;
    },
    function (err) {
        // eslint-disable-next-line no-console
        // console.log(err);

        // 如果地址无法请求
        if (R.isEmpty(err.response)) {
            // console.log(err)
            console.error("网络异常，请检查！");
            // ElMessage.error("网络异常，请检查！");
        }

        // 存在状态码
        if (err.response.status) {
            switch (err.response.status) {
                case 404:
                    ElMessage.error("Not Found Pages 404.");
                    break;
                case 500:
                    ElMessage.error(
                        "500 (Internal Server Error) " +
                            err.response.data.message
                    );
                    break;
                case 401:
                    // token 失效 这里可以加删除token 信息
                    ElMessage.error(err.response.data.message);
                    break;
                default:
                    ElMessage.error(
                        err.response.statusText +
                            ",error_code：" +
                            err.response.status
                    );
                    break;
            }
        } else {
            ElMessage.error(
                "未知错误,错误信息：" + err.response.statusText + "！"
            );
        }

        // 对响应错误做点什么
        return Promise.reject(err);
    }
);

/*对象转json*/
// export function toJson(data){
//   var json = qs.stringify(data);
//   return json;
// }

const R = {
    /* apihandle */
    apiHandle: (url, id = 0) => {
        let status = false;
        if (id > 0) {
            status = true;
            url += "/" + id;
        }
        return { url: url, status: status }; // 编辑则为false
    },

    /*判断是否为空*/
    isEmpty: (str) => {
        if (str === "" || str === null || str === undefined) {
            return true;
        }
        return false;
    },

    // 格式化金额
    formatFloat(value, length = 2) {
        let tempNum = 0;
        let s, temp;
        let s1 = value + "";
        let start = s1.indexOf(".");
        if (start == -1) s1 += ".00";
        start = s1.indexOf(".");
        if (s1.substr(start + length + 1, 1) >= 5) {
            tempNum = 1;
        }
        temp = Math.pow(10, length);
        s = Math.floor(value * temp) + tempNum;
        return s / temp;
    },

    // 获取语种
    getLocalesName: () => {
        let locales = localStorage.getItem("language");
        let language = !isEmpty(locales)
            ? locales
            : (
                  (navigator.language
                      ? navigator.language
                      : navigator.userLanguage) || "zh-cn"
              ).toLowerCase();
        if (language == "zh" || language == "cn") language = "zh-cn";
        return language;
    },

    // 获取拥有语种
    loadLocaleMessages: () => {
        const locales = require.context(
            "@/locales",
            true,
            /[A-Za-z0-9-_,\s]+\.json$/i
        );
        const messages = {};
        for (const key of locales.keys()) {
            const matched = key.match(/([A-Za-z0-9-_]+)\./i);
            if (matched && matched.length > 1) {
                const locale = matched[1];
                messages[locale] = {
                    ...locales(key),
                };
            }
        }
        return messages;
    },

    /**
     * get方法，对应get请求
     * @param {String} url [请求的url地址]
     * @param {Object} params [请求时携带的参数]
     */
    get: (url, params, isSource = false) => {
        return new Promise((resolve, reject) => {
            axios
                .get((!isSource ? prefixUri : "") + url, {
                    params: params,
                })
                .then((res) => {
                    resolve(res);
                })
                .catch((err) => {
                    reject(err.data);
                });
        });
    },

    put: (url, params, isSource = false) => {
        return new Promise((resolve, reject) => {
            if (params.is_type == "") params.is_type = 0;
            if (params.is_sort == "") params.is_sort = 0;
            axios
                .put((!isSource ? prefixUri : "") + url, qs.stringify(params))
                .then((res) => {
                    resolve(res);
                })
                .catch((err) => {
                    reject(err.data);
                });
        });
    },

    deletes: (url, params, isSource = false) => {
        return new Promise((resolve, reject) => {
            axios
                .delete((!isSource ? prefixUri : "") + url, {
                    params: params,
                })
                .then((res) => {
                    resolve(res);
                })
                .catch((err) => {
                    reject(err.data);
                });
        });
    },
    /**
     * post方法，对应post请求
     * @param {String} url [请求的url地址]
     * @param {Object} params [请求时携带的参数]
     */
    post: (url, params, isSource = false) => {
        return new Promise((resolve, reject) => {
            if (params.is_type == "") params.is_type = 0;
            if (params.is_sort == "") params.is_sort = 0;
            axios
                .post((!isSource ? prefixUri : "") + url, qs.stringify(params))
                .then((res) => {
                    resolve(res);
                })
                .catch((err) => {
                    reject(err.data);
                });
        });
    },

    /**
     * post方法，对应post请求,file 文件上传
     * @param {String} url [请求的url地址]
     * @param {Object} params [请求时携带的参数]
     */
    postfile: (url, params, isSource = false) => {
        return new Promise((resolve, reject) => {
            axios
                .post((!isSource ? prefixUri : "") + url, params, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((res) => {
                    resolve(res);
                })
                .catch((err) => {
                    reject(err.data);
                });
        });
    },
};
export default R;
