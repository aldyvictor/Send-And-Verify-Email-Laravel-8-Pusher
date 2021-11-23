<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use Auth;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('isarticle.owner')->only(['update']);
    }

    public function store(Request $request) {
        $article = new Article;
        $article->title = $request["title"];
        $article->body = $request["body"];
        $article->slug = Str::of($request["title"])->slug('-');

        $article->save();// insert into article
        
        return response()->json(["message" => "success", "article"=> $article], 201);
    }

    public function update(Request $request, $id) {
        return response()->json(["owner" => $request->article_owner]);
    }
}
