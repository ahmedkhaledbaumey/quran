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
        Schema::create('test__users', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('student_id') ; 
            $table->foreign('student_id')->references('id')->on('users');
            $table->unsignedBigInteger('teacher_id') ; 
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->unsignedBigInteger('test_id') ; 
            $table->foreign('test_id')->references('id')->on('tests');
            $table->integer('grade') ; 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test__users');
    }
};
