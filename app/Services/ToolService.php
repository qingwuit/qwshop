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

}
