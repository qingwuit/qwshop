<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppVersionsController extends Controller
{
    protected $modelName = 'AppVersion';

    public function app_versions()
    {
        return $this->success(
            $this->getService($this->modelName,true)->where([
                'device'    =>  request('device','android'),
            ])
            ->orderBy('version_code','desc')
            ->first()
        );
    }
}
