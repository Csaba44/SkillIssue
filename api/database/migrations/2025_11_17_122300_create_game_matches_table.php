<?php

use App\GameResultEnum;
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
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('opponent_id')->constrained('users', 'id');
            $table->integer('elo_before');
            $table->integer('xp_before');
            $table->integer('elo_after')->nullable();
            $table->integer('xp_after')->nullable();
            $table->enum('match_result', GameResultEnum::cases())->default(GameResultEnum::PENDING);
            $table->uuid('match_uuid');
            $table->timestamps();


            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_matches');
    }
};
