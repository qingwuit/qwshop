<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FavoriteService $fav_service)
    {
        $rs = $fav_service->getFav();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FavoriteService $fav_service)
    {
        $rs = $fav_service->addFav($request->id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FavoriteService $fav_service,$id)
    {
        $rs = $fav_service->isFav($id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FavoriteService $fav_service,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });

        $rs = $fav_service->delFav($idArray);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }
}
