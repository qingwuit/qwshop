<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public $modelName = 'Favorite';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function index(Request $request)
    {
        return $this->handle($this->getService('Favorite')->fav());
    }

    public function store(Request $request)
    {
        return $this->handle($this->getService('Favorite')->add());
    }

    public function is_fav()
    {
        return $this->handle($this->getService('Favorite')->isFav());
    }
}
