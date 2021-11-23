<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Auth;

class isArticleOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // user yang mengakses harus yang punya article
        $user_id = Auth::user()->id;
        $param = $request->route()->parameter('article'); // id article
        $article = Article::find($param);
        // return response()->json(["article" => $article]);
        if($user_id != $article->user_id) {
            return response()->json(['message' => 'unauthorized, check'], 401);
        }
        $request->article_owner = Auth::user()->name;

        return $next($request);
    }
}
