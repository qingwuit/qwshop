<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserChecksController extends Controller
{
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function edit()
    {
        return $this->handle($this->getService('UserCheck')->edit());
    }

    public function check()
    {
        return $this->handle($this->getService('UserCheck')->check());
    }
}
