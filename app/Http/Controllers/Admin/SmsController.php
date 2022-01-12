<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected $modelName = 'Sms';
    
    // public function send(){
    //     return $this->handle($this->getService('Sms')->sendSms('15073010917','register'));
    // }
}
