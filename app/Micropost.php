<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ["content"];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    
    /**
     * micropostがいいねされているユーザの関係を記述
    */
    public function favorite_users() {
        return $this->belongsToMany(User::class, "favorites", "micropost_id", "user_id")->withTimestamps();
    }
}
