<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $info = $this->getService('Configs')->getFormatConfig($request->name, $request->many_name??null, $request->many??false);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        
        return $this->success($info);
    }

  
    public function edit(Request $request)
    {
        $isCustom = $request->is_custom??null;
        return $this->handle($this->getService('Configs')->editConfig($isCustom, $request->many_name??null, $request->many??false));
    }
}
