<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->foreignId('course_id');
            $table->foreignId('instructor_id');
            $table->string('description');
            $table->timestamps();
        });
    }
CREATE TABLE evaluates (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
student_id BIGINT UNSIGNED,
course_id BIGINT UNSIGNED,
instructor_id BIGINT UNSIGNED,
description VARCHAR(255),
created_at TIMESTAMP NULL DEFAULT NULL,
updated_at TIMESTAMP NULL DEFAULT NULL,
);
SAmVxONzefW2cbs8zGTWl8XwKaA0fZzyNnog4BsmcgoBmA61OeRwtni9doso4ViF
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluates');
    }
};
