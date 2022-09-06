<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashesController extends Controller
{
    protected $modelName = 'Cash';

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Cash')->edit($id));
    }
}
