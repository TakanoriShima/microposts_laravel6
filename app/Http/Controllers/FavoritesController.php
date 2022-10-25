<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * 投稿を"いいね"するアクション
    */
    public function store($id) {
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    /**
     * 投稿の"いいね"を取り消すアクション
    */
    public function destroy($id) {
        \Auth::user()->unfavorite($id);
        return back();
    }
}
