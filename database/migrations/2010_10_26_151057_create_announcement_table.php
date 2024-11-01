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
        Schema::create('announcement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('instructor_id');
            $table->string('announcement_type');
            $table->string('vote_title');
            $table->string('choices');
            $table->string('text_title');
            $table->string('announcement_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement');
    }
};
