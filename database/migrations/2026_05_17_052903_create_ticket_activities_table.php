<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ticket_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('note'); // Short message
            $table->enum('type', ['manual', 'system'])->default('manual');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ticket_activities');
    }
};