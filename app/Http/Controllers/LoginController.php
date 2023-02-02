<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();
            
        if(!isset($client->id)){
            return response()->json(['error'=>true, 'message' => "Gagal Mengambil Token, Silahkan Install Passport", 'syntax'=> "php artisan passport:install", "code" => 401]);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["error"=> true, "list_error"=>$validator->errors(), "code" => 400]);
        }

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            $token = Request::create(
                'oauth/token',
                'POST'
            );

            $response = \Route::dispatch($token);
            $data = json_decode($response->getContent(), true);
            $accessToken = $data['access_token'];
            return response()->json(['error' => false, 'message' => "Berhasil Login", 'token' => $accessToken, 'code' => 200]);
        }

        return response()->json(['error' => true, 'message' => "Email Atau Password Salah", 'code' => 401]);
    
    }
}
