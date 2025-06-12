<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;
use function PHPUnit\Framework\isEmpty;

class TaskController extends Controller
{



public function addToFavorites($taskId)
{
    Task::findOrFail($taskId);
    Auth::user()->favoritesTasks()->syncWithoutDetaching($taskId);
    return response()->json(['message'=>'Task Added To Favorites'], 200);
}

public function removeFromFavorites($taskId) {
   $isFav= Auth::user()->favoritesTasks();
    if($isFav->where('task_id',$taskId)->exists()){
        $isFav->detach($taskId);
        return response()->json(['message'=>'Task Removed From Favorites'], 200);
    }else{
        return response()->json(['message' => 'This task is not in your favorites'], 403);
    }
    }

public function getFavoriteTasks() {

    $favTasks=Auth::user()->favoritesTasks;
    return response()->json(['Fav Task'=>$favTasks], 200);
}















    public function getAllTasks()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);

    }

    public function addCategoriesToTask(Request $request, $taskId)
    {
        try {
            //code...
            $user_id = Auth::user()->id;
            $task = Task::findOrFail($taskId);
            if ($user_id == $task->user_id) {
                $task->categories()->attach($request->category_id);
                return response()->json('Category Attached Successfully', 200);
            } else {
                // return response()->json(['error' => " Unauthurized !!"], 403);
            throw new Exception('Unauthurized !!',403);
            }
        } catch (\Throwable $th) {

            // throw $th;
            // $th='Already Added To Favorites';
            return response()->json(['error'=>$th->getMessage()], 200);
            // return response()->json(['error'=>'Already Added To Favorites'], 403);
        }

    }

    public function getCategoriesOfOneTask($taskId)
    {
        $user_id = Auth::user()->id;
        $task = Task::with('categories')->findOrFail($taskId);
        if ($user_id == Task::findOrFail($taskId)->user_id) {
            return response()->json($task->categories, 200);
        } else {
            return response()->json(['error' => " Unauthurized !!"], 403);

        }
    }

    public function getTasksInOneCategory($categoryId)
    {
        $user_id = Auth::user()->id;
        $category = Category::findOrFail($categoryId);
        $tasks = $category->tasks()->where('user_id', $user_id)->get();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found for this user in this category'], 404);
        }
        return response()->json($tasks, 200);
    }


    public function getTaskUser($id)
    {
        $user_id = Auth::user()->id;
        $user = Task::findOrFail($id)->user;
        if ($user_id == $user->id) {
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => " Unauthurized !!"], 403);
        }

    }
    public function getTasksByPriorty()
    {
        try {
            $tasks = Auth::user()->tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
            if ($tasks->isEmpty()) {
                abort(code: 404, message: 'There Is No Data To Show !!');
            }
            return response()->json($tasks, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }












    public function index()
    {
        try {
            $tasks = Auth::user()->tasks;
            if ($tasks->isEmpty()) {
                abort(code: 404, message: 'There Is No Data To Show !!');
            }
            return response()->json($tasks, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function store(StoreTaskRequest $request)
    {

        $user_id = Auth::user()->id;
        $validatedDate = $request->validated();
        $validatedDate['user_id'] = $user_id;
        $task = Task::create($validatedDate);
        return response()->json($task, 201);
    }


    public function show(string $id)
    {
        try {
            $user_id = Auth::user()->id;
            $task = Task::findOrFail($id);
            if ($user_id == $task->user_id) {
                return response()->json($task, 200);
            } else {
                return response()->json(['error' => " Unauthurized !!"], 403);
            }
        } catch (ModelNotFoundException) {
            return response()->json(['error' => "This ID : $id Not Found!!"], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }


    public function update(UpdateTaskRequest $request, $id)
    {
        try {

            $user_id = Auth::id();
            $task = Task::findOrFail($id);
            if ($task->user_id == $user_id) {
                $task->update($request->validated());
                return response()->json($task, 200);
            } else {
                // return response()->json(['error' => " Unauthurized !!"], 403);
                throw new Exception('Unauthurized !!',403);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Task Not Found !!"], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $th->getMessage()
            ], 500);
        }

    }


    public function destroy($id)
    {

        try {
            $user_id = Auth::id();
            $task = Task::findOrFail($id);
            if ($user_id == $task->user_id) {
                $task->delete();
                return response()->json('Deleted Successfully', 200);
            } else {
                // return response()->json(['error' => " Unauthurized !!"], 403);
            throw new Exception('Unauthurized !!',403);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something Went Wrong !!','message' => "This Task ID : $id Not Found To Delete"], 404);

        }
        catch (\Throwable $th) {
            return response()->json(['error' => 'Something Went Wrong !!', 'message' => $th->getMessage()], 500);

        }
    }
}
