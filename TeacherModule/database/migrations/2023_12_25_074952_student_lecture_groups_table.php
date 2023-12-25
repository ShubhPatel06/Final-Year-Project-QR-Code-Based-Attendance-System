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
        Schema::create('student_lecture_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('adm_no');
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('adm_no')->references('adm_no')->on('students');
            $table->foreign('lecture_id')->references('lecture_id')->on('lectures');
            $table->foreign('group_id')->references('group_id')->on('semester_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_lecture_groups');
    }
};
