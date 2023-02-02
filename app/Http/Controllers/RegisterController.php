<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:22',
            'phone_number' => 'required|string|max:22',
            'image.*' => 'image|mimes:jpeg,jpg,png,gif,svg,webp|max:4048',
            'birth_date' => 'required|date',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => true, "list_error" => $validator->errors(), "code" => 400]);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();
        if (!isset($client->id)) {
            return response()->json(['error' => true, 'message' => "Gagal Mengambil Token, Silahkan Install Passport", 'syntax' => "php artisan passport:install", "code" => 401]);
        }
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
        

        $image_file_name = "";
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path() . '/img/user/', $filename);
            $image_file_name = $filename;
        }

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->username = $request->username;
        $profile->phone_number = $request->phone_number;
        $profile->birth_date = $request->birth_date;
        if ($image_file_name != "") {
            $profile->image = $image_file_name;
        }
        $profile->save();
        
        return response()->json(['error' => false, 'message' => "Berhasil Register", 'token' => $accessToken, "code" => 200]);
    }
}
