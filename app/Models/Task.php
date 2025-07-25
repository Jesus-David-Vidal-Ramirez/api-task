<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Task extends Model
{
    use HasFactory;

    public $timestamp = false;
    protected $table = 'task';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'user_id',
        'responsible',
        'status_id',
        'status',
        'date_start',
        'date_end',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function status_id(): BelongsTo
    {
        return $this->belongsTo(StatusTask::class, 'status_id')->select('id', 'description');
    }
    public function user_id()
    {
        return $this->hasOne(User::class, 'id', 'responsible')->select('id', 'name');
    }

    /**
     * list task getTaskAll
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public static function getTaskAll($limit, $offset): JsonResponse
    {
        try {
            //code...
            $user = User::getUser();
            if (Gate::allows('read-task', $user->original['user'])) {
                $task = Task::with(['status_id', 'user_id'])->whereStatus('0')->skip($offset)
                    ->take($limit)->get([
                        'date_end',
                        'date_start',
                        'description',
                        'id',
                        'responsible',
                        'status_id',
                        'status',
                        'title',
                        'user_id',
                    ]);
            } else {
                $task = Task::with(['status_id', 'user_id'])->whereStatus('0')->whereUserId($user->original['user']->id)->skip($offset)
                    ->take($limit)->get([
                        'date_end',
                        'date_start',
                        'description',
                        'id',
                        'responsible',
                        'status_id',
                        'status',
                        'title',
                        'user_id',
                    ]);
            }

            if ($task->isEmpty()) {
                return response()->json(['message' => 'sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['message' => 'informacion encontrada', 'data' => $task], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo getTaskAll' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * creating of task createdTask
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function createdTask($request): JsonResponse
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:255',
                'title' => 'required|string|max:50',
                'responsible' => 'required|integer',
                'date_start' => 'required|string',
                'date_end' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
            }

            $userId = JWTAuth::parseToken()->authenticate()->id;

            $taskCreated = Task::create([
                'date_end' => $request->get('date_end'),
                'date_start' => $request->get('date_start'),
                'description' => $request->get('description'),
                'responsible' => $request->get('responsible'),
                'status_id' => 1,
                'title' => $request->get('title'),
                'user_id' => $userId,
            ]);

            $task = Task::with(['status_id', 'user_id'])->whereStatus(0)->whereId($taskCreated->id)->first();;

            return response()->json(['message' => 'Creacion exitosa', 'data' => $task], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo createdTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * searching of a task taskById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function taskById($id): JsonResponse
    {
        try {
            //code...
            $task = Task::with(['status_id', 'user_id'])->whereStatus(0)->whereId($id)->first([
                'date_end',
                'date_start',
                'description',
                'id',
                'responsible',
                'status_id',
                'status',
                'title',
                'user_id',
            ]);

            if (empty($task)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            if (!Gate::allows('read-by-id-task', $task)) {
                return response()->json(['message' => 'Sin Autorizacion', 'data' => []], Response::HTTP_FORBIDDEN);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo taskById' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion encontrada', 'data' => $task], Response::HTTP_OK);
    }

    /**
     * updating the of a task updatedTask
     * @param mixed $data
     * @param mixed $statusTask
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updatedTask($data, $task): JsonResponse
    {
        try {
            //Autenticacion
            $getTask = Task::whereId($task->id)->whereStatus(0)->first();
            if (!Gate::allows('update-task', $getTask)) {
                return response()->json(['message' => 'Sin Autorizacion', 'data' => []], Response::HTTP_FORBIDDEN);
            }

            if (empty($getTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            Task::whereId($task->id)->update([
                'date_end' => $data->date_end,
                'date_start' => $data->date_start,
                'description' => $data->description,
                'responsible' => $data->responsible,
                'title' => $data->title,
            ]);

            $task = Task::taskById($task->id);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo updatedTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion actualizada', 'data' => $task->original['data']], Response::HTTP_OK);
    }

    /**
     * Task Of Deleted deletedTask
     * @param mixed $statusTask
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deletedTask($task): JsonResponse
    {
        try {
            //code...
            $getTask = Task::find(id: $task->id);
            if (!Gate::allows('deleted-task', $getTask)) {
                return response()->json(['message' => 'Sin Autorizacion', 'data' => []], Response::HTTP_FORBIDDEN);
            }

            if (empty($getTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            Task::whereId($task->id)->whereStatus(0)->update(['status' => 1]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo deletedStatuTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion eliminada', 'data' => ['id' => $getTask->id]], Response::HTTP_OK);
    }

    /**
     * updating the status of a task updatedStatusTask
     * @param mixed $data
     * @param mixed $statusTask
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updatedStatusTask($request, $id): JsonResponse
    {
        try {
            $statusTaskExist = StatusTask::statuTaskById($request->status);
            if (!$statusTaskExist->getData()->data) {
                return $statusTaskExist;
            }

            //Autenticacion
            $getTask = Task::whereId($id)->whereStatus(0)->first();
            if (!Gate::allows('update-task', $getTask)) {
                return response()->json(['message' => 'Sin Autorizacion', 'data' => []], Response::HTTP_FORBIDDEN);
            }

            if (empty($getTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            /// Validar el status a enviar
            Task::whereId($id)->update([
                'status_id' => $request->status
            ]);

            $task = Task::taskById($id);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo updatedTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion actualizada', 'data' => $task->original['data']], Response::HTTP_OK);
    }
}
