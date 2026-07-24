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
        Schema::create('learning_session_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_session_id')
                ->constrained('learning_sessions');
            $table->string('type'); // start, pause, resume, stop`
            $table->dateTime('occurred_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_session_logs');
    }
};
