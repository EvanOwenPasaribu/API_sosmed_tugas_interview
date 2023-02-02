<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        
        $users = User::with(["profile", "following.user_followed", "followers.user_follower"])->get();
        $data = [
            'users' => $users->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'profile' => $user->profile,
                    'following' => $user->following->map(function ($follow) {
                        return [
                            'id' => $follow->user_followed->id,
                            'username' => $follow->user_followed->username,
                        ];
                    }),

                    'followers' => $user->followers->map(function ($follow) {
                        return [
                            'id' => $follow->user_follower->id,
                            'username' => $follow->user_follower->username,
                        ];
                    })
                ];
            })
        ];

        return $data;
    }

    public function search(Request $request){
        $keyword = $request->keyword;
        
        //$users = User::searchUser($keyword)->with(["profile::keyword", "following.user_followed", "followers.user_follower"])->get();
        $users = User::with(["profile", "following.user_followed", "followers.user_follower"])
        ->whereHas('profile', function($query) use ($keyword) {
            $query->where('username', 'like', "%$keyword%");
            $query->orWhere('first_name', 'like', "%$keyword%");
            $query->orwhere('last_name', 'like', "%$keyword%");
        })->get();

        $data = [
            'jumlah' => count($users),
            'users' => $users->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'profile' => $user->profile,
                    'following' => $user->following->map(function ($follow) {
                        return [
                            'id' => $follow->user_followed->id,
                            'username' => $follow->user_followed->username,
                        ];
                    }),

                    'followers' => $user->followers->map(function ($follow) {
                        return [
                            'id' => $follow->user_follower->id,
                            'username' => $follow->user_follower->username,
                        ];
                    })
                ];
            })
        ];

        return $data;
    }
}
