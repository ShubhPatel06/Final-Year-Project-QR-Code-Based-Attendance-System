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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id("record_id");
            $table->unsignedBigInteger("lecture_id");
            $table->date("date");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('lecture_id')->references('lecture_id')->on('lectures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};