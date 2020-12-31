<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SmsSignResource\SmsSignCollection;
use App\Models\SmsSign;
use Illuminate\Http\Request;

class SmsSignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,SmsSign $sms_sign_model)
    {
        $list = $sms_sign_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new SmsSignCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,SmsSign $sms_sign_model)
    {
        $sms_sign_model->name = $request->name??'';
        $sms_sign_model->val = $request->val??'';
        $sms_sign_model->code = $request->code??'';
        $sms_sign_model->content = $request->content??'';
        $sms_sign_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SmsSign $sms_sign_model,$id)
    {
        $info = $sms_sign_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,SmsSign $sms_sign_model, $id)
    {
        $sms_sign_model = $sms_sign_model->find($id);
        $sms_sign_model->name = $request->name??'';
        $sms_sign_model->val = $request->val??'';
        $sms_sign_model->code = $request->code??'';
        $sms_sign_model->content = $request->content??'';
        $sms_sign_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsSign $sms_sign_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $sms_sign_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
