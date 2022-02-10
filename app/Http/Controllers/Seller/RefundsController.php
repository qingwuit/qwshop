<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefundsController extends Controller
{
    protected $modelName = 'Order';
    protected $auth = 'users';

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Refund')->edit($id, 'seller'));
    }
}
