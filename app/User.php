<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 「一気に保存可能」なパラメータの指定
     * create()を使ってデータを保存する時には、Modelファイル内に$fillableを定義
     * create()で保存可能なパラメータを配列として代入しておく必要がある。
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     * パスワードなどの秘匿しておきたいカラムを隠してくれる
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     * 
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
    // ユーザが所有する投稿( Micropostモデルとの関係を定義 )
    public function microposts(){
        return $this->hasMany(Micropost::class);
    }
    
    // ユーザに関係するモデルの件数をロードする
    public function loadRelationshipCounts() {
        $this->loadCount(["microposts", "followings", "followers", "favorites"]);
    }
    
    /**
     * このユーザがフォロー中のユーザ
     * 第一引数：関係先のModelクラス(User::class)
     * 第二引数：中間テーブル(user_follow)
     * 第三引数：中間テーブルに保存されている自分のidを示すカラム名(user_id)
     * 第四引数：中間テーブルに保存されている関係先のidを示すカラム名(folow_id)
     * 戻り値は、フォロー関係を表すオブジェクト
    */
    public function followings() {
        return $this->belongsToMany(User::class, "user_follow", "user_id", "follow_id")->withTimestamps();
    }
    
    
    /**
    * このユーザをフォロー中のユーザ
    */
    public function followers() {
        return $this->belongsToMany(User::class, "user_follow", "follow_id", "user_id")->withTimestamps();
    }
    
    /**
     * $userIdで指定されたユーザをフォローする
    */
    public function follow($userId) {
        // すでにフォローしているか
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            // 上記以外はフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする
    */
    public function unfollow($userId) {
        // すでにフォローしているか
        $exist =$this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            // フォロー済み、かつ、自分自身でない場合はフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 上記以外の場合は何もしない
            return false;
        }
    }
    
    /**
     * 指定された$userIdのユーザをこのユーザがフォロー中であるかを調べる
     * フォロー中ならtrueを返す
    */
    public function is_following($userId) {
        // フォロー中のユーザの中に$userIdのものが存在するか
        return $this->followings()->where("follow_id", $userId)->exists();
        
    }
    
    /**
     * タイムライン用のmicropostを取得するためのメソッド
     * このユーザとフォロー中のユーザの投稿に絞り込む
    */
    public function feed_microposts() {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck("users.id")->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn("user_id", $userIds);
    }
    
    /**
     * 中間テーブルであるfavoriteテーブルとの関係性を記述
     * このユーザがいいねをしているメッセージ(microposts)
    */
    public function favorites() {
        return $this->belongsToMany(Micropost::class, "favorites", "user_id", "micropost_id")->withTimestamps();
    }
    
    /**
     * $micropostId で指定されたmicropostをこのユーザがすでに"いいね"しているか調べる。
     * すでに"いいね"していたらtrueを返す
    */
    public function is_favorite($micropostId) {
        return $this->favorites()->where("micropost_id", $micropostId)->exists();
    }
    
    /**
     * $micropostId で指定したmicropostをいいねする
     * @param int $micropostId
    */
    public function favorite($micropostId) {
        // すでにいいねしているのか
        $exist = $this->is_favorite($micropostId);
        
        if ($exist) {
            return false;
        } else {
            // 上記以外は"いいね"する
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    /**
     * $micropostId で指定したmicropostのいいねを解除する
     * @param int $micropostId
    */
    public function unfavorite($micropostId) {
        // すでにいいねしているのか
        $exist = $this->is_favorite($micropostId);
        
        if ($exist) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
        
    }
}
