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
        Schema::create('practice_session_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_session_id')->constrained();
            $table->foreignId('question_id')->constrained();
            $table->foreignId('user_answer_id')->nullable()->constrained('answers', 'id');
            $table->foreignId('correct_answer_id')->constrained('answers', 'id');
            $table->integer('round_number');
            $table->integer('user_guess_time_ms')->nullable();
            $table->timestamps();

            $table->unique(
                ['practice_session_id', 'round_number'],
                'psq_session_round_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_session_question');
    }
};
