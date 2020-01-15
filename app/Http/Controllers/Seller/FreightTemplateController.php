<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\FreightTemplate;

class FreightTemplateController extends BaseController
{
    public function index(FreightTemplate $freight_template_model){
        $user_info = auth()->user();
        $list = $freight_template_model->where('user_id',$user_info['id'])->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,FreightTemplate $freight_template_model){
        $user_info = auth()->user();
        $data = [
            'user_id'       =>  $user_info['id'],
            'name'          =>  $req->name,
            'content'       =>  isset($req->list)?json_encode($req->list):'',
            'price'         =>  floatval($req->price),
        ];
        $rs = $freight_template_model->insert($data);
        return $this->success_msg('ok',$rs);
    }

    public function edit(Request $req,FreightTemplate $freight_template_model,$id){
        if(!$req->isMethod('post')){
            $info = $freight_template_model->find($id);
            $info['content'] = json_decode($info['content']);
            return $this->success_msg('ok',$info);
        }
        $data = [
            'name'          =>  $req->name,
            'content'       =>  isset($req->list)?json_encode($req->list):'',
            'price'         =>  floatval($req->price),
        ];

        $rs = $freight_template_model->where('id',$id)->update($data);
        return $this->success_msg('ok',$rs);
    }

    public function del(Request $req,FreightTemplate $freight_template_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $freight_template_model->destroy($ids);
        return $this->success_msg();
    }
}
