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
        StatusTask::create(['description' => 'Activo']);
        StatusTask::create(['description' => 'Inactivo']);
        StatusTask::create(['description' => 'Cerrado']);
        StatusTask::create(['description' => 'Eliminado']);
    }
}
