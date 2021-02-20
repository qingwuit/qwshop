<?php

namespace App\Http\Controllers;

use App\Http\Resources\Home\ConfigResource\ConfigResource;
use App\Services\ConfigService;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function index(ConfigService $config_service){
        $data = ConfigResource::make($config_service->getFormatConfig())->resolve();
        return view('index',$data);
    }
}
