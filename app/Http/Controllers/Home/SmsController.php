<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        $phone = $request->phone??'';
        $name = $request->name??'register';
        if (!$this->getService('Tool')->checkPhone($phone)) {
            return $this->error(__('tip.phoneThr'));
        }
        return $this->handle($this->getService('Sms')->sendSms($phone, $name));
    }
}
