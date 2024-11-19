<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Implementamos la paginacion
        $limit  = (int) ($request->limit ?? 25);
        $offset = (int) ($request->offset ?? 0);
        return Task::getTaskAll($limit, $offset);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Task::createdTask($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Task::taskById($id);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, Task $task)
    {
        return Task::updatedTask($request, $task);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Task $task)
    {
        return Task::deletedTask($task);
    }
}
