<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    // indexの実装
    public function index(){
        
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy("id", "desc")->paginate(10);
        
        // ユーザ一覧ビューでそれを表示
        return view("users.index", [
            "users" => $users,
        ]);
        
    }
    
    public function show($id){
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザの投稿一覧を作成日時順の降順で取得
        $microposts = $user->feed_microposts()->orderBy("created_at", "desc")->paginate(10);

        // ユーザ詳細ビューでそれを表示
        return view("users.show", [
            "user" => $user,
            "microposts" => $microposts,
        ]);
    }
    
    public function destroy($id){
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        if (\Auth::id() === $user) {
            $user->delete();
        }
        
        
        // トップページへリダイレクト
        return redirect("/");
    }
    
    
    /**
     * ユーザのフォロー一覧ページを表示するアクション
     * 
     * @param
     *  $id ユーザのid
    */
    public function followings($id) {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);
        
        // フォロー一覧ビューでそれらを表示
        return view("users.followings", [
            "user" => $user,
            "users" => $followings,
        ]);
    }
    
    /**
     * ユーザのフォロワー一覧ページを表示するアクション
     * 
     * @param
     *  $id ユーザのid
    */
    public function followers($id) {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        //関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);
        
        // フォロワー一覧ビューでそれらを表示
        return view("users.followers", [
            "user" => $user,
            "users" => $followers,
        ]);
    }
    
    
    /**
     * ユーザーのいいね欄を表示するアクション
     * 
     * 
    */
    
    public function favorites($id) {
        
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        //関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザの"いいね"一覧を取得
        $favorites = $user->favorites()->paginate(10);
        
        // "いいね"一覧ビューで表示
        return view("users.favorites", [
            "user" => $user,
            "microposts" => $favorites,
        ]);
    }
}
