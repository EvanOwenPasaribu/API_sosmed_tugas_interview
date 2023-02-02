<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    public function user_followed(){
        return $this->belongsTo(User::class, "user_account", "id");
    }

    public function user_follower(){
        return $this->belongsTo(User::class, "user_id", "id");
    }

}
