<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agreement;
use App\Http\Resources\Admin\AgreementResource\AgreementCollection;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Agreement $agree_model)
    {
        if(!empty($request->title)){
            $agree_model = $agree_model->where('title','like','%'.$request->title.'%');
        }
        if(!empty($request->ename)){
            $agree_model = $agree_model->where('ename','like','%'.$request->ename.'%');
        }
        $list = $agree_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new AgreementCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Agreement $agree_model)
    {
        $agree_model->title = $request->title??'';
        $agree_model->ename = $request->ename??'';
        $agree_model->content = $request->content??'';
        $agree_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Agreement $agree_model,$id)
    {
        $info = $agree_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Agreement $agree_model, $id)
    {
        $agree_model = $agree_model->find($id);
        $agree_model->title = $request->title;
        $agree_model->ename = $request->ename??'';
        $agree_model->content = $request->content??'';
        $agree_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agreement $agree_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $agree_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
