<?php

namespace App\Http\Controllers;

use App\Qingwuit\Traits\ResourceTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,ResourceTrait;
    protected $modelName = null;
    protected $serviceName = 'Base';
    protected $setUser = false; // 根据用户来
    protected $isSuper = false; // 超级用户取全部
    protected $auth = 'admins';
    protected $belongName = 'belong_id';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $rs = $this->getService($this->serviceName)
                    ->setUserConfig(['setUser'=>($this->isSuper?false:$this->setUser),'auth'=>$this->auth,'belongName'=>$this->belongName])
                    ->getPageData($this->modelName);
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rs = $this->getService($this->serviceName)
                        ->setUserConfig(['setUser'=>($this->isSuper?false:$this->setUser),'auth'=>$this->auth,'belongName'=>$this->belongName])
                        ->addDat($this->modelName);
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $rs = $this->getService($this->serviceName)
                        ->setUserConfig(['setUser'=>($this->isSuper?false:$this->setUser),'auth'=>$this->auth,'belongName'=>$this->belongName])
                        ->findDat($this->modelName, $id);
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $rs = $this->getService($this->serviceName)
                        ->setUserConfig(['setUser'=>($this->isSuper?false:$this->setUser),'auth'=>$this->auth,'belongName'=>$this->belongName])
                        ->editDat($this->modelName, $id);
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rs = $this->getService('Base')
                        ->setUserConfig(['setUser'=>($this->isSuper?false:$this->setUser),'auth'=>$this->auth,'belongName'=>$this->belongName])
                        ->delDat($this->modelName, $id);
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    // get User Info
    public function getUser($auth = '')
    {
        return $this->getService('Base')->getUser(empty($auth)?$this->auth:$auth);
    }

    // get User Id
    public function getUserId($auth = '')
    {
        return $this->getService('Base')->getUserId(empty($auth)?$this->auth:$auth);
    }

    // get belong Id
    public function getBelongId($auth = '')
    {
        return $this->getService('Base')->getBelongId(empty($auth)?$this->auth:$auth);
    }

    // get isSuper Id
    public function getSuper($auth = '')
    {
        return $this->getService('Base')->getSuper(empty($auth)?$this->auth:$auth);
    }

    // get Roles
    public function getRoles($auth = '', $with = ['menus','permissions'])
    {
        return $this->getService('Base')->getRoles(empty($auth)?$this->auth:$auth, $with);
    }
}
