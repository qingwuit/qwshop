<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\SmsLogResource\SmsLogCollection;

class SmsLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SmsLog $sms_log)
    {
        if(!empty($request->phone)){
            $sms_log = $sms_log->where('phone','like','%'.$request->phone.'%');
        }
        $list = $sms_log->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new SmsLogCollection($list));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsLog $sms_log_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $sms_log_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
    
}
