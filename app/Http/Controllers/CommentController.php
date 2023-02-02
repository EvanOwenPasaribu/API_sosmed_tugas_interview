<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(["error"=> true, "list_error"=>$validator->errors(), "code" => 400]);
        }

        $post_id = $request->post_id;
        $comment = $request->comment;
        $user_id = auth('api')->user()->id;

        $user = Post::find($post_id);
        if(!$user){
            return response()->json(["error" => true, "message" => "Postingan Tidak Ditemukan", "code" => 401]);
        }

        $insert = new Comment();
        $insert->user_id = $user_id;
        $insert->post_id = $post_id;
        $insert->comment = $comment;
        $insert->save();

        return response()->json(["error" => false, "message" => "Berhasil Komentari Postingan", "code" => 200]);
    }
}
