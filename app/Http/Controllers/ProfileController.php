<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //


    public function show($id)
    {
        $user_id=Auth::user()->id;
        $profile = Profile::where('user_id', $id)->firstOrFail();
        $img = asset('storage/'. $profile->image);
        if($user_id==$profile->user_id){
        return response()->json(['Prosile'=>$profile,'Image'=>$img], 200);
    }else{
        return response()->json(['message'=>"Unauthurized !!"], 403);

    }
    }
    public function store(StoreProfileRequest $request)
    {

    $user_id = Auth::id();
    if(Profile::where('user_id',$user_id)->exists()){
        return response()->json(['Message'=>"This User Already Have Profule"], 200);
    }else{
        $validateDate = $request->validated();
        $validateDate['user_id']=$user_id;
        if($request->hasFile('image')){
            $path=$request->file('image')->store('photos','public');
            $validateDate['image']=asset('storage'. $path);
            // $imageUrl = asset('storage'. $path);
            $profile = Profile::create($validateDate);
            return response()->json(['message'=>'Profile Created Successfully','Profile'=>$profile], 200);
        }
    }
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $profile = Profile::where('user_id', $id)->firstOrFail();
        if ($user_id == $profile->user_id) {
            $validatedDate = $request->validated();
            $validatedDate['user_id'] = $user_id;
            $profile->update($validatedDate);
            return response()->json(['message' => "Updated Successfully", 'profile' => $profile], 201);
        } else {
            return response()->json(['error' => " Unauthurized !!"], 403);
        }
        // return response()->json(['user_id' => $user_id, 'profile' => $profile->user_id], 201);

    }

    public function destroy($id)
    {
        $user_id=Auth::user()->id;
        $profile = Profile::where('user_id', $id)->firstOrFail();
        if($user_id==$profile->id){
            $profile->delete();
            return response()->json("Deleted Successfully", 200);
        }else{
            return response()->json(['error' => " Unauthurized !!"], 403);
        }
    }
}
