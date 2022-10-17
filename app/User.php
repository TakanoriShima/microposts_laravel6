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
        $this->loadCount("microposts");
    }
    
}
