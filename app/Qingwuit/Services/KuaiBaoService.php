<?php

namespace App\Qingwuit\Services;

class KuaiBaoService extends BaseService
{
    public $url = "https://kop.kuaidihelp.com/api";
    // HTTP请求POST body值传入form_params.  GET 传入 query
    public function getExpressInfo($delivery_no, $delivery_code, $phone)
    {
        $info = $this->getService('Configs')->getFormatConfig('kuaibao');
        $url = $this->url;
        $appkey = $info['appkey'];
        $data['method'] = 'express.info.get';
        $data['app_id'] = $info['appid'];
        $data['ts'] = time();
        $data['sign'] = md5($data['app_id'] . $data['method'] . $data['ts'] . $appkey);

        $data2['waybill_no'] = $delivery_no;
        $data2['exp_company_code'] = $delivery_code;
        $data2['result_sort'] = 0;
        $data2['phone'] = substr($phone, -4);

        $data['data'] = json_encode($data2);
        $response = $this->getService('Tool')->httpRequest($url, 'POST', $data);
        $kbInfo = json_decode($response['data'], true);
        if ($kbInfo['code'] != 0) {
            return $this->formatError($kbInfo['msg']);
        }
        $data = $kbInfo['data'][0]['data'];
        return $this->format($data);
    }

    // 打印电子面单
    public function printWaybill($orderId)
    {
        $order = $this->getService('Order', true)->where('id', $orderId)->first();
        if (!$order) return $this->formatError();
        $store = $this->getService('Store', true)->where('id', $order->store_id)->first();

        // agent_id 到时候根据每个商家去再改

        $info = $this->getService('Configs')->getFormatConfig('kuaibao');
        $url = $this->url;
        $appkey = $info['appkey'];
        $data['method'] = 'cloud.print.waybill';
        $data['app_id'] = $info['appid'];
        $data['ts'] = time();
        $data['sign'] = md5($data['app_id'] . $data['method'] . $data['ts'] . $appkey);

        $data2['agent_id'] = $info['agent_id'];
        $data2['print_type'] = 3;
        $data2['private'] = 1;
        // $data2['template_id'] = ''; // 打印模板ID（点此查看系统支持的所有模板）；如果为空，则以云打印机上设置的模板为准，可到“微快递小邮筒”公众号的“管理”->“云打印机设置”中设置默认的模板
        $data2['print_data'] = [
            [
                'tid'           => $order->order_no,
                'cp_code'       => $order->delivery_code,
                'goods_name'    => $order->order_name,
                "note"          => $order->remark,
            ]
        ];
        $area = explode(' ', $store->area_info);
        $data2['sender'] = [
            'mobile'    =>  empty($store->store_mobile) ? '10000000000' : $store->store_mobile,
            'phone'     =>  empty($store->store_mobile) ? '10000000000' : $store->store_mobile,
            'name'      =>  $store->store_name,
            'address'   =>  [
                'province'  =>  $area[0] ?? '北京市',
                'city'      =>  $area[1] ?? '市辖区',
                'district'  =>  $area[2] ?? '朝阳区',
                'detail'    =>  empty($store->address) ? '-' : $store->address,
            ]
        ];
        $area = explode(' ', $order->receive_area);
        $data2['sender'] = [
            'mobile'    =>  empty($order->receive_tel) ? '10000000000' : $order->receive_tel,
            'phone'     =>  empty($order->receive_tel) ? '10000000000' : $order->receive_tel,
            'name'      =>  $order->payment_name,
            'address'   =>  [
                'province'  =>  $area[0] ?? '北京市',
                'city'      =>  $area[1] ?? '市辖区',
                'district'  =>  $area[2] ?? '朝阳区',
                'detail'    =>  empty($order->receive_address) ? '-' : $order->receive_address,
            ]
        ];

        $data['data'] = json_encode($data2);
        $response = $this->getService('Tool')->httpRequest($url, 'POST', $data);
        $kbInfo = json_decode($response['data'], true);

        if (!isset($kbInfo['data'][$order->order_no]['task_info']['waybill_code'])) {
            return $this->formatError(json_encode($kbInfo));
        }
        $waybill_code = $info['data'][$order->order_no]['task_info']['waybill_code'];
        $order->delivery_no = $waybill_code;
        $order->save();
        return $this->format($waybill_code);
    }
}
