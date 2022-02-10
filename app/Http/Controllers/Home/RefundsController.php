<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefundsController extends Controller
{
    public function store(Request $request)
    {
        return $this->handle($this->getService('Refund')->add());
    }

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Refund')->edit($id));
    }

    public function show($id)
    {
        return $this->handle($this->getService('Refund')->getRefundByOrderId($id));
    }
}
