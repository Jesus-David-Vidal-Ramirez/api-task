<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statustask', function (Blueprint $table) {
            $table->id(); // Este campo debe ser la clave primaria
            $table->string('description');
            $table->tinyInteger('status')->default(0)->comment('0 = Activo 1 = Inactivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statustask');
    }
};
