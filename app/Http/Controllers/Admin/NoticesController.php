<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoticesController extends Controller
{
    protected $modelName = 'Notice';
    protected $setUser = true;
}
