<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache; 

class AutoController extends BaseController
{

    // 清空所有缓存
    public function cache_flush(){
        Cache::flush();
        return $this->success_msg('Success');
    }
}
