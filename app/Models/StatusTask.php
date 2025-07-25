<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StatusTask extends Model
{
    use HasFactory;

    public $timestamp = false;
    protected $table = 'statustask';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * List StatusTask getStatusAll
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatusAll(): JsonResponse
    {
        try {
            //code...
            $statusTask = StatusTask::whereStatus('0')->get(['id', 'title', 'description']);
            if ($statusTask->isEmpty()) {
                return response()->json(['message' => 'sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['message' => 'informacion encontrada', 'data' => $statusTask], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo getStatusAll' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * creating of StatusTask createdStatuTask
     * @param mixed $description
     * @return \Illuminate\Http\JsonResponse
     */
    public static function createdStatuTask($request): JsonResponse
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
            }

            $titleExits = StatusTask::statuTaskByTitle($request->get('title'));
            if (!empty($titleExits->getData()->data)) {
                return $titleExits;
            }

            $statusTaskCreated = StatusTask::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
            ]);

            return response()->json(['message' => 'Creacion exitosa', 'data' => $statusTaskCreated], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo createdStatuTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * searching of a StatusTask statuTaskById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function statuTaskById($id): JsonResponse
    {
        try {
            //code...
            $statuTask = StatusTask::whereStatus(0)->whereId($id)->first(['id', 'title', 'description']);

            if (empty($statuTask)) {
                return response()->json(['message' => 'Estado No Encontrado', 'data' => []], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo statuTaskById' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion encontrada', 'data' => $statuTask], Response::HTTP_OK);
    }

    /**
     * updating the of a StatusTask updatedStatuTask
     * @param mixed $data
     * @param mixed $statusTask
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updatedStatuTask($data, $statusTask): JsonResponse
    {
        try {
            //code...

            $getStatuTask = StatusTask::whereId($statusTask->id)->whereStatus(0)->first();

            if (empty($getStatuTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }

            $titleExits = StatusTask::statuTaskByTitle($data->title);
            if (!empty($titleExits->getData()->data)) {
                return $titleExits;
            }
            StatusTask::whereId($statusTask->id)->whereStatus(0)->update(['title' => $data->title, 'description' => $data->description]);

            $getStatuTask = StatusTask::whereId($statusTask->id)->first(['id', 'title', 'description']);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo updatedStatuTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Informacion actualizada', 'data' => $getStatuTask], Response::HTTP_OK);
    }
    /**
     * StatusTask Of Deleted deletedStatuTask
     * @param mixed $statusTask
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deletedStatuTask($statusTask): JsonResponse
    {
        try {
            //code...

            $getStatuTask = StatusTask::whereId($statusTask->id)->whereStatus(0)->first();

            if (empty($getStatuTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }
            StatusTask::whereId($statusTask->id)->whereStatus(0)->update(['status' => 1]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo deletedStatuTask' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'informacion eliminada', 'data' => ['id' => $getStatuTask->id]], Response::HTTP_OK);
    }

    /**
     * searching of a StatusTask Title statuTaskByTitle
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function statuTaskByTitle($title): JsonResponse
    {
        try {
            //code...
            $statuTask = StatusTask::whereStatus(0)->whereTitle($title)->first();

            if (empty($statuTask)) {
                return response()->json(['message' => 'Sin informacion', 'data' => []], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error en el metodo statuTaskByTitle' . json_encode($th->getMessage()));
            return response()->json(['message' => 'Houston tenemos problemas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Titulo del estado existente', 'data' => $statuTask], Response::HTTP_OK);
    }
}
