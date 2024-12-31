<?php

namespace Database\Seeders;

use App\Models\StatusTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTaskSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusTask::create(['title' => 'Activo', 'description' => 'Actividad sin iniciar']);
        StatusTask::create(['title' => 'Inactivo','description' => 'Actividad Cancelada']);
        StatusTask::create(['title' => 'Pausado','description' => 'Actividad pausada']);
        StatusTask::create(['title' => 'Cerrado', 'description' => 'Actividad cerrada']);
        StatusTask::create(['title' => 'Eliminado', 'description' => 'Actividad Eliminada']);
    }
}
