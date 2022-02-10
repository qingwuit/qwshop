<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadsController extends Controller
{
    public function upload(Request $request)
    {
        $uploadType = request('uploadType', 'uploadPic');
        $name = $request->name??'';
        $option = isset($request->option)?json_decode($request->option, true):[];
        try {
            $rs = $this->getService('Uploads')->$uploadType($name, $option);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->handle($rs);
    }
}
