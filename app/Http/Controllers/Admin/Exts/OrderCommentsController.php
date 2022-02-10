<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderCommentsController extends Controller
{
    protected $modelName = 'OrderComment';

    public function update(Request $request, $id)
    {
        $request->offsetSet('reply_time', now());
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['reply','reply_time']));
    }
}
