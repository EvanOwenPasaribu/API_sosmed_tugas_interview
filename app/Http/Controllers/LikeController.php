<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $post_id = $request->post_id;
        $user_id = auth('api')->user()->id;

        $user = Post::find($post_id);
        if(!$user){
            return response()->json(["error" => true, "message" => "Postingan Tidak Ditemukan", "code" => 401]);
        }

        $exist = Like::where("user_id", $user_id)->where("post_id", $post_id)->get();
        if(count($exist) > 0){
            return response()->json(["error" => true, "message" => "Anda Sudah Like Postingan Ini", "code" => 401]);
        }

        $like = new Like();
        $like->user_id = $user_id;
        $like->post_id = $post_id;
        $like->save();

        return response()->json(["error" => false, "message" => "Berhasil Like Postingan", "code" => 200]);
    }


    public function unlike(Request $request)
    {
        $post_id = $request->post_id;
        $user_id = auth('api')->user()->id;

        $user = Post::find($post_id);
        if(!$user){
            return response()->json(["error" => true, "message" => "Postingan Tidak Ditemukan", "code" => 401]);
        }

        $exist = Like::where("user_id", $user_id)->where("post_id", $post_id)->get();
        if(count($exist) == 0){
            return response()->json(["error" => true, "message" => "Anda Belum Like Postingan Ini", "code" => 401]);
        }

        $like = Like::where("user_id", $user_id)->where("post_id", $post_id);
        $like->delete();

        return response()->json(["error" => false, "message" => "Berhasil Unlike Postingan", "code" => 200]);
    }

}
