<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    protected $modelName = 'OrderComment';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';
}
