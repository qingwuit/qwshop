<?php
namespace App\Services;
use Endroid\QrCode\QrCode;

class ToolService extends BaseService{
    // 生成二维码
    public function create_qrcode($str=''){
        $qrCode = new QrCode($str);
        // Save it to a file
        return $qrCode->writeDataUri();
    }

    /**
    * 对银行卡号进行掩码处理
    * 掩码规则头4位,末尾余数位不变，中间4的整数倍字符用星号替换，并且用每隔4位空格隔开
    * @param  string $bankCardNo 银行卡号
    * @return string             掩码后的银行卡号
    */
    public function formatBankCardNo($bankCardNo){
        //每隔4位分割为数组
        $split = str_split($bankCardNo,4);    
        //头和尾保留，其他部分替换为星号       
        $split = array_fill(1,count($split) - 2,"****") + $split;
        ksort($split);
        //合并
        return implode(" ",$split);
    }
 
    /**
    * 对手机号进行掩码处理
    * 手机号掩码的规则是末尾4位，开头余数位不变，中间4的整数倍字符用星号替换   
    * @param string $mobile 手机号
    * @return string
    */
    public function formatMobile($mobile){
         //颠倒顺序每隔4位分割为数组
        $split = str_split(strrev($mobile),4);    
        //头和尾保留，其他部分替换为星号  
        $split = array_fill(1,count($split) - 2,"****") + $split;
        ksort($split);
        //合并颠倒顺序
        return strrev( implode("",$split));
    }
 
    /**
    * 姓名第一个字掩码
    * @param string $true_name 真实姓名
    * @return string
    */
    public function formatTrueName($true_name){      
        return "*" . mb_substr($true_name,1);
    }

    /**
    * 只显示最后一个字符
    * @param string $true_name 真实姓名
    * @return string
    */
    public function formatTrueName2($true_name){   
        if(mb_strlen($true_name)>4){
            return "***" . mb_substr($true_name,-4,4);
        }else{
            return "***" . mb_substr($true_name,-1,1);
        }   
        
    }
 
    /**
    * 对身份证进行掩码
    * 掩码规则是显示头两位和末尾1位，中间星号
    * @param string $id_card 身份证号
    * @return string
    */
    public function formatIdCard($id_card){      
         //每隔1位分割为数组
        $split = str_split($id_card,1);    
        //头2位和尾保留，其他部分替换为星号     
        $split = array_fill(2,count($split) - 3,"*") + $split;
        ksort($split);
        //合并
        return implode("",$split);
    }

}
