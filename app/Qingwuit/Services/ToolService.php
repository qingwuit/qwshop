<?php

namespace App\Qingwuit\Services;

use GuzzleHttp\Client;

class ToolService extends BaseService
{
    /**
     * 对银行卡号进行掩码处理
     * 掩码规则头4位,末尾余数位不变，中间4的整数倍字符用星号替换，并且用每隔4位空格隔开
     * @param  string $bankCardNo 银行卡号
     * @return string             掩码后的银行卡号
     */
    public function formatBankCardNo($bankCardNo)
    {
        //每隔4位分割为数组
        $split = str_split($bankCardNo, 4);
        //头和尾保留，其他部分替换为星号
        $split = array_fill(1, count($split) - 2, "****") + $split;
        ksort($split);
        //合并
        return implode(" ", $split);
    }

    /**
     * 对手机号进行掩码处理
     * 手机号掩码的规则是末尾4位，开头余数位不变，中间4的整数倍字符用星号替换
     * @param string $mobile 手机号
     * @return string
     */
    public function formatMobile($mobile)
    {
        //颠倒顺序每隔4位分割为数组
        $split = str_split(strrev($mobile), 4);
        //头和尾保留，其他部分替换为星号
        $split = array_fill(1, count($split) - 2, "****") + $split;
        ksort($split);
        //合并颠倒顺序
        return strrev(implode("", $split));
    }

    /**
     * 姓名第一个字掩码
     * @param string $true_name 真实姓名
     * @return string
     */
    public function formatTrueName($true_name)
    {
        return "*" . mb_substr($true_name, 1);
    }

    /**
     * 只显示最后一个字符
     * @param string $true_name 真实姓名
     * @return string
     */
    public function formatTrueName2($true_name)
    {
        if (mb_strlen($true_name) > 4) {
            return "***" . mb_substr($true_name, -4, 4);
        } else {
            return "***" . mb_substr($true_name, -1, 1);
        }
    }

    /**
     * 对身份证进行掩码
     * 掩码规则是显示头两位和末尾1位，中间星号
     * @param string $id_card 身份证号
     * @return string
     */
    public function formatIdCard($id_card)
    {
        //每隔1位分割为数组
        $split = str_split($id_card, 1);
        //头2位和尾保留，其他部分替换为星号
        $split = array_fill(2, count($split) - 3, "*") + $split;
        ksort($split);
        //合并
        return implode("", $split);
    }

    // 递归无线树状结构
    public function getTree($data, $pid = 0, $lev = 0)
    {
        static $arr = [];
        foreach ($data as $v) {
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $arr[] = $v;
                $this->getTree($data, $v['id'], $lev + 1);
            }
        }
        return $arr;
    }

    // 递归无线树状结构 多元数组
    public function getChildren($data, $pid = 0, $lev = 0, $deep = -1)
    {
        $arr = [];
        if ($deep > -1 && $lev == $deep) {
            return $arr;
        }
        foreach ($data as $v) {
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $v['children'] = $this->getChildren($data, $v['id'], $lev + 1, $deep);
                if (count($v['children']) <= 0) {
                    unset($v['children']);
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }
    // 引用方式 树状结构
    public function getAreaChildren($data, $pid = 0, $lev = 0, $deep = -1)
    {
        $data = is_array($data) ? $data : $data->toArray();
        $data = array_column($data, Null, "code");
        $new_data = [];
        foreach ($data as $k => $v) {
            if ($deep > -1 && $v['deep'] > $deep) continue;
            //判断数据为第几级
            $data[$k]["lev"] = (strlen(strval($v["code"])) / 2) - 1;
            if (isset($data[$v["pid"]])) {
                $data[$v["pid"]]["children"][] = &$data[$k];
            } else {
                $new_data[] = &$data[$k];
            }
        }
        return $new_data;
    }

    // 递归无线树状结构 多元数组
    public function getAreaChildren2($data, $pid = 0, $lev = 0, $deep = -1)
    {
        $arr = [];
        if ($deep > -1 && $lev == $deep) {
            return $arr;
        }
        foreach ($data as $v) {
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $v['children'] = $this->getAreaChildren($data, $v['code'], $lev + 1, $deep);
                if (count($v['children']) <= 0) {
                    unset($v['children']);
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }


    // 手机号码检测
    public function checkPhone($phone)
    {
        if (preg_match("/^1[3456789]\d{9}$/", $phone)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * 获取图片的缩略图
     * @author 青梧系统 <www.qwsystem.com>
     *
     */

    public function thumb($path, $size = '300')
    {
        $len = strripos($path, '.');
        $ext = substr($path, $len);
        $name = substr($path, 0, $len);
        return $name . '_' . $size . $ext;
    }

    /**
     *
     * 获取图片的缩略图
     * @author 青梧系统 <www.qwsystem.com>
     *
     */

    public function thumb_array($arr, $size = '150')
    {
        $data = [];
        foreach ($arr as $path) {
            $len = strripos($path, '.');
            $ext = substr($path, $len);
            $name = substr($path, 0, $len);
            $data[] = $name . '_' . $size . $ext;
        }
        return $data;
    }

    public function ToUrlParams(array $array)
    {
        $buff = "";
        foreach ($array as $k => $v) {
            if ($v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    // http 请求
    public function httpRequest($url, $method = 'GET', $data = [], $header = [], $type = "form_params")
    {
        $client = new Client(['verify' => false]); // 去掉证书验证
        $queryData = [];

        if ($method = 'GET') {
            $queryData = ['query' => $data];
        }
        if ($method = 'POST') {
            $queryData = [$type => $data];
        }

        if (!empty($header)) {
            $queryData['headers'] = $header;
        }
        $response = $client->request($method, $url, $queryData);
        $info = $response->getBody();
        $infos = $info->getContents();
        return $this->format($infos);
    }
}
