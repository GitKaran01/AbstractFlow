<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('client_name');
            $table->string('loan_number');
            $table->string('product_type'); // e.g., Typing, Full Search
            
            // Property details
            $table->text('property_address');
            $table->string('city');
            $table->string('state');
            $table->string('county');
            $table->string('parcel_id');
            $table->string('borrower_name');
            $table->string('co_borrower_name')->nullable(); 

            // Cost & Rates Matrix from your second sheet
            $table->decimal('search_rate', 8, 2)->default(0.00);
            $table->decimal('copy_rate', 8, 2)->default(0.00);
            $table->decimal('total_cost', 8, 2)->default(0.00); // calculated as search_rate + copy_rate

            // Main Assignment & Smart Backup Assignment
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('backup_user_id')->nullable()->constrained('users')->onDelete('set null'); // The Backup Abstractor column
            
            $table->enum('status', ['unassigned', 'assigned', 'in_progress', 'submitted_qc', 'stalled', 'completed'])->default('unassigned');
            
            // Abstractor deliverables
            $table->date('search_date')->nullable();
            $table->string('pdf_path')->nullable();
            $table->text('qc_notes')->nullable(); 
            
            $table->dateTime('due_date');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};