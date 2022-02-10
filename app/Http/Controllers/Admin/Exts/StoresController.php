<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    protected $modelName = 'Store';

    public function update(Request $request, $id)
    {
        try {
            $rs = $this->getService($this->modelName)->editStore($id, 'id', 'admins');
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
