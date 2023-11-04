<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id("lecture_id");
            $table->string("lecture_code");
            $table->string("lecture_name");
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->integer('total_hours');
            $table->string("day");
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('course_id')->references('course_id')->on('courses');
            $table->foreign('lecturer_id')->references('user_id')->on('lecturers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
