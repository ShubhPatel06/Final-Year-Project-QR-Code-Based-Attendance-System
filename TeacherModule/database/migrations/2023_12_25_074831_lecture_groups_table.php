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
        Schema::create('lecture_groups', function (Blueprint $table) {
            $table->foreignId('lecture_id');
            $table->foreignId('group_id');

            $table->foreign('lecture_id')->references('lecture_id')->on('lectures');
            $table->foreign('group_id')->references('group_id')->on('semester_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_groups');
    }
};
