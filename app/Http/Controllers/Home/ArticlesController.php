<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function article(Request $request)
    {
        $article = $this->getService('Article', true)->where('name', $request->name??'')->first();
        if ($article) {
            $article->increment('click');
            return $this->success($article);
        }
        return $this->error($article);
    }
}
