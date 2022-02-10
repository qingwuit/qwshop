<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadsController extends Controller
{
    public function upload(Request $request)
    {
        $name = $request->name??'';
        $option = isset($request->option)?json_decode($request->option, true):[];
        try {
            $rs = $this->getService('Uploads')->uploadPic($name, $option);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->handle($rs);
    }
}
