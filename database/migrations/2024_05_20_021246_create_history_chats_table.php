<?php

use App\Models\HistoryTopic;
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
        Schema::create('history_chats', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->foreignIdFor(HistoryTopic::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_chats');
    }
};
