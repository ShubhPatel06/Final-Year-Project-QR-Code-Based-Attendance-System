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
        Schema::create('lecturer_allocations', function (Blueprint $table) {
            $table->id("allocation_id");
            $table->unsignedBigInteger('lecturer_id');
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('lecturer_id')->references('user_id')->on('lecturers');
            $table->foreign('lecture_id')->references('lecture_id')->on('lectures');
            $table->foreign('group_id')->references('group_id')->on('semester_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturer_allocations');
    }
};
