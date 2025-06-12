<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Console\View\TaskResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
//->middleware('auth:sanctum'); //معناها ان لازم يتاكد ان اللي داخل دلوقتي هو اليوزر المسجل


Route::post('register',[UserController::class,"register"]);
Route::post('login',[UserController::class,"login"]);
Route::post('logout',[UserController::class,"logout"])->middleware('auth:sanctum');
//->middleware('auth:sanctum'); //معناها ان لازم يتاكد ان اللي داخل دلوقتي هو اليوزر المسجل


// عملت ميدل وير للجروب ده كله عشان مكررش ف كل راوت
Route::middleware(['auth:sanctum'])->group(callback: function () {

Route::get('welcome',[WelcomeController::class,'welcome'] );
// Route::get('user',[UserController::class,'index']);
// Route::get('user/{id}',[UserController::class,'checkUser']);

// Route::get('tasks',[TaskController::class,'index']);
// Route::post('tasks',[TaskController::class,'store']);
// Route::put('tasks/{id}',[TaskController::class,'update']);
// Route::get('tasks/{id}',[TaskController::class,'show']);
// Route::delete('tasks/{id}',[TaskController::class,'destroy']);


// عملت اختصار للي ثابت في الراوت بدل ماكررهم في كل راوت
Route::prefix('profile')->group(function(){
Route::post('',[ProfileController::class,'store']); //done
Route::get('/{id}',[ProfileController::class,'show']); //done
Route::Put('/{id}',[ProfileController::class,'update']); //done
Route::delete('/{id}',[ProfileController::class,'destroy']); //done

});

Route::prefix("user/")->group(function(){
    Route::get('',[UserController::class,'GetUser']); //done
    Route::get('{id}/profile',[UserController::class,'getProfile']); //done
    Route::get('{id}/tasks',[UserController::class,'getUserTasks']); //done
});


Route::apiResource('tasks',TaskController::class); //done
Route::get('task/all',[TaskController::class,'getAllTasks'])->middleware('CheckUser'); //done
Route::get('task/ordered',[TaskController::class,'getTasksByPriorty']); //done


// Route::post('task/{id}/favorite',[TaskController::class,'addToFavorites']); //done
// Route::delete('task/{id}/favorite',[TaskController::class,'removeFromFavorites']); //done
// Route::get('task/favorites',[TaskController::class,'getFavoriteTasks']); //done

Route::post('task/{id}/favorite',[TaskController::class,'addToFavorites']);
Route::delete('task/{id}/favorite',[TaskController::class,'removeFromFavorites']);
route::get('task/favorites',[TaskController::class,"getFavoriteTasks"]);


Route::get('task/{id}/user',[TaskController::class,'getTaskUser']); //done
Route::post('tasks/{taskId}/categories',[TaskController::class,'addCategoriesToTask']); //done
Route::get('tasks/{taskId}/categories',[TaskController::class,'getCategoriesOfOneTask']); //done
Route::get('categories/{categoryId}/tasks',[TaskController::class,"getTasksInOneCategory"]); //done


});
