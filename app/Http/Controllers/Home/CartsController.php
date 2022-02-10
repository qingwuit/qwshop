<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public $modelName = 'Cart';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function index(Request $request)
    {
        return $this->handle($this->getService('Cart')->getCart());
    }

    public function store(Request $request)
    {
        return $this->handle($this->getService('Cart')->addCart());
    }

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Cart')->editCart($id));
    }

    public function count()
    {
        return $this->handle($this->getService('Cart')->getCount());
    }
}
