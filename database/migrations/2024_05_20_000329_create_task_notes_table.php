<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_notes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('due_date')->nullable();
            $table->integer('time')->nullable();
            $table->string('reminder')->nullable();
            $table->integer('priority', false, false)->default(0);
            $table->string('type')->default('task');
            $table->boolean('is_complete')->default(0);
            $table->boolean('is_trash')->default(0);
            $table->boolean('is_shortcut')->default(0);
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreignId('user_id');
            $table->foreignId('list_id')->nullable();
            $table->foreignId('notebook_id')->nullable();
            $table->foreignId('tag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_notes');
    }
};
