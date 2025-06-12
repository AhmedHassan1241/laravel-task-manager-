<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => "required|string|max:255",
            "email" => "required|string|max:255|email|unique:users,email",
            'password' => "required|string|max:255"
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            "password" => Hash::make(value: $request->password)
        ]);
        //To send Emails To user with Content in WelcomeMail
        Mail::to($user->email)->send(new WelcomeMail($user));

        return response()->json(['message' => "User Registered Successfully", "User" => $user], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => "required|string|email",
            'password' => "required|string"
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->firstOrFail();
            // $user=Auth::user(); == $user=User::where('email',$request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => "Login Successfully", "User" => $user, "Token" => $token], 200);
        } else {
            return response()->json(['message' => 'Invalid Email Or Password !!'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // ==Auth::logout(); not delete token
        //Auth::user()->currentAccessToken()->delete(); == $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => "User Logout Successfully"], 200);
    }
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json(['message'=>"Logout Successfully"], 200);
    // }































    public function getProfile($id)
    {
        $user_id = Auth::user()->id;
        $profile = User::find($id)->profile;
        if ($user_id == $profile->user_id) {
            return response()->json($profile, 200);
        } else {
            return response()->json(['error' => " Unauthurized !!"], 403);
        }
    }
    public function GetUser(){
        $user_id=Auth::user()->id;
        $user = User::with('profile')->findOrFail($user_id);
        return (new UserResource($user))->response()->setStatusCode(200);
        // return response()->json(['User '=>$user], 200);
    }



    public function getUserTasks($id)
    {
        $user_id = Auth::user()->id;
        $tasks = User::findOrFail($id)->tasks;
        if ($user_id == $id) {
            return response()->json($tasks, 200);
        } else {
            return response()->json(['error' => " Unauthurized !!"], 403);
        }

    }

    //
    function index()
    {

    }

    public function checkUser(int $id)
    {


    }

}
