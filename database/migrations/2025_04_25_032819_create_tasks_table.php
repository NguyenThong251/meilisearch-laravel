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
        // Schema::create('tasks', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->dateTime('start_date');
        //     $table->dateTime('due_date');
        //     $table->string('estimated_time')->nullable();
        //     $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        //     $table->enum('priority', ['normal', 'urgent'])->default('normal');
        //     $table->integer('progress')->default(0);
        //     $table->json('file_urls')->nullable();
        //     $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
        //     $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
        //     $table->timestamps();
        // });
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->string('estimated_time')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed']);
            $table->enum('priority', ['normal', 'urgent']);
            $table->integer('progress');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->json('file_urls')->nullable();
            $table->timestamps();
            $table->index(['status', 'priority']);
            $table->index('creator_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};