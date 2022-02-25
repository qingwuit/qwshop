<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    protected $modelName = 'Address';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function store(Request $request)
    {
        return $this->handle($this->getService('Address')->add());
    }

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Address')->edit($id));
    }

    public function set_default($id)
    {
        return $this->handle($this->getService('Address')->setDefault($id));
    }

}
