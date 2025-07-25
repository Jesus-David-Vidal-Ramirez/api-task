<?php

namespace App\Http\Controllers;

use App\Events\NewTask;
use App\Models\Task;
use Illuminate\Http\Request;

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

        $response = Task::getTaskAll($limit, $offset);
        // NewTask::dispatch($response);
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = Task::createdTask($request);
        NewTask::dispatch($response);
        return $response;
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

    // /**
    //  * Update status the specified resource in storage.
    //  */
    public function updatedStatusTask(Request $request, $id)
    {
        return Task::updatedStatusTask($request, $id);
    }
}
