<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ArticleResource\ArticleCollection;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Article $article_model)
    {
        if(!empty($request->name)){
            $article_model = $article_model->where('name','like','%'.$request->name.'%');
        }
        if(!empty($request->ename)){
            $article_model = $article_model->where('ename','like','%'.$request->ename.'%');
        }
        $list = $article_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new ArticleCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Article $article_model)
    {
        $article_model->name = $request->name??'';
        $article_model->ename = $request->ename??'';
        $article_model->content = $request->content??'';
        $article_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article_model,$id)
    {
        $info = $article_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Article $article_model, $id)
    {
        $article_model = $article_model->find($id);
        $article_model->name = $request->name;
        $article_model->ename = $request->ename??'';
        $article_model->content = $request->content??'';
        $article_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $article_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
