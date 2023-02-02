<?php

namespace App\Http\Controllers;

use App\Models\Following;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class FollowingController extends Controller
{
    public function follow(Request $request)
    {
        $user_account = $request->user_account;
        $user_id = auth('api')->user()->id;

        $user = User::find($user_account);
        if(!$user || $user_account == $user_id){
            return response()->json(["error" => true, "message" => "Akun Tidak Ditemukan", "code" => 401]);
        }

        $exist = Following::where("user_id", $user_id)->where("user_account", $user_account)->get();
        if(count($exist) > 0){
            return response()->json(["error" => true, "message" => "Anda Sudah Follow User Ini", "code" => 401]);
        }

        $following = new Following();
        $following->user_id = $user_id;
        $following->user_account = $user_account;
        $following->save();

        return response()->json(["error" => false, "message" => "Berhasil Follow User", "code" => 200]);
    }

    public function unfollow(Request $request)
    {
        $user_account = $request->user_account;
        $user_id = auth('api')->user()->id;

        $user = User::find($user_account);
        if(!$user || $user_account == $user_id){
            return response()->json(["error" => true, "message" => "Akun Tidak Ditemukan", "code" => 401]);
        }

        $exist = Following::where("user_id", $user_id)->where("user_account", $user_account)->get();
        if(count($exist) == 0){
            return response()->json(["error" => true, "message" => "Anda Belum Follow User Ini", "code" => 401]);
        }

        $following = Following::where("user_id", $user_id)->where("user_account", $user_account);
        $following->delete();

        return response()->json(["error" => false, "message" => "Berhasil Unfollow User", "code" => 200]);
    }
}
