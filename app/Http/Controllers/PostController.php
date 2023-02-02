<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $data = Post::with(["images", 'likes', 'comments'])->get();
        return $data;
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'caption' => 'required',
            'image.*' => 'image|mimes:jpeg,jpg,png,gif,svg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => true, "list_error" => $validator->errors(), "code" => 400]);
        }

        $user_id = auth('api')->user()->id;

        $post = new Post();
        $post->user_id = $user_id;
        $post->caption = $request->caption;
        $post->save();

        $index = 0;
        if ($request->hasfile('image')) {
            $images = $request->file('image');
            foreach ($images as $image) {
                $index += 1;
                $filename = $index . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/img/post/', $filename);
                $postImage = new PostImage();
                $postImage->post_id = $post->id;
                $postImage->image = $filename;
                $postImage->save();
            }
        }

        return response()->json(["error" => false, "message" => "Berhasil Diposting", "code" => 200]);
    }

    public function update($post_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image.*' => 'image|mimes:jpeg,jpg,png,gif,svg,webp|max:4048'
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => true, "list_error" => $validator->errors(), "code" => 400]);
        }

        $user_id = auth('api')->user()->id;
        $post = Post::find($post_id);
        if ($post) {
            if ($user_id == $post->user_id) {
                $post->caption = $request->caption;
                $post->save();
            } else {
                return response()->json(["error" => true, "message" => "Bukan Postingan Anda", "code" => 403]);
            }
        } else {
            return response()->json(["error" => true, "message" => "Id Tidak Ditemukan", "code" => 401]);
        }

        $index = 0;
        if ($request->hasfile('image')) {
            $images = $request->file('image');
            foreach ($images as $image) {
                $index += 1;
                $filename = $index . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/img/post/', $filename);
                $postImage = new PostImage();
                $postImage->post_id = $post_id;
                $postImage->image = $filename;
                $postImage->save();
            }
        }

        return response()->json(["error" => false, "message" => "Berhasil Update Postingan", "code" => 200]);
    }

    public function delete($post_id)
    {
        $post = Post::find($post_id);
        $user_id = auth('api')->user()->id;

        if ($post) {
            if ($user_id == $post->user_id) {
                $post->delete();
                return response()->json(["error" => false, "message" => "Berhasil Dihapus", "code" => 200]);
            } else {
                return response()->json(["error" => true, "message" => "Bukan Postingan Anda", "code" => 403]);
            }
        }
        return response()->json(["error" => true, "message" => "Id Tidak Ditemukan", "code" => 400]);
    }
}
