<?php

namespace App\Http\Controllers;

use App\Models\StatusTask;
use Illuminate\Http\Request;

class StatusTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StatusTask::getStatusAll();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return StatusTask::createdStatuTask($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return StatusTask::statuTaskById($id);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, StatusTask $StatusTask)
    {
        return StatusTask::updatedStatuTask($request, $StatusTask);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(StatusTask $StatusTask)
    {
        return StatusTask::deletedStatuTask($StatusTask);
    }
}
