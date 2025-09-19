<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('due_at')->nullable();
            $table->unsignedTinyInteger('priority')->default(2); // 1=High,2=Medium,3=Low
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['completed_at', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};


