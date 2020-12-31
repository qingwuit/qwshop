<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(ArticleService $article_service,$ename){
        $info = $article_service->getArticle($ename);
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }
}
