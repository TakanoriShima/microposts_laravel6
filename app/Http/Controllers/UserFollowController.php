<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * フォロー機能のためのController
 * フォローするためのstoreメソッドとアンフォローするためのdestroyメソッドを作成
 * 
 * storeメソッドではUser.phpに定義されているfollowメソッドを使って、ユーザをフォローできるようする
 * destroyメソッドではUser.phpに定義されているunfollowメソッドを使ってユーザをアンフォローできるようにします。
*/

class UserFollowController extends Controller
{
    /**
     * ユーザをフォローするアクション
     * 
     * @param 
     *  $id 相手のユーザid
     * @return \Illuminate\Http\Response
    */
    public function store($id) {
        /**
         * 認証済みユーザ(閲覧者)が、$idのユーザをフォローする
         * 
         * (分解)
         * $user = \Auth::user(); // 自ユーザ(Userクラスのインスタンス)が$user変数へ代入される
         * $user->follow($id);    // そのインスタンスのfollow()メソッドを呼ぶ 
        */
        \Auth::user()->follow($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    /**
     * ユーザをアンフォローするアクション
     * 
     * @param
     *  $id 相手のユーザid
     * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
        // 認証済みユーザ(閲覧者)が、$idのユーザをアンフォローする
        \Auth::user()->unfollow($id);
        // 前のURLへリダイレクトする
        return back();
    }
}
