

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->unique()->nullable(); // e.g., V-101 (Null for admin)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'abstractor'])->default('abstractor');
            
            // Coverage details from your sheet
            $table->string('covered_state')->nullable(); // e.g., "NJ"
            $table->string('covered_county')->nullable(); // e.g., "Camden"
            
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};