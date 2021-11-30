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

    public function index(Request $request) {
        $user = $request->user();
        $articles = Article::with('author', function($query) {
            $query->select(["name", "email"]);
        } )->get();
        return response()->json([
            "message" => "success",
            "data" => $articles
        ]);
    }

    public function store(Request $request) {
        $article = new Article;
        $article->title = $request["title"];
        $article->body = $request["body"];
        $article->slug = Str::of($request["title"])->slug('-');
        $article->user_id = $request->user()->id;
        $article->save();// insert into article
        
        return response()->json(["message" => "success", "article"=> $article], 201);
    }

    public function update(Request $request, $id) {
        return response()->json(["owner" => $request->article_owner]);
    }
}
