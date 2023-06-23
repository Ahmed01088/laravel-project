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
        Schema::dropIfExists('notifications');
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('body');
            $table->unsignedBigInteger('student_affair_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('lecturer_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->date('date');
            $table->string('type');
            $table->foreign('student_affair_id')->references('id')->on('student_affairs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('lecturer_id')->references('id')->on('lecturers')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
