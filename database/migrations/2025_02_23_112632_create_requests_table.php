<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // $table->foreign('user_id')->references('id')->on('users');
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->json('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', [
                'pending', 'assigned', 'in_progress', 'awaiting_customer', 
                'awaiting_payment', 'completed', 'customer_approved', 
                'worker_marked_completed', 'canceled_by_customer', 
                'canceled_by_worker', 'canceled_by_admin', 'rejected', 
                'failed', 'disputed', 'refunded'
            ])->default('pending');
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
