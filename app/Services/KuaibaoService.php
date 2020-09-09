<?php
namespace App\Services;

use App\Models\Config;
use GuzzleHttp\Client;

/**
 * 快宝第三方平台物流查询
 *
 * @Description
 * @author hg <www.qingwuit.com>
 */
class KuaibaoService extends BaseService{
    
    public function getExpressInfo($delivery_no,$delivery_code,$phone){
        $config_service = new Config();
        $info = json_decode($config_service->getFormatConfig('kuaibao'),true);
        $url = 'https://kop.kuaidihelp.com/api';
        $appkey = $info['app_key'];
        $data['method'] = 'express.info.get';
        $data['app_id'] = $info['app_id'];
        $data['ts'] = time();
        $data['sign'] = md5($data['app_id'].$data['method'].$data['ts'].$appkey);

        $data2['waybill_no'] = $delivery_no;
        $data2['exp_company_code'] = $delivery_code;
        $data2['result_sort'] = 0;
        $data2['phone'] = substr($phone,-4);

        $data['data'] = json_encode($data2);
        $info = $this->http_send($url,$data,'POST');
        $infos = $info->getContents();
        $data = json_decode($infos,true);
        return $data;
    }

    // 发送http请求
    public function http_send($url,$data=[],$method="GET"){
        $client = new Client();
        $params = [];
        if($method == 'GET'){
            $params['query'] = $data;
        }elseif($method == 'POST'){
            $params['form_params'] = $data;
        }elseif($method == 'json'){
            $method="POST";
            $params['json'] = $data;
        }
        $response = $client->request($method,$url,$params);
       
        return $response->getBody();
    }
}
