<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // projector, laptop, microphone, camera, laboratory
            $table->string('serial_number')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // available, borrowed, maintenance
            $table->integer('quantity')->default(1);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};